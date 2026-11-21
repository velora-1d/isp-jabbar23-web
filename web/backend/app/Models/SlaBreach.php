<?php

namespace App\Models;

use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SlaBreach extends Model
{
    use HasUuids, HasTenant;

    protected $fillable = [
        'tenant_id',
        'sla_policy_id',
        'ticket_id',
        'breach_type',
        'due_at',
        'breached_at',
        'is_breached',
    ];

    protected $casts = [
        'due_at' => 'datetime',
        'breached_at' => 'datetime',
        'is_breached' => 'boolean',
    ];

    public function policy(): BelongsTo
    {
        return $this->belongsTo(SlaPolicy::class, 'sla_policy_id');
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
