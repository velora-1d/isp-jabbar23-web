<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Customer;
use App\Models\User;
use App\Traits\HasFilters;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    use HasFilters;

    /**
     * Display a listing of tickets.
     */
    public function index(Request $request)
    {
        $query = Ticket::with(['customer', 'technician']);

        // Apply global filters (year, month, search)
        $this->applyGlobalFilters($query, $request, [
            'dateColumn' => 'created_at',
            'searchColumns' => ['ticket_number', 'subject', 'customer.name']
        ]);

        // Apply specific filters
        $this->applyStatusFilter($query, $request);
        $this->applyRelationFilter($query, $request, 'technician_id');

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $tickets = $query->latest()->paginate(10)->withQueryString();
        $technicians = User::role('noc')->orderBy('name')->get();

        // Stats respecting filters
        $statsQuery = Ticket::query();
        if ($request->filled('year')) {
            $statsQuery->whereYear('created_at', $request->year);
        }
        if ($request->filled('month')) {
            $statsQuery->whereMonth('created_at', $request->month);
        }

        $stats = [
            'open' => (clone $statsQuery)->where('status', 'open')->count(),
            'in_progress' => (clone $statsQuery)->where('status', 'in_progress')->count(),
            'resolved' => (clone $statsQuery)->where('status', 'resolved')->count(),
        ];

        // Filter options
        $statuses = [
            'open' => 'Open',
            'in_progress' => 'In Progress',
            'resolved' => 'Resolved',
            'closed' => 'Closed',
        ];

        $priorities = [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'critical' => 'Critical',
        ];

        return view('tickets.index', compact('tickets', 'technicians', 'stats', 'statuses', 'priorities'));
    }

    /**
     * Show the form for creating a new ticket.
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $technicians = User::role('noc')->orderBy('name')->get();
        return view('tickets.create', compact('customers', 'technicians'));
    }

    /**
     * Store a newly created ticket in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,critical',
            'technician_id' => 'nullable|exists:users,id',
        ]);

        $ticket = Ticket::create($request->all());

        \App\Events\TicketCreated::dispatch($ticket);

        return redirect()->route('tickets.index')->with('success', 'Tiket pelaporan baru berhasil dibuat.');
    }

    /**
     * Display the specified ticket.
     */
    public function show(Ticket $ticket)
    {
        $technicians = User::role('noc')->orderBy('name')->get();
        return view('tickets.show', compact('ticket', 'technicians'));
    }

    /**
     * Update the specified ticket in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'priority' => 'required|in:low,medium,high,critical',
            'technician_id' => 'nullable|exists:users,id',
            'admin_notes' => 'nullable|string',
        ]);

        $ticket->fill($request->only(['status', 'priority', 'technician_id', 'admin_notes']));

        // Auto-set resolved_at if status becomes resolved
        if ($ticket->isDirty('status') && $request->status === 'resolved' && !$ticket->resolved_at) {
            $ticket->resolved_at = now();
        }

        $ticket->save();

        return redirect()->back()->with('success', 'Status tiket berhasil diperbarui.');
    }

    /**
     * Remove the specified ticket from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Tiket dihapus.');
    }
}
