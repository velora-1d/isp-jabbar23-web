<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function category()
    {
        return $this->belongsTo(InventoryCategory::class, 'category_id');
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function getTotalStockAttribute()
    {
        return $this->stocks()->sum('quantity');
    }
}
