<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Table;

/**
 * @property int $id
 * @property int $category_id
 * @property string|null $sku
 * @property string $name
 * @property string|null $description
 * @property string $unit
 * @property float $min_stock_alert
 * @property float $purchase_price
 * @property float $selling_price
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
#[Table('inventory_items')]
class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'description',
        'unit',
        'min_stock_alert',
        'purchase_price',
        'selling_price',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'min_stock_alert' => 'float',
        'purchase_price' => 'float',
        'selling_price' => 'float',
    ];

    public function category()
    {
        return $this->belongsTo(InventoryCategory::class, 'category_id');
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function serials()
    {
        return $this->hasMany(InventorySerial::class);
    }

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function getTotalStockAttribute(): float
    {
        return (float) $this->stocks()->sum('quantity');
    }
}
