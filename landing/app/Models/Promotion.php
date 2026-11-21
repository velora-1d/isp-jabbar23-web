<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
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

    public function getTypeColorAttribute(): string
    {
        return match ($this->type) {
            'percentage' => 'blue',
            'fixed' => 'emerald',
            'free_month' => 'purple',
            default => 'gray',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'percentage' => 'Persentase',
            'fixed' => 'Nominal',
            'free_month' => 'Gratis Bulan',
            default => $this->type,
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

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'active' => 'emerald',
            'scheduled' => 'blue',
            'expired' => 'gray',
            'exhausted' => 'amber',
            'inactive' => 'red',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active' => 'Aktif',
            'scheduled' => 'Terjadwal',
            'expired' => 'Kadaluarsa',
            'exhausted' => 'Habis',
            'inactive' => 'Nonaktif',
            default => $this->status,
        };
    }

    public function getDiscountLabelAttribute(): string
    {
        return match ($this->type) {
            'percentage' => $this->value . '%',
            'fixed' => 'Rp ' . number_format($this->value, 0, ',', '.'),
            'free_month' => $this->value . ' Bulan Gratis',
            default => (string) $this->value,
        };
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
