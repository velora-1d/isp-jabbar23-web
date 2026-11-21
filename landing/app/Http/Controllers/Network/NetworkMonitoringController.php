<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\Olt;
use Illuminate\Http\Request;

class NetworkMonitoringController extends Controller
{
    public function index()
    {
        // Fetch OLTs to monitor
        $olts = Olt::all();
        
        // In the future we can add Routers, Switches, etc.
        
        return view('network.monitoring.index', compact('olts'));
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
