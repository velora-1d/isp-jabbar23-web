<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    protected $fillable = [
        'user_id',
        'period',
        'basic_salary',
        'allowances',
        'overtime',
        'bonus',
        'deductions',
        'tax',
        'net_salary',
        'working_days',
        'present_days',
        'absent_days',
        'late_days',
        'status',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'allowances' => 'decimal:2',
        'overtime' => 'decimal:2',
        'bonus' => 'decimal:2',
        'deductions' => 'decimal:2',
        'tax' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'paid_at' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'gray',
            'approved' => 'amber',
            'paid' => 'emerald',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'approved' => 'Disetujui',
            'paid' => 'Dibayar',
            default => $this->status,
        };
    }

    public function getGrossPayAttribute(): float
    {
        return $this->basic_salary + $this->allowances + $this->overtime + $this->bonus;
    }

    public function getTotalDeductionsAttribute(): float
    {
        return $this->deductions + $this->tax;
    }

    public function getPeriodLabelAttribute(): string
    {
        return \Carbon\Carbon::createFromFormat('Y-m', $this->period)->format('F Y');
    }
}
