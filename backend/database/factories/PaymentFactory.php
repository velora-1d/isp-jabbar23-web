<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::factory(),
            'customer_id' => fn(array $attrs) => Invoice::find($attrs['invoice_id'])?->customer_id ?? Customer::factory(),
            'amount' => fake()->randomElement([150000, 300000, 450000]),
            'payment_method' => fake()->randomElement(['cash', 'bank_transfer', 'qris']),
            'reference_number' => fake()->optional()->numerify('REF-########'),
            'paid_at' => now(),
            'status' => Payment::STATUS_CONFIRMED,
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => Payment::STATUS_PENDING,
        ]);
    }

    public function confirmed(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => Payment::STATUS_CONFIRMED,
        ]);
    }
}
