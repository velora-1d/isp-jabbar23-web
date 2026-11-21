<?php

namespace App\Listeners;

use App\Events\TicketCreated;
use App\Jobs\SendWhatsAppJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendTicketNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TicketCreated $event): void
    {
        $ticket = $event->ticket;
        $technician = $ticket->technician;

        if (!$technician || !$technician->phone) {
            Log::warning("Skipping Ticket Notification: Technician data or phone invalid for Ticket #{$ticket->ticket_number}");
            return;
        }

        $message = "Halo, *{$technician->name}*!\n\n"
            . "🛠️ *Tugas Baru Diterima*\n\n"
            . "Tiket: *{$ticket->ticket_number}*\n"
            . "Pelanggan: *{$ticket->customer->name}*\n"
            . "Alamat: {$ticket->customer->address}\n"
            . "Keluhan: \"{$ticket->subject}\"\n"
            . "Prioritas: " . ucfirst($ticket->priority) . "\n\n"
            . "Mohon segera ditindaklanjuti. Cek detail di aplikasi teknisi.\n\n"
            . "*Sistem Monitoring ISP Jabbar*";

        SendWhatsAppJob::dispatch($technician->phone, $message);
    }
}
