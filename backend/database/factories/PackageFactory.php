<?php

namespace Database\Factories;

use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

class PackageFactory extends Factory
{
    protected $model = Package::class;

    public function definition(): array
    {
        $speeds = [10, 20, 30, 50, 100];
        $speed = fake()->randomElement($speeds);

        return [
            'name' => "Paket {$speed} Mbps",
            'speed_down' => $speed,
            'speed_up' => (int) ($speed / 2),
            'price' => $speed * 15000,
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
