<?php

namespace App\Services\Olt;

use App\Models\Olt;
use InvalidArgumentException;

class OltServiceFactory
{
    public static function make(Olt $olt): OltServiceInterface
    {
        switch ($olt->server_profile) {
            case 'zte':
                return new ZteOltService($olt);
            case 'huawei':
                return new HuaweiOltService($olt);
            case 'simulation':
                // Keeping this for backward compatibility if DB has 'simulation' but throwing error if used
                throw new InvalidArgumentException("Simulation mode is disabled.");
            default:
                throw new InvalidArgumentException("Unknown OLT profile: {$olt->server_profile}");
        }
    }
}
