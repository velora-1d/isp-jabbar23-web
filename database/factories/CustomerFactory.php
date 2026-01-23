<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'address' => fake()->address(),
            'rt_rw' => fake()->numerify('0##/0##'),
            'kelurahan' => fake()->citySuffix(),
            'kecamatan' => fake()->city(),
            'kabupaten' => fake()->city(),
            'provinsi' => 'Jawa Barat',
            'kode_pos' => fake()->postcode(),
            'latitude' => fake()->latitude(-7.5, -6.5),
            'longitude' => fake()->longitude(106.5, 107.5),
            'package_id' => Package::factory(),
            'status' => Customer::STATUS_ACTIVE,
            'installation_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'billing_date' => now()->day(1),
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => Customer::STATUS_ACTIVE,
        ]);
    }

    public function suspended(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => Customer::STATUS_SUSPENDED,
        ]);
    }
}
