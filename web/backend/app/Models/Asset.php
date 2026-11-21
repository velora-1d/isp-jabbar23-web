<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    protected $fillable = [
        'name',
        'code',
        'serial_number',
        'vendor_id',
        'category',
        'condition',
        'status',
        'purchase_price',
        'purchase_date',
        'warranty_until',
        'location',
        'assigned_to',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'warranty_until' => 'date',
        'purchase_price' => 'decimal:2',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function getCategoryColorAttribute(): string
    {
        return match ($this->category) {
            'network' => 'cyan',
            'computer' => 'blue',
            'office' => 'amber',
            'vehicle' => 'emerald',
            'tools' => 'violet',
            default => 'gray',
        };
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'network' => 'Jaringan',
            'computer' => 'Komputer',
            'office' => 'Perkantoran',
            'vehicle' => 'Kendaraan',
            'tools' => 'Alat',
            default => 'Lainnya',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'available' => 'emerald',
            'in_use' => 'blue',
            'maintenance' => 'amber',
            'disposed' => 'red',
            default => 'gray',
        };
    }

    public function getConditionColorAttribute(): string
    {
        return match ($this->condition) {
            'new' => 'emerald',
            'good' => 'blue',
            'fair' => 'amber',
            'poor' => 'orange',
            'broken' => 'red',
            default => 'gray',
        };
    }
}
