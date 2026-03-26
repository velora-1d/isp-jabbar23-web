<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Odp;
use App\Models\Olt;
use App\Models\Router;
use Illuminate\Http\JsonResponse;

class TopologyController extends Controller
{
    public function data(): JsonResponse
    {
        $nodes = [];
        $edges = [];

        // Routers
        $routers = Router::all();
        foreach ($routers as $router) {
            $nodes[] = [
                'id' => 'router_' . $router->id,
                'label' => $router->name,
                'title' => "{$router->ip_address}:{$router->port}",
                'group' => 'router',
                'shape' => 'box',
                'color' => $router->status === 'online' ? '#10B981' : '#EF4444',
                'data' => $router
            ];
        }

        // OLTs
        $olts = Olt::all();
        foreach ($olts as $olt) {
            $nodes[] = [
                'id' => 'olt_' . $olt->id,
                'label' => $olt->name,
                'title' => "{$olt->ip_address}\n{$olt->pon_ports} PON ports",
                'group' => 'olt',
                'shape' => 'database',
                'color' => $olt->status === 'active' ? '#3B82F6' : '#6B7280',
                'data' => $olt
            ];

            if ($routers->count() > 0) {
                $edges[] = [
                    'from' => 'router_' . $routers->first()->id,
                    'to' => 'olt_' . $olt->id,
                    'color' => '#4B5563',
                    'width' => 2
                ];
            }
        }

        // ODPs
        $odps = Odp::all();
        foreach ($odps as $odp) {
            $nodes[] = [
                'id' => 'odp_' . $odp->id,
                'label' => $odp->name,
                'title' => "{$odp->ports} ports\n{$odp->used_ports} used",
                'group' => 'odp',
                'shape' => 'dot',
                'size' => 15,
                'color' => $odp->status === 'active' ? '#8B5CF6' : '#6B7280',
                'data' => $odp
            ];

            if ($olts->count() > 0) {
                $edges[] = [
                    'from' => 'olt_' . $olts->first()->id,
                    'to' => 'odp_' . $odp->id,
                    'color' => '#6B7280',
                    'dashes' => true
                ];
            }
        }

        return response()->json([
            'nodes' => $nodes,
            'edges' => $edges,
        ]);
    }
}
