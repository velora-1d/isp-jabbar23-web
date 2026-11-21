<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;

class GeneratePaymentTokens extends Command
{
    protected $signature = 'customers:generate-tokens';
    protected $description = 'Generate payment tokens for customers that do not have one';

    public function handle()
    {
        $customers = Customer::whereNull('payment_token')->get();

        if ($customers->isEmpty()) {
            $this->info('All customers already have payment tokens.');
            return 0;
        }

        $bar = $this->output->createProgressBar($customers->count());
        $bar->start();

        foreach ($customers as $customer) {
            $customer->update([
                'payment_token' => bin2hex(random_bytes(16)),
            ]);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Generated tokens for {$customers->count()} customers.");

        return 0;
    }
}
