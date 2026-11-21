<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\Olt;
use App\Models\Router;
use App\Models\RouterHealthLog;
use Illuminate\Http\Request;

class NetworkMonitoringController extends Controller
{
    public function index()
    {
        // Fetch OLTs to monitor
        $olts = Olt::all();

        // Fetch Routers with latest health log
        $routers = Router::with(['healthLogs' => function ($query) {
            $query->latest('logged_at')->limit(1);
        }])
            ->withCount('customers')
            ->get()
            ->map(function ($router) {
                $router->latest_log = $router->healthLogs->first();
                return $router;
            });

        return view('network.monitoring.index', compact('olts', 'routers'));
    }

    public function getRouterStats(Request $request, $id)
    {
        $range = $request->get('range', '24h');
        $query = RouterHealthLog::where('router_id', $id)->orderBy('logged_at', 'asc');

        if ($range === '1h') {
            $query->where('logged_at', '>=', now()->subHour());
        } elseif ($range === '24h') {
            $query->where('logged_at', '>=', now()->subDay());
        } elseif ($range === '7d') {
            $query->where('logged_at', '>=', now()->subDays(7));
        }

        // Limit data points for performance if needed, but for now raw is fine
        $logs = $query->get(['logged_at', 'cpu_load', 'memory_usage', 'active_hotspot', 'active_pppoe']);

        return response()->json([
            'labels' => $logs->pluck('logged_at')->map(fn($d) => $d->format('H:i')),
            'cpu' => $logs->pluck('cpu_load'),
            'memory' => $logs->pluck('memory_usage'),
            'hotspot' => $logs->pluck('active_hotspot'),
            'pppoe' => $logs->pluck('active_pppoe'),
        ]);
    }

    public function ping(Request $request)
    {
        $request->validate([
            'ip' => 'required|ip'
        ]);

        $ip = $request->ip;

        // Determine OS to set ping command
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $cmd = "ping -n 1 -w 1000 " . escapeshellarg($ip);
        } else {
            $cmd = "ping -c 1 -W 1 " . escapeshellarg($ip);
        }

        $output = [];
        $status = -1;
        exec($cmd, $output, $status);

        // Parse latency (Basic generic parsing)
        $latency = 'N/A';
        foreach ($output as $line) {
            if (preg_match('/time[=<](\d+)(?:ms|ms)/i', $line, $matches)) {
                $latency = $matches[1] . ' ms';
                break;
            }
        }

        return response()->json([
            'online' => $status === 0,
            'latency' => $latency,
            'raw' => implode("\n", $output) // Debug info
        ]);
    }
}
