<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\Router;
use Illuminate\Http\Request;

class RouterController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super-admin|admin|technician');
    }

    public function index()
    {
        $routers = Router::latest()->paginate(15);
        
        $stats = [
            'total' => Router::count(),
            'online' => Router::where('status', '=', 'online')->count(['*']),
            'offline' => Router::where('status', '=', 'offline')->count(['*']),
            'mikrotik' => Router::where('type', '=', 'mikrotik')->count(['*']),
        ];

        return view('network.routers.index', compact('routers', 'stats'));
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

    public function sync(Router $router)
    {
        // Placeholder for Mikrotik API sync
        // In production, this would connect via RouterOS API
        try {
            // Simulate sync - real implementation would use RouterOS API
            $router->update([
                'status' => 'online',
                'last_sync_at' => now(),
                'identity' => $router->name,
                'version' => '7.x',
            ]);

            return back()->with('success', 'Router berhasil disinkronkan!');
        } catch (\Exception $e) {
            $router->update(['status' => 'offline']);
            return back()->with('error', 'Gagal menyinkronkan router: ' . $e->getMessage());
        }
    }

    public function testConnection(Router $router)
    {
        // Placeholder for connection test
        // Real implementation would ping or connect via API
        try {
            // Simulate test
            $reachable = @fsockopen($router->ip_address, $router->port, $errno, $errstr, 3);
            
            if ($reachable) {
                fclose($reachable);
                $router->update(['status' => 'online']);
                return response()->json(['success' => true, 'message' => 'Router dapat dijangkau!']);
            }

            $router->update(['status' => 'offline']);
            return response()->json(['success' => false, 'message' => 'Router tidak dapat dijangkau'], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
