<?php

namespace App\Services;

use App\Models\WorkOrder;
use App\Models\WorkOrderItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WorkOrderService
{
    /**
     * Create a new work order.
     */
    public function createWorkOrder(array $data): WorkOrder
    {
        return DB::transaction(function () use ($data) {
            $workOrder = WorkOrder::create([
                'ticket_number' => $this->generateTicketNumber(),
                'customer_id'   => $data['customer_id'] ?? null,
                'type'          => $data['type'],
                'priority'      => $data['priority'] ?? 'medium',
                'description'   => $data['description'] ?? null,
                'status'        => 'pending',
                'odp_id'        => $data['odp_id'] ?? null,
            ]);

            if (isset($data['items'])) {
                $this->syncMaterials($workOrder, $data['items']);
            }

            return $workOrder;
        });
    }

    /**
     * Update work order status and notes.
     */
    public function updateStatus(WorkOrder $workOrder, string $status, ?string $notes = null): WorkOrder
    {
        $updateData = ['status' => $status];
        
        if ($notes) {
            $workOrder->technician_notes = $notes;
        }

        if ($status === 'completed') {
            $updateData['completed_date'] = now();
        }

        $workOrder->update($updateData);

        return $workOrder->fresh();
    }

    /**
     * Sync material usage for a work order.
     */
    public function syncMaterials(WorkOrder $workOrder, array $items): void
    {
        $workOrder->items()->delete();

        foreach ($items as $item) {
            WorkOrderItem::create([
                'work_order_id'     => $workOrder->id,
                'inventory_item_id' => $item['id'],
                'quantity'          => $item['quantity'],
                'unit'              => $item['unit'] ?? 'pcs',
                'notes'             => $item['notes'] ?? null,
            ]);
        }
    }

    /**
     * Generate unique ticket number (e.g. WO-202403-0001).
     */
    private function generateTicketNumber(): string
    {
        $prefix = 'WO-' . now()->format('Ym') . '-';
        $last = WorkOrder::where('ticket_number', 'like', $prefix . '%')
            ->orderBy('ticket_number', 'desc')
            ->first();

        $number = 1;
        if ($last) {
            $number = (int) substr($last->ticket_number, -4) + 1;
        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
