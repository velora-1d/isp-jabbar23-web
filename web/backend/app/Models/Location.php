<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Table;

/**
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string|null $address
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
#[Table('locations')]
class Location extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'address', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
