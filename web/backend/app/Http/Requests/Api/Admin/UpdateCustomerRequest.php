<?php

namespace App\Http\Requests\Api\Admin;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $validStatuses = implode(',', array_keys(Customer::STATUSES));
        $customer = $this->route('customer');

        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'ktp_number' => 'required|string|max:20',
            'address' => 'required|string',
            'rt_rw' => 'required|string|max:20',
            'kelurahan' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'kabupaten' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'package_id' => 'required|exists:packages,id',
            'installation_date' => 'required|date',
            'billing_date' => 'required|date',
            'olt_id' => 'required|exists:olts,id',
            'odp_port' => 'required|string|max:50',
            'onu_index' => 'required|string|max:100',
            'router_id' => 'required|exists:routers,id',
            'pppoe_username' => 'required|string|max:100|unique:customers,pppoe_username,' . ($customer->id ?? 'NULL'),
            'pppoe_password' => 'required|string|max:100',
            'mikrotik_ip' => 'required|ipv4',
            'partner_id' => 'required|exists:partners,id',
            'assigned_to' => 'required|exists:users,id',
            'team_size' => 'required|integer|min:1',
            'status' => "required|in:{$validStatuses}",
            'notes' => 'required|string',
        ];
    }
}
