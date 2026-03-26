<?php

namespace App\Models;

use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $tenant_id
 * @property string $work_order_id
 * @property int $inventory_item_id
 * @property float $quantity
 * @property string $unit
 * @property string|null $notes
 */
class WorkOrderItem extends Model
{
    use HasUuids, HasTenant;

    protected $fillable = [
        'tenant_id',
        'work_order_id',
        'inventory_item_id',
        'quantity',
        'unit',
        'notes',
    ];

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }
}
