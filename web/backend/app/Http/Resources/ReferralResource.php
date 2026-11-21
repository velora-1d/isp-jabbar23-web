<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReferralResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'referrer' => [
                'id' => $this->referrer?->id,
                'name' => $this->referrer?->name,
                'customer_id' => $this->referrer?->customer_id,
            ],
            'referred' => [
                'id' => $this->referred?->id,
                'name' => $this->referred?->name,
                'customer_id' => $this->referred?->customer_id,
            ],
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            'reward_amount' => (float) $this->reward_amount,
            'reward_paid' => $this->reward_paid,
            'qualified_at' => $this->qualified_at?->format('Y-m-d H:i:s'),
            'rewarded_at' => $this->rewarded_at?->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }

    protected function getStatusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'qualified' => 'Memenuhi Syarat',
            'rewarded' => 'Sudah Dibayar',
            'expired' => 'Kadaluarsa',
            default => $this->status,
        };
    }
}
