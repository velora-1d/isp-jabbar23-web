<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\IpPool;
use App\Models\IpAddress;
use App\Models\Customer;
use Illuminate\Http\Request;

class IpamController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super-admin|admin|technician');
    }

    public function index()
    {
        $pools = IpPool::withCount('addresses')->get();
        
        $stats = [
            'total_pools' => $pools->count(),
            'total_ips' => IpAddress::count(),
            'allocated' => IpAddress::where('status', '=', 'allocated')->count(['*']),
            'available' => IpAddress::where('status', '=', 'available')->count(['*']),
        ];

        return view('network.ipam.index', compact('pools', 'stats'));
    }

    public function createPool()
    {
        return view('network.ipam.create-pool');
    }

    public function storePool(Request $request)
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

        $pool = IpPool::create($validated);

        // Generate IP addresses for the pool
        $this->generateIpAddresses($pool);

        return redirect()->route('network.ipam.index')
            ->with('success', 'IP Pool berhasil dibuat dengan ' . $pool->addresses()->count() . ' alamat IP!');
    }

    private function generateIpAddresses(IpPool $pool): void
    {
        $networkLong = ip2long($pool->network);
        $totalHosts = pow(2, 32 - $pool->prefix) - 2; // Exclude network and broadcast

        $addresses = [];
        for ($i = 1; $i <= $totalHosts && $i <= 254; $i++) { // Limit to 254 for /24 or smaller
            $addresses[] = [
                'ip_pool_id' => $pool->id,
                'address' => long2ip($networkLong + $i),
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        IpAddress::insert($addresses);
    }

    public function destroyPool(IpPool $pool)
    {
        $pool->delete();
        return redirect()->route('network.ipam.index')
            ->with('success', 'IP Pool berhasil dihapus!');
    }

    public function allocate(Request $request)
    {
        $validated = $request->validate([
            'ip_address_id' => 'required|exists:ip_addresses,id',
            'customer_id' => 'required|exists:customers,id',
        ]);

        $address = IpAddress::findOrFail($validated['ip_address_id']);
        $address->update([
            'status' => 'allocated',
            'customer_id' => $validated['customer_id'],
        ]);

        return back()->with('success', 'IP berhasil dialokasikan!');
    }

    public function release(IpAddress $address)
    {
        $address->update([
            'status' => 'available',
            'customer_id' => null,
        ]);

        return back()->with('success', 'IP berhasil dilepas!');
    }
}
