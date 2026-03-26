<?php

namespace App\Services\WorkOrder;

use App\Models\WorkOrder;
use App\Models\InventoryItem;
use App\Services\Inventory\InventoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WorkOrderService
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {}

    /**
     * Create a new work order.
     */
    public function create(array $data): WorkOrder
    {
        return DB::transaction(function () use ($data) {
            $data['ticket_number'] = $data['ticket_number'] ?? 'WO-' . date('Ym') . '-' . strtoupper(Str::random(4));
            
            $workOrder = WorkOrder::create($data);

            if (isset($data['items'])) {
                foreach ($data['items'] as $item) {
                    $workOrder->items()->create($item);
                }
            }

            return $workOrder;
        });
    }

    /**
     * Update the status of a work order.
     * If completed, deduct inventory stock.
     */
    public function updateStatus(WorkOrder $workOrder, string $status, ?string $notes = null): WorkOrder
    {
        return DB::transaction(function () use ($workOrder, $status, $notes) {
            $workOrder->status = $status;
            
            if ($notes) {
                $workOrder->technician_notes = $notes;
            }

            if ($status === 'completed') {
                $workOrder->completed_date = now();
                $this->deductInventoryItems($workOrder);
            }

            $workOrder->save();
            return $workOrder;
        });
    }

    /**
     * Deduct inventory items used in the work order.
     */
    protected function deductInventoryItems(WorkOrder $workOrder): void
    {
        foreach ($workOrder->items as $item) {
            $this->inventoryService->recordTransaction([
                'inventory_item_id' => $item->inventory_item_id,
                'location_id' => 1, // Default Utama - Adjust as needed
                'type' => 'out',
                'quantity' => $item->quantity,
                'notes' => "Used in Work Order: {$workOrder->ticket_number}",
                'reference_no' => $workOrder->ticket_number,
            ]);
        }
    }
}
