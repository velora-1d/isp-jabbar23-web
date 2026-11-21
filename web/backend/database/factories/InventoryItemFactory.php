<?php

namespace Database\Factories;

use App\Models\InventoryItem;
use App\Models\InventoryCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryItemFactory extends Factory
{
    protected $model = InventoryItem::class;

    public function definition(): array
    {
        return [
            'category_id' => InventoryCategory::factory(),
            'sku' => fake()->unique()->numerify('SKU-####'),
            'name' => fake()->randomElement(['Kabel FO', 'Connector SC', 'Router Mikrotik', 'ONU ZTE', 'Splitter 1:8']),
            'description' => fake()->sentence(),
            'unit' => fake()->randomElement(['pcs', 'meter', 'roll', 'box']),
            'min_stock_alert' => fake()->numberBetween(5, 20),
            'purchase_price' => fake()->numberBetween(10000, 500000),
            'selling_price' => fake()->numberBetween(15000, 600000),
            'is_active' => true,
        ];
    }
}
