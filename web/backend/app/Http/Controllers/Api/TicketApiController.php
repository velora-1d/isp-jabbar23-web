<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketApiController extends Controller
{
    /**
     * List my tickets.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $customer = Customer::where('email', $user->email)->first();

        if (!$customer) {
            return response()->json([], 200);
        }

        $tickets = Ticket::where('customer_id', $customer->id)
            ->with(['technician:id,name']) // Only get tech name
            ->latest()
            ->paginate(10);

        return response()->json($tickets);
    }

    /**
     * Create a new ticket (Report Issue).
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $user = $request->user();
        $customer = Customer::where('email', $user->email)->first();

        if (!$customer) {
            return response()->json(['message' => 'Customer profile not found'], 404);
        }

        $ticket = Ticket::create([
            'customer_id' => $customer->id,
            'subject' => $request->subject,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'open', // Default status
        ]);

        \App\Events\TicketCreated::dispatch($ticket);

        return response()->json([
            'message' => 'Laporan gangguan berhasil dikirim. Teknisi kami akan segera merespon.',
            'ticket' => $ticket,
        ], 201);
    }
}
