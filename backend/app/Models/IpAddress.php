<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IpAddress extends Model
{
    protected $fillable = [
        'ip_pool_id',
        'address',
        'status',
        'customer_id',
        'notes',
    ];

    public function pool(): BelongsTo
    {
        return $this->belongsTo(IpPool::class, 'ip_pool_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'available' => 'emerald',
            'allocated' => 'blue',
            'reserved' => 'amber',
            'blocked' => 'red',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'available' => 'Tersedia',
            'allocated' => 'Terpakai',
            'reserved' => 'Reserved',
            'blocked' => 'Diblokir',
            default => $this->status,
        };
    }
}
