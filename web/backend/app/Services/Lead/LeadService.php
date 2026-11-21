<?php

namespace App\Services\Lead;

use App\Models\Lead;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LeadService
{
    /**
     * Create a new lead.
     */
    public function create(array $data): Lead
    {
        $data['lead_number'] = 'LEAD-' . date('Ym') . '-' . strtoupper(Str::random(4));
        return Lead::create($data);
    }

    /**
     * Update lead status.
     * If status is 'won', convert to customer.
     */
    public function updateStatus(Lead $lead, string $status): Lead
    {
        return DB::transaction(function () use ($lead, $status) {
            $lead->status = $status;

            if ($status === 'won' && !$lead->customer_id) {
                $customer = $this->convertToCustomer($lead);
                $lead->customer_id = $customer->id;
                $lead->converted_at = now();
            }

            $lead->save();
            return $lead;
        });
    }

    /**
     * Convert lead to customer record.
     */
    protected function convertToCustomer(Lead $lead): Customer
    {
        return Customer::create([
            'customer_number' => 'CUST-' . date('Ym') . '-' . strtoupper(Str::random(6)),
            'name' => $lead->name,
            'phone' => $lead->phone,
            'email' => $lead->email,
            'address' => $lead->address,
            'status' => 'prospect', // Set to prospect first before activation
            'package_id' => $lead->interested_package_id,
            'latitude' => $lead->latitude,
            'longitude' => $lead->longitude,
            'institution_id' => 1, // Default institution
        ]);
    }
}
