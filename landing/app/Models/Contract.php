<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'contract_number',
        'start_date',
        'end_date',
        'status',
        'terms',
        'scanned_copy_path',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'active' => 'emerald',
            'expired' => 'red',
            'terminated' => 'gray',
            'draft' => 'blue',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'active' => 'Aktif',
            'expired' => 'Kadaluarsa',
            'terminated' => 'Diputus',
            'draft' => 'Draft',
            default => ucfirst($this->status),
        };
    }
}
