<?php

namespace App\Services\Olt;

interface OltServiceInterface
{
    /**
     * Get real-time optical signal strength (Rx/Tx) of an ONU.
     * 
     * @param string $onuIndex The ONU identifier (e.g. gpon-onu_1/1/1:1)
     * @return array {rx_power: float, tx_power: float, status: string}
     */
    public function getOnuSignal(string $onuIndex): array;

    /**
     * Get detailed status of an ONU.
     * 
     * @param string $onuIndex
     * @return array
     */
    public function getOnuStatus(string $onuIndex): array;

    /**
     * Reboot an ONU remotely.
     * 
     * @param string $onuIndex
     * @return bool
     */
    public function rebootOnu(string $onuIndex): bool;
}
