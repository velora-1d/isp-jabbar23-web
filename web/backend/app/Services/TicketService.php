<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\Customer;
use App\Models\User;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TicketService
{
    /**
     * Get list of tickets with filters.
     */
    public function index(array $filters = []): LengthAwarePaginator
    {
        $query = Ticket::with(['customer:id,name,customer_id', 'technician:id,name']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (!empty($filters['technician_id'])) {
            $query->where('technician_id', $filters['technician_id']);
        }

        if (!empty($filters['year'])) {
            $query->whereYear('created_at', $filters['year']);
        }

        if (!empty($filters['month'])) {
            $query->whereMonth('created_at', $filters['month']);
        }

        return $query->latest()->paginate($filters['per_page'] ?? 10);
    }

    /**
     * Get ticket statistics.
     */
    public function getStats(array $filters = []): array
    {
        $query = Ticket::query();

        if (!empty($filters['year'])) {
            $query->whereYear('created_at', $filters['year']);
        }
        if (!empty($filters['month'])) {
            $query->whereMonth('created_at', $filters['month']);
        }

        return [
            'total' => (clone $query)->count(),
            'open' => (clone $query)->where('status', 'open')->count(),
            'in_progress' => (clone $query)->where('status', 'in_progress')->count(),
            'resolved' => (clone $query)->where('status', 'resolved')->count(),
            'closed' => (clone $query)->where('status', 'closed')->count(),
        ];
    }

    /**
     * Store a new ticket.
     */
    public function store(array $data): Ticket
    {
        return DB::transaction(function () use ($data) {
            $ticket = Ticket::create($data);
            
            // Dispatch event for notification (if any)
            // \App\Events\TicketCreated::dispatch($ticket);
            
            return $ticket;
        });
    }

    /**
     * Update ticket.
     */
    public function update(Ticket $ticket, array $data): Ticket
    {
        return DB::transaction(function () use ($ticket, $data) {
            $ticket->fill($data);

            // Auto-set resolved_at
            if ($ticket->isDirty('status') && $data['status'] === 'resolved' && !$ticket->resolved_at) {
                $ticket->resolved_at = \Illuminate\Support\Carbon::now();
            }

            $ticket->save();
            return $ticket;
        });
    }

    /**
     * Get filter options for UI.
     */
    public function getFilterOptions(): array
    {
        return [
            'statuses' => [
                ['value' => 'open', 'label' => 'Open'],
                ['value' => 'in_progress', 'label' => 'In Progress'],
                ['value' => 'resolved', 'label' => 'Resolved'],
                ['value' => 'closed', 'label' => 'Closed'],
            ],
            'priorities' => [
                ['value' => 'low', 'label' => 'Low'],
                ['value' => 'medium', 'label' => 'Medium'],
                ['value' => 'high', 'label' => 'High'],
                ['value' => 'critical', 'label' => 'Critical'],
            ],
            'technicians' => User::role('noc')->orderBy('name')->get(['id', 'name']),
        ];
    }
}
