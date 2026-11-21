<?php

namespace App\Console\Commands;

use App\Jobs\SendWhatsappJob;
use Illuminate\Console\Command;

class TestWhatsappCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wa:test {phone} {message=Testing Jabbar23 WhatsApp Gateway}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test WhatsApp message via Queue';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $phone = $this->argument('phone');
        $message = $this->argument('message');

        $this->info("Enqueuing message to {$phone}...");
        
        SendWhatsappJob::dispatch($phone, $message);

        $this->info("Job dispatched successfully! Check your queue worker.");
    }
}
