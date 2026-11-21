<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Table;

/**
 * @property int $id
 * @property int $inventory_item_id
 * @property int $location_id
 * @property float $quantity
 * @property string|null $aisle
 * @property string|null $bin
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
#[Table('stocks')]
class Stock extends Model
{
    use HasFactory;

    protected $fillable = ['inventory_item_id', 'location_id', 'quantity', 'aisle', 'bin'];

    protected $casts = [
        'quantity' => 'float',
    ];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
