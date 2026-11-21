<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Table;
use App\Traits\HasTenant;
use Illuminate\Support\Str;

#[Table('promotions')]
class Promotion extends Model
{
    use HasTenant;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'tenant_id',
        'name',
        'code',
        'description',
        'type',
        'value',
        'min_purchase',
        'max_discount',
        'usage_limit',
        'usage_count',
        'per_customer_limit',
        'start_date',
        'end_date',
        'applicable_packages',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'applicable_packages' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function getTypeColorAttribute(): string
    {
        return match ($this->type) {
            'percentage' => 'blue',
            'fixed' => 'emerald',
            'free_month' => 'purple',
            default => 'gray',
        };
    }

    public function getStatusAttribute(): string
    {
        if (!$this->is_active) {
            return 'inactive';
        }
        
        $today = now()->startOfDay();
        
        if ($this->start_date > $today) {
            return 'scheduled';
        }
        
        if ($this->end_date < $today) {
            return 'expired';
        }
        
        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return 'exhausted';
        }
        
        return 'active';
    }

    public function calculateDiscount(float $amount): float
    {
        $discount = match ($this->type) {
            'percentage' => $amount * ($this->value / 100),
            'fixed' => $this->value,
            'free_month' => $amount,
            default => 0,
        };

        if ($this->max_discount) {
            $discount = min($discount, $this->max_discount);
        }

        return $discount;
    }
}
