<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\IpPool;
use App\Models\IpAddress;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class IpamController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = IpPool::withCount(['addresses as total_ips', 
            'addresses as allocated_ips' => function($q) {
                $q->where('status', 'allocated');
            },
            'addresses as available_ips' => function($q) {
                $q->where('status', 'available');
            }
        ]);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('network', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $pools = $query->latest()->paginate($request->get('limit', 15));

        $stats = [
            'total_pools' => IpPool::count(),
            'total_ips' => IpAddress::count(),
            'total_allocated' => IpAddress::where('status', 'allocated')->count(),
            'total_available' => IpAddress::where('status', 'available')->count(),
        ];

        return response()->json([
            'pools' => $pools,
            'stats' => $stats
        ]);
    }

    public function showPool(IpPool $pool, Request $request): JsonResponse
    {
        $addresses = $pool->addresses()
            ->with('customer:id,name,identifier')
            ->when($request->filled('status'), function($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->filled('search'), function($q) use ($request) {
                $q->where('address', 'like', "%{$request->search}%");
            })
            ->paginate($request->get('limit', 50));

        return response()->json($addresses);
    }

    public function storePool(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'network' => 'required|ip',
            'prefix' => 'required|integer|min:8|max:30',
            'gateway' => 'nullable|ip',
            'dns_primary' => 'nullable|ip',
            'dns_secondary' => 'nullable|ip',
            'type' => 'required|in:public,private,cgnat',
            'description' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            
            $pool = IpPool::create($validated);
            $this->generateIpAddresses($pool);

            DB::commit();

            return response()->json([
                'message' => 'IP Pool berhasil dibuat',
                'pool' => $pool->loadCount('addresses')
            ], 210);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal membuat pool: ' . $e->getMessage()], 500);
        }
    }

    public function destroyPool(IpPool $pool): JsonResponse
    {
        if ($pool->addresses()->where('status', 'allocated')->exists()) {
            return response()->json(['message' => 'Pool tidak bisa dihapus karena masih ada IP yang teralokasi.'], 422);
        }

        $pool->delete();
        return response()->json(['message' => 'IP Pool berhasil dihapus']);
    }

    public function allocate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ip_address_id' => 'required|exists:ip_addresses,id',
            'customer_id' => 'required|exists:customers,id',
        ]);

        $address = IpAddress::findOrFail($validated['ip_address_id']);
        
        if ($address->status === 'allocated') {
            return response()->json(['message' => 'IP sudah teralokasi.'], 422);
        }

        $address->update([
            'status' => 'allocated',
            'customer_id' => $validated['customer_id'],
        ]);

        return response()->json([
            'message' => 'IP berhasil dialokasikan',
            'address' => $address->load('customer')
        ]);
    }

    public function release(IpAddress $address): JsonResponse
    {
        $address->update([
            'status' => 'available',
            'customer_id' => null,
        ]);

        return response()->json(['message' => 'IP berhasil dilepas']);
    }

    private function generateIpAddresses(IpPool $pool): void
    {
        $networkLong = ip2long($pool->network);
        $totalHosts = pow(2, 32 - $pool->prefix) - 2;

        $addresses = [];
        // Limit to 254 for safety in this version, though $totalHosts can be larger
        $limit = min($totalHosts, 254); 

        for ($i = 1; $i <= $limit; $i++) {
            $addresses[] = [
                'ip_pool_id' => $pool->id,
                'address' => long2ip($networkLong + $i),
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($addresses) >= 100) {
                IpAddress::insert($addresses);
                $addresses = [];
            }
        }

        if (count($addresses) > 0) {
            IpAddress::insert($addresses);
        }
    }
}
