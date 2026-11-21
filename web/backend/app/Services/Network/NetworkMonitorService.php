<?php

namespace App\Services\Network;

use RouterOS\Config;
use RouterOS\Client;
use RouterOS\Query;
use App\Models\Router;
use Exception;
use Illuminate\Support\Facades\Log;

class NetworkMonitorService
{
    /**
     * Get real-time system resources from MikroTik.
     */
    public function getSystemResources(Router $router): array
    {
        try {
            $config = (new Config())
                ->set('host', $router->ip_address)
                ->set('user', $router->username)
                ->set('pass', $router->password)
                ->set('port', $router->port ?? 8728)
                ->set('timeout', 5);

            $client = new Client($config);
            
            $resource = $client->query(new Query("/system/resource/print"))->read();
            $identity = $client->query(new Query("/system/identity/print"))->read();
            
            if (empty($resource)) return [];

            return [
                'identity' => $identity[0]['name'] ?? $router->name,
                'uptime'   => $resource[0]['uptime'] ?? 'unknown',
                'cpu_load' => (int)($resource[0]['cpu-load'] ?? 0),
                'memory_free' => (int)($resource[0]['free-memory'] ?? 0),
                'memory_total' => (int)($resource[0]['total-memory'] ?? 0),
                'hdd_free' => (int)($resource[0]['free-hdd-space'] ?? 0),
                'version'  => $resource[0]['version'] ?? 'unknown',
                'board_name' => $resource[0]['board-name'] ?? 'unknown',
            ];
        } catch (Exception $e) {
            Log::error("NetworkMonitor Error (Resources): " . $e->getMessage());
        }

        return [];
    }

    /**
     * Get active connection counts (PPPoE & Hotspot).
     */
    public function getActiveCounts(Router $router): array
    {
        try {
            $config = (new Config())
                ->set('host', $router->ip_address)
                ->set('user', $router->username)
                ->set('pass', $router->password)
                ->set('port', $router->port ?? 8728);

            $client = new Client($config);
            
            $pppoeActive = $client->query(new Query("/ppp/active/print"))->read();
            $hotspotActive = $client->query(new Query("/ip/hotspot/active/print"))->read();
            
            return [
                'pppoe'   => count($pppoeActive),
                'hotspot' => count($hotspotActive),
                'total'   => count($pppoeActive) + count($hotspotActive),
            ];
        } catch (Exception $e) {
            Log::error("NetworkMonitor Error (Active Counts): " . $e->getMessage());
        }

        return ['pppoe' => 0, 'hotspot' => 0, 'total' => 0];
    }

    /**
     * Get interface traffic stats.
     */
    public function getInterfaceTraffic(Router $router, string $interface = 'ether1'): array
    {
        try {
            $config = (new Config())
                ->set('host', $router->ip_address)
                ->set('user', $router->username)
                ->set('pass', $router->password)
                ->set('port', $router->port ?? 8728);

            $client = new Client($config);
            
            $query = new Query("/interface/monitor-traffic");
            $query->equal("interface", $interface);
            $query->equal("once", "");
            
            $stats = $client->query($query)->read();
            
            if (empty($stats)) return ['rx' => 0, 'tx' => 0];

            return [
                'rx' => (int)($stats[0]['rx-bits-per-second'] ?? 0),
                'tx' => (int)($stats[0]['tx-bits-per-second'] ?? 0),
                'rx_human' => $this->formatBits($stats[0]['rx-bits-per-second'] ?? 0),
                'tx_human' => $this->formatBits($stats[0]['tx-bits-per-second'] ?? 0),
            ];
        } catch (Exception $e) {
            Log::error("NetworkMonitor Error (Traffic): " . $e->getMessage());
        }

        return ['rx' => 0, 'tx' => 0];
    }

    private function formatBits($bits): string
    {
        $units = ['bps', 'Kbps', 'Mbps', 'Gbps'];
        $bits = (int)$bits;
        $i = 0;
        while ($bits >= 1000 && $i < count($units) - 1) {
            $bits /= 1000;
            $i++;
        }
        return round($bits, 2) . ' ' . $units[$i];
    }
}
