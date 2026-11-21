<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerStatusLog extends Model
{
    protected $fillable = [
        'customer_id',
        'status',
        'previous_status',
        'changed_by',
        'notes',
        'changed_at',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    /**
     * Get the customer this log belongs to.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the user who changed the status.
     */
    public function changedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    /**
     * Get status label from Customer model.
     */
    public function getStatusLabelAttribute(): string
    {
        return Customer::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get previous status label.
     */
    public function getPreviousStatusLabelAttribute(): string
    {
        if (!$this->previous_status) return '-';
        return Customer::STATUSES[$this->previous_status] ?? ucfirst($this->previous_status);
    }
}
