<?php

namespace App\Jobs;

use App\Models\Router;
use App\Services\Network\PppoeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SyncRouterStats implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(PppoeService $pppoeService): void
    {
        $routers = Router::all();
        $totalActivePppoe = 0;
        
        Log::info("Starting Background Sync for " . $routers->count() . " routers.");

        foreach ($routers as $router) {
            try {
                /** @var Router $router */
                $sessions = $pppoeService->getActiveSessions($router);
                $count = count($sessions);
                $totalActivePppoe += $count;
                
                // Store per-router stats in cache
                Cache::put("router:stats:{$router->id}:pppoe", $count, now()->addMinutes(10));
                
                Log::info("Router {$router->name}: {$count} active sessions synced.");
            } catch (\Exception $e) {
                Log::error("Failed to sync router {$router->name}: " . $e->getMessage());
            }
        }

        // Store global aggregated stats
        Cache::put("network:stats:total_pppoe", $totalActivePppoe, now()->addMinutes(10));
        
        Log::info("Background Sync completed. Total active PPPoE: {$totalActivePppoe}");
    }
}
