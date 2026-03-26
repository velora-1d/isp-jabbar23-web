<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function __construct(
        protected TicketService $ticketService
    ) {}

    /**
     * Display a listing of tickets.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->all();
        
        return response()->json([
            'tickets' => $this->ticketService->index($filters),
            'stats' => $this->ticketService->getStats($filters),
            'options' => $this->ticketService->getFilterOptions(),
        ]);
    }

    /**
     * Store a newly created ticket.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_id'    => 'required|exists:customers,id',
            'subject'        => 'required|string|max:255',
            'description'    => 'required|string',
            'priority'       => 'required|in:low,medium,high,critical',
            'technician_id'  => 'nullable|exists:users,id',
        ]);

        $ticket = $this->ticketService->store($validated);

        return response()->json([
            'message' => 'Tiket pelaporan berhasil dibuat.',
            'ticket'  => $ticket
        ], 201);
    }

    /**
     * Display the specified ticket.
     */
    public function show(Ticket $ticket): JsonResponse
    {
        return response()->json($ticket->load(['customer', 'technician']));
    }

    /**
     * Update the specified ticket.
     */
    public function update(Request $request, Ticket $ticket): JsonResponse
    {
        $validated = $request->validate([
            'status'        => 'sometimes|required|in:open,in_progress,resolved,closed',
            'priority'      => 'sometimes|required|in:low,medium,high,critical',
            'technician_id' => 'sometimes|nullable|exists:users,id',
            'admin_notes'   => 'sometimes|nullable|string',
        ]);

        $ticket = $this->ticketService->update($ticket, $validated);

        return response()->json([
            'message' => 'Tiket berhasil diperbarui.',
            'ticket'  => $ticket
        ]);
    }

    /**
     * Remove the specified ticket.
     */
    public function destroy(Ticket $ticket): JsonResponse
    {
        $ticket->delete();
        return response()->json(['message' => 'Tiket berhasil dihapus.']);
    }
}
