<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Traits\HasFilters;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    use HasFilters;

    public function __construct()
    {
        $this->middleware('role:super-admin|admin|finance');
    }

    public function index(Request $request)
    {
        $query = Vendor::query();

        // Apply global filters
        $this->applyGlobalFilters($query, $request, [
            'dateColumn' => 'created_at',
            'searchColumns' => ['name', 'code', 'contact_person', 'email', 'phone']
        ]);

        // Apply status filter
        $this->applyStatusFilter($query, $request);

        // Apply type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $vendors = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => Vendor::count(),
            'active' => Vendor::where('status', 'active')->count(),
            'equipment' => Vendor::where('type', 'equipment')->count(),
            'service' => Vendor::where('type', 'service')->count(),
        ];

        // Filter options
        $statuses = [
            'active' => 'Active',
            'inactive' => 'Inactive',
        ];

        $types = [
            'equipment' => 'Equipment',
            'consumable' => 'Consumable',
            'service' => 'Service',
            'other' => 'Other',
        ];

        return view('vendors.index', compact('vendors', 'stats', 'statuses', 'types'));
    }

    public function create()
    {
        return view('vendors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:vendors',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'type' => 'required|in:equipment,consumable,service,other',
            'status' => 'required|in:active,inactive',
            'bank_name' => 'nullable|string|max:100',
            'bank_account' => 'nullable|string|max:50',
            'npwp' => 'nullable|string|max:30',
            'notes' => 'nullable|string',
        ]);

        Vendor::create($validated);

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor berhasil ditambahkan!');
    }

    public function show(Vendor $vendor)
    {
        return view('vendors.show', compact('vendor'));
    }

    public function edit(Vendor $vendor)
    {
        return view('vendors.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:vendors,code,' . $vendor->id,
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'type' => 'required|in:equipment,consumable,service,other',
            'status' => 'required|in:active,inactive',
            'bank_name' => 'nullable|string|max:100',
            'bank_account' => 'nullable|string|max:50',
            'npwp' => 'nullable|string|max:30',
            'notes' => 'nullable|string',
        ]);

        $vendor->update($validated);

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor berhasil diperbarui!');
    }

    public function destroy(Vendor $vendor)
    {
        Vendor::destroy($vendor->id);

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor berhasil dihapus!');
    }
}
