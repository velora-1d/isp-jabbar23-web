<?php

namespace App\Services\Olt;

class SimulationOltService implements OltServiceInterface
{
    public function getOnuSignal(string $onuIndex): array
    {
        // Simulate a realistic GPON signal
        // Good signal: -15 to -25 dBm
        // Bad signal: < -27 dBm
        
        $rx = rand(-2800, -1500) / 100; // -15.00 to -28.00
        $tx = rand(150, 300) / 100; // 1.50 to 3.00

        $status = ($rx < -27) ? 'critical' : 'normal';

        return [
            'rx_power' => $rx,
            'tx_power' => $tx,
            'status' => $status,
            'message' => 'Simulated Signal (OK)'
        ];
    }

    public function getOnuStatus(string $onuIndex): array
    {
        $states = ['working', 'working', 'working', 'dying_gasp', 'offline'];
        $state = $states[array_rand($states)];

        return [
            'status' => $state,
            'description' => $state === 'working' ? 'Online' : 'Offline/Power Fail',
            'uptime' => rand(1, 30) . ' days',
            'last_down' => now()->subHours(rand(1, 100))->toDateTimeString()
        ];
    }

    public function rebootOnu(string $onuIndex): bool
    {
        // Simulate reboot delay
        sleep(1);
        return true;
    }
}
