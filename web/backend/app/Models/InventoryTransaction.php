<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Table;

/**
 * @property int $id
 * @property int $inventory_item_id
 * @property int|null $inventory_serial_id
 * @property string $type
 * @property float $quantity
 * @property string|null $reference_no
 * @property int $user_id
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
#[Table('inventory_transactions')]
class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_item_id',
        'inventory_serial_id',
        'type',
        'quantity',
        'reference_no',
        'user_id',
        'notes'
    ];

    protected $casts = [
        'quantity' => 'float',
    ];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    public function serial()
    {
        return $this->belongsTo(InventorySerial::class, 'inventory_serial_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
