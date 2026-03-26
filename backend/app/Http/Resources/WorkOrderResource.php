<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\WorkOrderItemResource;

class WorkOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'ticket_number'    => $this->ticket_number,
            'type'             => $this->type,
            'status'           => $this->status,
            'priority'         => $this->priority,
            'scheduled_date'   => $this->scheduled_date,
            'completed_date'   => $this->completed_date,
            'description'      => $this->description,
            'technician_notes' => $this->technician_notes,
            'photos'           => $this->photos,
            
            // Relationships
            'customer'   => $this->whenLoaded('customer'),
            'technician' => $this->whenLoaded('technician'),
            'odp'        => $this->whenLoaded('odp'),
            'items'      => WorkOrderItemResource::collection($this->whenLoaded('items')),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
