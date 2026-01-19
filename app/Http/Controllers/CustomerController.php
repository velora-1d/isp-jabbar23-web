<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(Request $request): View
    {
        $query = Customer::with(['package', 'technician'])->latest();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        if ($request->filled('kelurahan') && $request->kelurahan !== 'all') {
            $query->where('kelurahan', $request->kelurahan);
        }
        if ($request->filled('kecamatan') && $request->kecamatan !== 'all') {
            $query->where('kecamatan', $request->kecamatan);
        }
        if ($request->filled('kabupaten') && $request->kabupaten !== 'all') {
            $query->where('kabupaten', $request->kabupaten);
        }
        if ($request->filled('provinsi') && $request->provinsi !== 'all') {
            $query->where('provinsi', $request->provinsi);
        }
        if ($request->filled('package_id') && $request->package_id !== 'all') {
            $query->where('package_id', $request->package_id);
        }

        $customers = $query->paginate(10)->withQueryString();

        $stats = [
            'total' => Customer::count(),
            'active' => Customer::where('status', 'active')->count(),
            'pending' => Customer::whereIn('status', ['registered', 'survey', 'approved', 'scheduled', 'installing'])->count(),
            'suspended' => Customer::where('status', 'suspended')->count(),
        ];
        
        $statuses = Customer::STATUSES;
        $packages = Package::orderBy('name')->get();
        
        // Filter Options
        $kelurahans = Customer::distinct()->whereNotNull('kelurahan')->where('kelurahan', '!=', '')->pluck('kelurahan')->sort();
        $kecamatans = Customer::distinct()->whereNotNull('kecamatan')->where('kecamatan', '!=', '')->pluck('kecamatan')->sort();
        $kabupatens = Customer::distinct()->whereNotNull('kabupaten')->where('kabupaten', '!=', '')->pluck('kabupaten')->sort();
        $provinsis = Customer::distinct()->whereNotNull('provinsi')->where('provinsi', '!=', '')->pluck('provinsi')->sort();

        return view('customers.index', compact(
            'customers', 
            'stats', 
            'statuses', 
            'packages', 
            'kelurahans', 
            'kecamatans', 
            'kabupatens', 
            'provinsis'
        ));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create(): View
    {
        $packages = Package::active()->get();
        $statuses = Customer::STATUSES;
        $technicians = User::role('noc')->orderBy('name')->get();
        return view('customers.create', compact('packages', 'statuses', 'technicians'));
    }

    /**
     * Store a newly created customer.
     */
    public function store(Request $request): RedirectResponse
    {
        $validStatuses = implode(',', array_keys(Customer::STATUSES));
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'rt_rw' => 'nullable|string|max:20',
            'kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kabupaten' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'package_id' => 'required|exists:packages,id',
            'assigned_to' => 'nullable|exists:users,id',
            'team_size' => 'nullable|integer|min:1|max:10',
            'status' => "required|in:{$validStatuses}",
            'installation_date' => 'nullable|date',
            'billing_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil ditambahkan!');
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer): View
    {
        $customer->load(['package', 'technician', 'statusLogs.changedByUser']);
        $statuses = Customer::STATUSES;
        return view('customers.show', compact('customer', 'statuses'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer): View
    {
        $packages = Package::active()->get();
        $statuses = Customer::STATUSES;
        $technicians = User::role('noc')->orderBy('name')->get();
        return view('customers.edit', compact('customer', 'packages', 'statuses', 'technicians'));
    }

    /**
     * Update the specified customer.
     */
    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $validStatuses = implode(',', array_keys(Customer::STATUSES));
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'rt_rw' => 'nullable|string|max:20',
            'kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kabupaten' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'package_id' => 'required|exists:packages,id',
            'assigned_to' => 'nullable|exists:users,id',
            'team_size' => 'nullable|integer|min:1|max:10',
            'status' => "required|in:{$validStatuses}",
            'installation_date' => 'nullable|date',
            'billing_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Data pelanggan berhasil diupdate!');
    }

    /**
     * Update customer status (Technician/Admin action).
     */
    public function updateStatus(Request $request, Customer $customer): RedirectResponse
    {
        $validStatuses = implode(',', array_keys(Customer::STATUSES));
        
        $validated = $request->validate([
            'status' => "required|in:{$validStatuses}",
            'notes' => 'nullable|string'
        ]);

        $customer->status = $validated['status'];
        // If becoming active and no install date, set it
        if ($customer->status === Customer::STATUS_ACTIVE && !$customer->installation_date) {
            $customer->setAttribute('installation_date', now());
        }
        $customer->save(); // Triggers boot() listener for logging

        // Update the log entry with notes if provided
        if ($request->filled('notes')) {
            // We fetch the very latest log (just created by save())
            /** @var \App\Models\CustomerStatusLog|null $latestLog */
            $latestLog = $customer->statusLogs()->latest('id')->first();
            if ($latestLog) {
                $latestLog->update(['notes' => $validated['notes']]);
            }
        }

        return back()->with('success', 'Status progres berhasil diupdate!');
    }

    /**
     * Remove the specified customer.
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil dihapus!');
    }

    /**
     * Display payment history for a customer.
     */
    public function paymentHistory(Request $request, Customer $customer): View
    {
        $query = $customer->invoices()->orderByDesc('period_start');
        
        // Filter by year if provided
        if ($request->filled('year')) {
            $query->whereYear('period_start', $request->year);
        }
        // Filter by month if provided
        if ($request->filled('month')) {
            $query->whereMonth('period_start', $request->month);
        }
        // Filter by status if provided (paid/unpaid)
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $invoices = $query->paginate(12)->withQueryString();
        
        // Get unique years for filter dropdown
        $years = $customer->invoices()
            ->selectRaw('YEAR(period_start) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        return view('customers.payment-history', compact('customer', 'invoices', 'years'));
    }
}
