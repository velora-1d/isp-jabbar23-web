<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Table;

/**
 * @property int $id
 * @property int $inventory_item_id
 * @property string $serial_number
 * @property string $status
 * @property int|null $customer_id
 * @property int|null $location_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
#[Table('inventory_serials')]
class InventorySerial extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_item_id',
        'serial_number',
        'status',
        'customer_id',
        'location_id'
    ];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
