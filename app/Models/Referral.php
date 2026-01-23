<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Referral extends Model
{
    protected $fillable = [
        'code',
        'referrer_id',
        'referred_id',
        'status',
        'reward_amount',
        'reward_paid',
        'qualified_at',
        'rewarded_at',
    ];

    protected $casts = [
        'reward_amount' => 'decimal:2',
        'reward_paid' => 'boolean',
        'qualified_at' => 'datetime',
        'rewarded_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($referral) {
            if (empty($referral->code)) {
                $referral->code = strtoupper(Str::random(8));
            }
        });
    }

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'referrer_id');
    }

    public function referred(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'referred_id');
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'gray',
            'qualified' => 'blue',
            'rewarded' => 'emerald',
            'expired' => 'red',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'qualified' => 'Kualifikasi',
            'rewarded' => 'Dibayar',
            'expired' => 'Kadaluarsa',
            default => ucfirst($this->status),
        };
    }
}
