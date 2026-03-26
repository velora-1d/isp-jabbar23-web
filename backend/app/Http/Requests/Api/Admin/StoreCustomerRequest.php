<?php

namespace App\Http\Requests\Api\Admin;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Middleware handles this
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $validStatuses = implode(',', array_keys(Customer::STATUSES));

        return [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'rt_rw' => 'nullable|string|max:20',
            'kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kabupaten' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'package_id' => 'required|exists:packages,id',
            'assigned_to' => 'nullable|exists:users,id',
            'team_size' => 'nullable|integer|min:1|max:10',
            'status' => "required|in:{$validStatuses}",
            'installation_date' => 'nullable|date',
            'billing_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'router_id' => 'nullable|exists:routers,id',
            'pppoe_username' => 'nullable|string|max:100|unique:customers,pppoe_username',
            'pppoe_password' => 'nullable|string|max:100',
            'mikrotik_ip' => 'nullable|ipv4',
            'olt_id' => 'nullable|exists:olts,id',
            'onu_index' => 'nullable|string|max:100',
        ];
    }
}
