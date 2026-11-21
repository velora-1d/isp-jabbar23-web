<?php

namespace App\Services\Olt;

use App\Models\Olt;
use Exception;

class HuaweiOltService implements OltServiceInterface
{
    protected $olt;

    public function __construct(Olt $olt)
    {
        $this->olt = $olt;
    }

    public function getOnuSignal(string $onuIndex): array
    {
        // TODO: Implement Telnet connection to Huawei OLT
        // 1. Connect to $this->olt->ip_address
        // 2. Login
        // 3. Exec "display ont info ..."
        
        throw new Exception("Connection to Huawei OLT ({$this->olt->name}) failed or not implemented yet.");
    }

    public function getOnuStatus(string $onuIndex): array
    {
        throw new Exception("Connection to Huawei OLT ({$this->olt->name}) failed or not implemented yet.");
    }

    public function rebootOnu(string $onuIndex): bool
    {
        throw new Exception("Reboot command failed: Cannot connect to OLT.");
    }
}
