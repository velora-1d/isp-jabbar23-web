<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Table;

/**
 * @property int $id
 * @property string $name
 * @property int $speed_down
 * @property int $speed_up
 * @property float $price
 * @property string $description
 * @property bool $is_active
 * @property string $formatted_price
 * @property string $formatted_speed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
#[Table('packages')]
class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'speed_down',
        'speed_up',
        'price',
        'description',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'speed_down' => 'integer',
        'speed_up' => 'integer',
    ];

    /**
     * Scope for active packages only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get formatted price with currency.
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->price, 0, ',', '.');
    }

    /**
     * Get formatted speed string.
     */
    public function getFormattedSpeedAttribute(): string
    {
        return $this->speed_down . '/' . $this->speed_up . ' Mbps';
    }
}
