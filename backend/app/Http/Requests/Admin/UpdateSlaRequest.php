<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSlaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['sometimes', 'required', 'in:low,medium,high,critical'],
            'first_response_hours' => ['sometimes', 'required', 'integer', 'min:1'],
            'resolution_hours' => ['sometimes', 'required', 'integer', 'min:1'],
            'is_active' => ['boolean'],
        ];
    }
}
