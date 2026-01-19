<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of tickets.
     */
    public function index(Request $request)
    {
        $query = Ticket::with(['customer', 'technician'])->latest();

        // Filters
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('technician_id') && $request->technician_id !== 'all') {
            $query->where('technician_id', $request->technician_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($dq) use ($search) {
                      $dq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $tickets = $query->paginate(10)->withQueryString();
        $technicians = User::role('noc')->orderBy('name')->get();
        
        // Stats
        $stats = [
            'open' => Ticket::where('status', 'open')->count(),
            'in_progress' => Ticket::where('status', 'in_progress')->count(),
            'resolved' => Ticket::where('status', 'resolved')->count(),
        ];

        return view('tickets.index', compact('tickets', 'technicians', 'stats'));
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
