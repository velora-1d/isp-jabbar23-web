<?php

namespace App\Jobs;

use App\Services\Notification\WhatsappService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendWhatsappJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public $backoff = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected string $to,
        protected string $message
    ) {}

    /**
     * Execute the job.
     */
    public function handle(WhatsappService $whatsappService): void
    {
        $success = $whatsappService->sendMessage($this->to, $this->message);

        if (!$success) {
            throw new \Exception("Failed to send WhatsApp message to {$this->to}");
        }
    }
}
