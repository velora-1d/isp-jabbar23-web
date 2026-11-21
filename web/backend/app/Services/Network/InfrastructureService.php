<?php

namespace App\Services\Network;

use App\Models\Olt;
use App\Models\Odp;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class InfrastructureService
{
    /**
     * OLT Methods
     */
    public function getAllOlts(): Collection
    {
        return Olt::latest()->get();
    }

    public function createOlt(array $data): Olt
    {
        try {
            return Olt::create($data);
        } catch (Exception $e) {
            Log::error("InfrastructureService (Olt Create): " . $e->getMessage());
            throw $e;
        }
    }

    public function updateOlt(Olt $olt, array $data): bool
    {
        return $olt->update($data);
    }

    public function deleteOlt(Olt $olt): bool
    {
        return $olt->delete();
    }

    /**
     * ODP Methods
     */
    public function getAllOdps(): Collection
    {
        return Odp::latest()->get();
    }

    public function createOdp(array $data): Odp
    {
        try {
            return Odp::create($data);
        } catch (Exception $e) {
            Log::error("InfrastructureService (Odp Create): " . $e->getMessage());
            throw $e;
        }
    }

    public function updateOdp(Odp $odp, array $data): bool
    {
        return $odp->update($data);
    }

    public function deleteOdp(Odp $odp): bool
    {
        return $odp->delete();
    }
}
