<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $periodStart = fake()->dateTimeBetween('-3 months', 'now');
        $periodEnd = (clone $periodStart)->modify('+1 month');
        $dueDate = (clone $periodEnd)->modify('+7 days');

        return [
            'invoice_number' => 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
            'customer_id' => Customer::factory(),
            'amount' => fake()->randomElement([150000, 300000, 450000, 750000]),
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'due_date' => $dueDate,
            'status' => 'unpaid',
        ];
    }

    public function paid(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'paid',
            'payment_date' => now(),
            'payment_method' => 'manual',
        ]);
    }

    public function unpaid(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'unpaid',
            'payment_date' => null,
            'payment_method' => null,
        ]);
    }

    public function overdue(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'overdue',
            'due_date' => now()->subDays(14),
        ]);
    }
}
