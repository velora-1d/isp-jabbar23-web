<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Router;
use App\Services\Network\NetworkMonitorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NetworkController extends Controller
{
    protected $monitorService;

    public function __construct(NetworkMonitorService $monitorService)
    {
        $this->monitorService = $monitorService;
    }

    /**
     * Get list of routers.
     */
    public function routers(): JsonResponse
    {
        $routers = Router::where('status', 'online')->get(['id', 'name', 'ip_address', 'type']);
        return response()->json($routers);
    }

    /**
     * Get monitor data for a specific router.
     */
    public function monitor(Router $router): JsonResponse
    {
        // For production, we might want to cache some data for a few seconds
        // to avoid hitting MikroTik API too frequently on concurrent requests.
        
        $resources = $this->monitorService->getSystemResources($router);
        $activeUsers = $this->monitorService->getActiveCounts($router);
        $traffic = $this->monitorService->getInterfaceTraffic($router, 'ether1'); // Default interface

        return response()->json([
            'router' => [
                'id' => $router->id,
                'name' => $router->name,
                'status' => $router->status,
            ],
            'resources' => $resources,
            'active_users' => $activeUsers,
            'traffic' => $traffic,
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
