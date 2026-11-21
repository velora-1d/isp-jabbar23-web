<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $invoice_number
 * @property int $customer_id
 * @property float $amount
 * @property Carbon $period_start
 * @property Carbon $period_end
 * @property Carbon $due_date
 * @property string $status
 * @property Carbon|null $payment_date
 * @property string|null $payment_method
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_number',
        'customer_id',
        'amount',
        'tax_amount',
        'total_after_tax',
        'period_start',
        'period_end',
        'due_date',
        'status',
        'payment_date',
        'payment_method',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_after_tax' => 'decimal:2',
        'period_start' => 'date',
        'period_end' => 'date',
        'due_date' => 'date',
        'payment_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($invoice) {
            // Default PPN is 11%
            $invoice->tax_amount = $invoice->amount * 0.11;
            $invoice->total_after_tax = $invoice->amount + $invoice->tax_amount;
        });
    }

    /**
     * Relationship with Customer.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relationship with Payments.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Scope for unpaid invoices.
     */
    public function scopeUnpaid($query)
    {
        return $query->where('status', 'unpaid');
    }

    /**
     * Scope for paid invoices.
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Get formatted amount.
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format((float) ($this->total_after_tax ?: $this->amount), 0, ',', '.');
    }

    /**
     * Get formatted period string (e.g. "Jan 2026").
     */
    public function getFormattedPeriodAttribute(): string
    {
        return $this->period_start->format('M Y');
    }

    /**
     * Check if invoice was paid on time.
     */
    public function getIsOnTimeAttribute(): ?bool
    {
        if ($this->status !== 'paid' || !$this->payment_date) {
            return null; // Not yet paid
        }
        return $this->payment_date->lte($this->due_date);
    }

    /**
     * Get days late (negative = early, 0 = on time, positive = late).
     */
    public function getDaysLateAttribute(): ?int
    {
        if ($this->status !== 'paid' || !$this->payment_date) {
            return null;
        }
        return $this->due_date->diffInDays($this->payment_date, false);
    }

    /**
     * Get payment status label.
     */
    public function getPaymentStatusLabelAttribute(): string
    {
        if ($this->status !== 'paid') {
            return 'Belum Bayar';
        }

        $daysLate = $this->days_late;
        if ($daysLate <= 0) {
            return 'Tepat Waktu';
        } elseif ($daysLate <= 7) {
            return 'Telat ' . $daysLate . ' hari';
        } else {
            return 'Telat ' . $daysLate . ' hari';
        }
    }
}
