<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Table;

/**
 * @property int $id
 * @property int $customer_id
 * @property string $contract_number
 * @property string $start_date
 * @property string|null $end_date
 * @property string $status
 * @property string|null $terms
 * @property string|null $scanned_copy_path
 * @property string|null $digital_signature_path
 * @property string|null $signed_at
 * @property string|null $client_ip
 */
#[Table('contracts')]
class Contract extends Model
{
    protected $fillable = [
        'customer_id',
        'contract_number',
        'start_date',
        'end_date',
        'status',
        'terms',
        'scanned_copy_path',
        'digital_signature_path',
        'signed_at',
        'client_ip',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'signed_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
