<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkOrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'work_order_id'     => $this->work_order_id,
            'inventory_item_id' => $this->inventory_item_id,
            'quantity'          => (float) $this->quantity,
            'unit'              => $this->unit,
            'notes'             => $this->notes,
            
            'inventory_item' => $this->whenLoaded('inventoryItem'),
        ];
    }
}
