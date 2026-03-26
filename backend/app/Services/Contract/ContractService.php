<?php

namespace App\Services\Contract;

use App\Models\Contract;
use App\Models\Customer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ContractService
{
    /**
     * Create a draft contract for a customer.
     */
    public function createDraft(Customer $customer, array $data): Contract
    {
        $data['customer_id'] = $customer->id;
        $data['contract_number'] = 'KONTRAK-' . date('Ym') . '-' . strtoupper(Str::random(6));
        $data['status'] = 'draft';
        $data['start_date'] = $data['start_date'] ?? now();
        
        return Contract::create($data);
    }

    /**
     * Sign a contract digitally.
     */
    public function signDigitally(Contract $contract, string $signatureDataUri, string $ipAddress): Contract
    {
        // Decode base64 signature
        $image = str_replace('data:image/png;base64,', '', $signatureDataUri);
        $image = str_replace(' ', '+', $image);
        $imageName = 'signatures/contract_' . $contract->id . '_' . time() . '.png';
        
        Storage::disk('public')->put($imageName, base64_decode($image));

        $contract->update([
            'digital_signature_path' => $imageName,
            'signed_at' => now(),
            'client_ip' => $ipAddress,
            'status' => 'active'
        ]);

        return $contract;
    }
}
