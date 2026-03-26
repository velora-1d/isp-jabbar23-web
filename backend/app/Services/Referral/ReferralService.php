<?php

namespace App\Services\Referral;

use App\Models\Customer;
use App\Models\Referral;
use Illuminate\Support\Str;

class ReferralService
{
    /**
     * Generate a unique referral code for a customer.
     */
    public function generateUniqueCode(): string
    {
        do {
            $code = 'JAB-' . strtoupper(Str::random(6));
        } while (Referral::where('code', $code)->exists());

        return $code;
    }

    /**
     * Track a new referral when a customer is registered.
     */
    public function recordReferral(string $code, Customer $newCustomer): ?Referral
    {
        $referral = Referral::where('code', $code)->whereNull('referred_id')->first();
        
        if (!$referral) {
            // Jika kode baru dan belum ada di tabel referrals (misal kode milik customer langsung)
            // Cari customer yang memiliki kode ini (jika kita simpan kode rujukan di tabel customers)
            // Untuk desain ini, kita asumsikan kode rujukan terdaftar di tabel referrals sebagai draf/template
            return null;
        }

        $referral->update([
            'referred_id' => $newCustomer->id,
            'status' => 'pending'
        ]);

        return $referral;
    }

    /**
     * Qualify a referral when the referred customer becomes active.
     */
    public function qualifyReferral(Customer $customer): void
    {
        $referral = Referral::where('referred_id', $customer->id)
            ->where('status', 'pending')
            ->first();

        if ($referral && $customer->status === 'active') {
            $referral->update([
                'status' => 'qualified',
                'qualified_at' => now(),
                'reward_amount' => 50000 // Contoh: Rp 50.000 per rujukan sukses
            ]);
        }
    }
}
