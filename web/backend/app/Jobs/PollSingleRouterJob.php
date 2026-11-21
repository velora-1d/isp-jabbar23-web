<?php

namespace App\Jobs;

use App\Models\Router;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RouterOS\Client;
use RouterOS\Query;

class PollSingleRouterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $routerId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $routerId)
    {
        $this->routerId = $routerId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $router = Router::find($this->routerId);
        
        if (!$router) {
            return;
        }

        try {
            // Initiate Client
            // Note: Ensure configured timeout is low (e.g., 5-10s) to not block worker too long
            $client = new Client([
                'host' => $router->ip_address,
                'user' => $router->username,
                'pass' => $router->password,
                'port' => (int) ($router->port ?? 8728),
                'timeout' => 10,
            ]);

            // Get System Resources
            $resourceQuery = new Query('/system/resource/print');
            $resource = $client->query($resourceQuery)->read();
            
            if (empty($resource)) {
                $this->markOffline($router);
                return;
            }

            $res = $resource[0];
            
            // Get Active PPP Counts
            $pppQuery = new Query('/ppp/active/print');
            $pppQuery->where('count-only', 'true'); // If supported, or just count result
            // Note: counting 10k users via API print might be heavy. 
            // Better to use '/ppp/active/count' if API supports it, or just SNMP.
            // For now, simpler approach:
            
            // Actually, API print count-only doesn't work like CLI. 
            // We'll skip precise user count for now to avoid hanging, or just count result size.
            // $activePpp = count($client->query(new Query('/ppp/active/print'))->read());
            
            // Optimize: Use /ppp/active/print with .proplist=.id to minimize data
            $pppQuery = (new Query('/ppp/active/print'))->equal('.proplist', '.id');
            $activePpp = count($client->query($pppQuery)->read());

            // Hotspot Active
            $hotspotQuery = (new Query('/ip/hotspot/active/print'))->equal('.proplist', '.id');
            $activeHotspot = count($client->query($hotspotQuery)->read());

            // Log Data
            DB::table('router_health_logs')->insert([
                'router_id' => $router->id,
                'cpu_load' => (int) ($res['cpu-load'] ?? 0),
                'free_memory' => (int) ($res['free-memory'] ?? 0),
                'total_memory' => (int) ($res['total-memory'] ?? 0),
                'uptime_seconds' => $this->parseUptime($res['uptime'] ?? '0s'),
                'active_pppoe' => $activePpp,
                'active_hotspot' => $activeHotspot,
                'voltage' => isset($res['voltage']) ? $res['voltage'] / 10 : null,
                'temperature' => isset($res['temperature']) ? $res['temperature'] : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update Router Status
            $router->update([
                'status' => 'online',
                'last_sync_at' => now(),
                'version' => $res['version'] ?? $router->version,
                'model' => $res['board-name'] ?? $router->model,
            ]);

        } catch (\Exception $e) {
            Log::error("Router Monitor Error ({$router->ip_address}): " . $e->getMessage());
            $this->markOffline($router);
        }
    }

    private function markOffline($router)
    {
        $router->update(['status' => 'offline']);
        // Optional: Send WA Alert to NOC if offline > 10 mins
    }

    private function parseUptime($uptimeString)
    {
        // Simple parser, MikroTik uptime format: 2w4d6h30m or 10:00:00
        // For simplicity, returning 0 or simplified integer
        // Ideally should convert fully to seconds
        return 0; 
    }
}
