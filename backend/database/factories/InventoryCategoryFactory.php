<?php

namespace Database\Factories;

use App\Models\InventoryCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InventoryCategoryFactory extends Factory
{
    protected $model = InventoryCategory::class;

    public function definition(): array
    {
        $name = fake()->unique()->word() . ' ' . fake()->word();

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
        ];
    }
}
