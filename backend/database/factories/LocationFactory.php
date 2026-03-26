<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Gudang Utama', 'Gudang Cabang', 'Kantor Pusat', 'Pos Teknisi']),
            'type' => fake()->randomElement(['warehouse', 'office', 'field']),
            'address' => fake()->address(),
            'is_active' => true,
        ];
    }
}
