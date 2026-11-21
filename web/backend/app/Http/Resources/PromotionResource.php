<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromotionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'type' => $this->type,
            'type_label' => $this->type_label,
            'type_color' => $this->type_color,
            'value' => (float) $this->value,
            'discount_label' => $this->discount_label,
            'min_purchase' => (float) $this->min_purchase,
            'max_discount' => (float) $this->max_discount,
            'usage_limit' => $this->usage_limit,
            'usage_count' => $this->usage_count,
            'per_customer_limit' => $this->per_customer_limit,
            'start_date' => $this->start_date->format('Y-m-d'),
            'end_date' => $this->end_date->format('Y-m-d'),
            'applicable_packages' => $this->applicable_packages,
            'is_active' => $this->is_active,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'status_color' => $this->status_color,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
