<?php

namespace App\Models;

use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $ticket_number
 * @property int|null $customer_id
 * @property string $type
 * @property string $status
 * @property string $priority
 * @property string|null $scheduled_date
 * @property string|null $completed_date
 * @property int|null $technician_id
 * @property string|null $description
 * @property string|null $technician_notes
 * @property array|null $photos
 * @property int|null $odp_id
 */
class WorkOrder extends Model
{
    use HasUuids, HasTenant;

    protected $fillable = [
        'tenant_id',
        'ticket_number',
        'customer_id',
        'type',
        'status',
        'priority',
        'scheduled_date',
        'completed_date',
        'technician_id',
        'description',
        'technician_notes',
        'photos',
        'odp_id',
    ];

    protected $casts = [
        'scheduled_date' => 'datetime',
        'completed_date' => 'datetime',
        'photos' => 'array',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function odp(): BelongsTo
    {
        return $this->belongsTo(Odp::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(WorkOrderItem::class);
    }
}
