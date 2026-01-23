<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrderItem extends Model
{
    protected $fillable = ['work_order_id', 'inventory_item_id', 'quantity', 'unit', 'notes'];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }
}
