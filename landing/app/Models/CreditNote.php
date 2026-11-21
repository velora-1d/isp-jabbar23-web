<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'credit_number',
        'customer_id',
        'amount',
        'issue_date',
        'reason',
        'notes',
        'status',
        'applied_to_invoice_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'issue_date' => 'date',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function appliedInvoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'applied_to_invoice_id');
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getReasonLabelAttribute(): string
    {
        return match ($this->reason) {
            'overpayment' => 'Kelebihan Bayar',
            'refund' => 'Refund',
            'discount' => 'Diskon',
            'adjustment' => 'Penyesuaian',
            'other' => 'Lainnya',
            default => $this->reason,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'applied' => 'Diterapkan',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'amber',
            'applied' => 'emerald',
            'cancelled' => 'red',
            default => 'gray',
        };
    }
}
