<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\Router;
use App\Traits\HasFilters;
use Illuminate\Http\Request;

class RouterController extends Controller
{
    use HasFilters;

    public function __construct()
    {
        $this->middleware('role:super-admin|admin|technician|noc');
    }

    public function index(Request $request)
    {
        $query = Router::query();

        // Apply global filters
        $this->applyGlobalFilters($query, $request, [
            'dateColumn' => 'created_at',
            'searchColumns' => ['name', 'ip_address', 'identity', 'notes']
        ]);

        // Apply status filter
        $this->applyStatusFilter($query, $request);

        // Apply type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $routers = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => Router::count(),
            'online' => Router::where('status', 'online')->count(),
            'offline' => Router::where('status', 'offline')->count(),
            'mikrotik' => Router::where('type', 'mikrotik')->count(),
        ];

        // Filter options
        $statuses = [
            'online' => 'Online',
            'offline' => 'Offline',
            'unknown' => 'Unknown',
        ];

        $types = [
            'mikrotik' => 'Mikrotik',
            'cisco' => 'Cisco',
            'ubiquiti' => 'Ubiquiti',
            'other' => 'Other',
        ];

        return view('network.routers.index', compact('routers', 'stats', 'statuses', 'types'));
    }

    public function create()
    {
        return view('network.routers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ip_address' => 'required|ip',
            'port' => 'required|integer|min:1|max:65535',
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|max:255',
            'type' => 'required|in:mikrotik,cisco,ubiquiti,other',
            'notes' => 'nullable|string',
        ]);

        Router::create($validated);

        return redirect()->route('network.routers.index')
            ->with('success', 'Router berhasil ditambahkan!');
    }

    public function edit(Router $router)
    {
        return view('network.routers.edit', compact('router'));
    }

    public function update(Request $request, Router $router)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ip_address' => 'required|ip',
            'port' => 'required|integer|min:1|max:65535',
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|max:255',
            'type' => 'required|in:mikrotik,cisco,ubiquiti,other',
            'notes' => 'nullable|string',
        ]);

        // Only update password if provided
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $router->update($validated);

        return redirect()->route('network.routers.index')
            ->with('success', 'Router berhasil diperbarui!');
    }

    public function destroy(Router $router)
    {
        $router->delete();

        return redirect()->route('network.routers.index')
            ->with('success', 'Router berhasil dihapus!');
    }

    public function sync(Router $router, \App\Services\MikrotikService $mikrotik)
    {
        try {
            $mikrotik->connect($router);

            // Fetch Identity
            $client = new \RouterOS\Client([
                'host' => $router->ip_address,
                'user' => $router->username,
                'pass' => $router->password,
                'port' => (int) $router->port,
            ]);

            $identity = $client->query('/system/identity/print')->read();
            $resource = $client->query('/system/resource/print')->read();

            $router->update([
                'status' => 'online',
                'last_sync_at' => now(),
                'identity' => $identity[0]['name'] ?? $router->name,
                'version' => $resource[0]['version'] ?? 'Unknown',
                'model' => $resource[0]['board-name'] ?? 'Unknown',
            ]);

            return back()->with('success', 'Router berhasil disinkronkan!');
        } catch (\Exception $e) {
            $router->update(['status' => 'offline']);
            return back()->with('error', 'Gagal menyinkronkan router: ' . $e->getMessage());
        }
    }

    public function testConnection(Router $router, \App\Services\MikrotikService $mikrotik)
    {
        try {
            $response = $mikrotik->testConnection($router);
            $router->update(['status' => 'online']);

            return response()->json([
                'success' => true,
                'message' => 'Router Connected! Identity: ' . ($response[0]['name'] ?? 'Unknown')
            ]);
        } catch (\Exception $e) {
            $router->update(['status' => 'offline']);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function importCustomers(Router $router, \App\Services\MikrotikService $mikrotik)
    {
        try {
            $mikrotik->connect($router);

            // 1. Fetch Data
            $profiles = $mikrotik->getProfiles();
            $secrets = $mikrotik->getSecrets();

            $importedCount = 0;
            $skippedCount = 0;

            // 2. Sync Profiles to Packages
            // Map profile name to package ID
            $packageMap = [];
            foreach ($profiles as $profile) {
                $name = $profile['name'];
                $rateLimit = $profile['rate-limit'] ?? null;
                $price = 0; // Default price, admin must update later

                // Find or create package
                $package = \App\Models\Package::firstOrCreate(
                    ['name' => $name],
                    [
                        'price' => $price,
                        'speed' => $rateLimit ?? 'Unknown',
                        'description' => 'Imported from Mikrotik Profile: ' . $name
                    ]
                );
                $packageMap[$name] = $package->id;
            }

            // 3. Process Secrets
            foreach ($secrets as $secret) {
                $username = $secret['name'];
                $password = $secret['password'] ?? '';
                $profileName = $secret['profile'] ?? 'default';
                $isDisabled = ($secret['disabled'] ?? 'false') === 'true';
                $localAddress = $secret['remote-address'] ?? null; // Usually implementation uses remote-address for customer IP

                // Check if customer exists
                if (\App\Models\Customer::where('pppoe_username', $username)->exists()) {
                    $skippedCount++;
                    continue;
                }

                $packageId = $packageMap[$profileName] ?? \App\Models\Package::first()->id;

                // Create Customer
                \App\Models\Customer::create([
                    'customer_id' => 'CUST-' . strtoupper(uniqid()),
                    'name' => $username, // Use username as name initially
                    'address' => 'Imported from Mikrotik',
                    'router_id' => $router->id,
                    'package_id' => $packageId,
                    'pppoe_username' => $username,
                    'pppoe_password' => $password,
                    'mikrotik_ip' => filter_var($localAddress, FILTER_VALIDATE_IP) ? $localAddress : null,
                    'status' => $isDisabled ? 'suspended' : 'active',
                    'installation_date' => now(),
                    'billing_date' => now(),
                ]);

                $importedCount++;
            }

            return back()->with('success', "Import Successful! Imported: $importedCount, Skipped: $skippedCount");

        } catch (\Exception $e) {
            return back()->with('error', 'Import Failed: ' . $e->getMessage());
        }
    }
}
