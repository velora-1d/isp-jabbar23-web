<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Referral;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ReferralService
{
    public function list(array $filters = [])
    {
        $query = Referral::with(['referrer', 'referred']);

        if (isset($filters['search'])) {
            $query->where('code', 'like', '%' . $filters['search'] . '%')
                  ->orWhereHas('referrer', function($q) use ($filters) {
                      $q->where('name', 'like', '%' . $filters['search'] . '%');
                  });
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate($filters['per_page'] ?? 15);
    }

    public function generateUniqueCode(): string
    {
        do {
            $code = 'JAB-' . strtoupper(Str::random(6));
        } while (Referral::where('code', $code)->exists());

        return $code;
    }

    public function recordReferral(string $code, Customer $newCustomer): ?Referral
    {
        $referral = Referral::where('code', $code)->whereNull('referred_id')->first();
        
        if (!$referral) {
            return null;
        }

        $referral->update([
            'referred_id' => $newCustomer->id,
            'status' => 'pending'
        ]);

        return $referral;
    }

    public function qualifyReferral(Customer $customer): void
    {
        $referral = Referral::where('referred_id', $customer->id)
            ->where('status', 'pending')
            ->first();

        if ($referral && $customer->status === 'active') {
            $referral->update([
                'status' => 'qualified',
                'qualified_at' => now(),
                'reward_amount' => 50000 // Rp 50.000 (Default Reward)
            ]);
        }
    }

    public function payout(Referral $referral): Referral
    {
        if ($referral->status !== 'qualified' || $referral->reward_paid) {
            throw ValidationException::withMessages(['referral' => 'Referral ini tidak dapat dicairkan.']);
        }

        DB::transaction(function () use ($referral) {
            // Integration with billing can be added here
            $referral->update([
                'status' => 'rewarded',
                'reward_paid' => true,
                'rewarded_at' => now(),
            ]);
        });

        return $referral;
    }
}
