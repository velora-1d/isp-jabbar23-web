<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreSlaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'in:low,medium,high,critical'],
            'first_response_hours' => ['required', 'integer', 'min:1'],
            'resolution_hours' => ['required', 'integer', 'min:1'],
            'is_active' => ['boolean'],
        ];
    }
}
