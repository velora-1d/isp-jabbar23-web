<?php

namespace App\Services\Olt;

use App\Models\Olt;
use Exception;

class ZteOltService implements OltServiceInterface
{
    protected $olt;

    public function __construct(Olt $olt)
    {
        $this->olt = $olt;
    }

    public function getOnuSignal(string $onuIndex): array
    {
        // TODO: Implement Telnet connection to ZTE OLT
        // 1. Connect to $this->olt->ip_address via Telnet (port 23)
        // 2. Login with $this->olt->username / $this->olt->password
        // 3. Exec "show gpon onu detail-info $onuIndex"
        // 4. Parse output for Rx/Tx Power

        throw new Exception("Connection to ZTE OLT ({$this->olt->name}) failed or not implemented yet. Please verify device reachability.");
    }

    public function getOnuStatus(string $onuIndex): array
    {
        throw new Exception("Connection to ZTE OLT ({$this->olt->name}) failed or not implemented yet.");
    }

    public function rebootOnu(string $onuIndex): bool
    {
        throw new Exception("Reboot command failed: Cannot connect to OLT.");
    }
}
