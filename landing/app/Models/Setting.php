<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    /**
     * Get a setting value by key.
     */
    public static function getValue(string $key, mixed $default = null): mixed
    {
        $setting = self::query()->where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        // Try to decode JSON value
        $decoded = json_decode((string) $setting->value, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        // Handle boolean strings
        if ($setting->value === 'true') return true;
        if ($setting->value === 'false') return false;

        return $setting->value;
    }

    /**
     * Set a setting value by key.
     */
    public static function setValue(string $key, mixed $value, ?string $group = null): void
    {
        // Convert value to string for storage
        if (is_bool($value)) {
            $storedValue = $value ? 'true' : 'false';
        } elseif (is_array($value)) {
            $storedValue = json_encode($value);
        } else {
            $storedValue = (string) $value;
        }

        static::updateOrCreate(
            ['key' => $key],
            ['value' => $storedValue, 'group' => $group]
        );
    }
}
