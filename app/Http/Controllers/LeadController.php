<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Package;
use App\Models\User;
use App\Models\Customer;
use App\Traits\HasFilters;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    use HasFilters;

    public function index(Request $request)
    {
        $query = Lead::with(['interestedPackage', 'assignedTo']);

        // Apply global filters (year, month, search)
        $this->applyGlobalFilters($query, $request, [
            'dateColumn' => 'created_at',
            'searchColumns' => ['name', 'phone', 'email', 'lead_number']
        ]);

        // Apply specific filters
        $this->applyStatusFilter($query, $request);
        $this->applyRelationFilter($query, $request, 'assigned_to');

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        // Stats respecting filters
        $statsQuery = Lead::query();
        if ($request->filled('year')) {
            $statsQuery->whereYear('created_at', $request->year);
        }
        if ($request->filled('month')) {
            $statsQuery->whereMonth('created_at', $request->month);
        }

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'new' => (clone $statsQuery)->where('status', 'new')->count(),
            'in_progress' => (clone $statsQuery)->whereIn('status', ['contacted', 'qualified', 'proposal', 'negotiation'])->count(),
            'won' => (clone $statsQuery)->where('status', 'won')->count(),
            'lost' => (clone $statsQuery)->where('status', 'lost')->count(),
        ];

        $leads = $query->latest()->paginate(15)->withQueryString();
        $salesUsers = User::whereHas('roles', fn($q) => $q->whereIn('name', ['Super Admin', 'Sales & CS']))->get();

        // Filter options
        $statuses = [
            'new' => 'New',
            'contacted' => 'Contacted',
            'qualified' => 'Qualified',
            'proposal' => 'Proposal',
            'negotiation' => 'Negotiation',
            'won' => 'Won',
            'lost' => 'Lost',
        ];

        $sources = [
            'website' => 'Website',
            'whatsapp' => 'WhatsApp',
            'referral' => 'Referral',
            'walk-in' => 'Walk-in',
            'social_media' => 'Social Media',
            'other' => 'Other',
        ];

        return view('leads.index', compact('leads', 'stats', 'salesUsers', 'statuses', 'sources'));
    }

    public function create()
    {
        $packages = Package::where('is_active', true)->get();
        $salesUsers = User::whereHas('roles', fn($q) => $q->whereIn('name', ['Super Admin', 'Sales & CS']))->get();

        return view('leads.create', compact('packages', 'salesUsers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'rt_rw' => 'nullable|string|max:20',
            'kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kabupaten' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'source' => 'required|in:website,whatsapp,referral,walk-in,social_media,other',
            'interested_package_id' => 'nullable|exists:packages,id',
            'assigned_to' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        Lead::create($validated);

        return redirect()->route('leads.index')->with('success', 'Lead berhasil ditambahkan!');
    }

    public function show(Lead $lead)
    {
        $lead->load(['interestedPackage', 'assignedTo', 'customer']);
        return view('leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        $packages = Package::where('is_active', true)->get();
        $salesUsers = User::whereHas('roles', fn($q) => $q->whereIn('name', ['Super Admin', 'Sales & CS']))->get();

        return view('leads.edit', compact('lead', 'packages', 'salesUsers'));
    }

    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'rt_rw' => 'nullable|string|max:20',
            'kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kabupaten' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'source' => 'required|in:website,whatsapp,referral,walk-in,social_media,other',
            'interested_package_id' => 'nullable|exists:packages,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:new,contacted,qualified,proposal,negotiation,won,lost',
            'notes' => 'nullable|string',
        ]);

        $lead->update($validated);

        return redirect()->route('leads.index')->with('success', 'Lead berhasil diupdate!');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->route('leads.index')->with('success', 'Lead berhasil dihapus!');
    }

    /**
     * Convert lead to customer
     */
    public function convert(Lead $lead)
    {
        if ($lead->status === 'won' && $lead->customer_id) {
            return redirect()->route('leads.show', $lead)->with('error', 'Lead sudah dikonversi!');
        }

        // Create customer from lead data
        $customer = Customer::create([
            'name' => $lead->name,
            'phone' => $lead->phone,
            'email' => $lead->email,
            'address' => $lead->address,
            'rt_rw' => $lead->rt_rw,
            'kelurahan' => $lead->kelurahan,
            'kecamatan' => $lead->kecamatan,
            'kabupaten' => $lead->kabupaten,
            'provinsi' => $lead->provinsi,
            'kode_pos' => $lead->kode_pos,
            'latitude' => $lead->latitude,
            'longitude' => $lead->longitude,
            'package_id' => $lead->interested_package_id,
            'assigned_to' => $lead->assigned_to,
            'status' => Customer::STATUS_REGISTERED,
            'notes' => "Converted from Lead: {$lead->lead_number}",
        ]);

        // Update lead
        $lead->update([
            'status' => 'won',
            'converted_at' => now(),
            'customer_id' => $customer->id,
        ]);

        return redirect()->route('customers.show', $customer)->with('success', 'Lead berhasil dikonversi menjadi Customer!');
    }
}
