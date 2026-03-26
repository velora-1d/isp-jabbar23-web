<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Table;

/**
 * @property int $id
 * @property string $lead_number
 * @property string $name
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $address
 * @property string|null $rt_rw
 * @property string|null $kelurahan
 * @property string|null $kecamatan
 * @property string|null $kabupaten
 * @property string|null $provinsi
 * @property string|null $kode_pos
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string $source
 * @property int|null $interested_package_id
 * @property int|null $assigned_to
 * @property string $status
 * @property string|null $notes
 * @property string|null $converted_at
 * @property int|null $customer_id
 */
#[Table('leads')]
class Lead extends Model
{
    protected $fillable = [
        'lead_number',
        'name',
        'phone',
        'email',
        'address',
        'rt_rw',
        'kelurahan',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kode_pos',
        'latitude',
        'longitude',
        'source',
        'interested_package_id',
        'assigned_to',
        'status',
        'notes',
        'converted_at',
        'customer_id',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'converted_at' => 'datetime',
    ];

    public function interestedPackage(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'interested_package_id');
    }

    public function assignedSales(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
