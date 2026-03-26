<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Vendor;
use App\Traits\HasFilters;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AssetController extends Controller
{
    use HasFilters;

    public function __construct()
    {
        $this->middleware('role:super-admin|admin');
    }

    public function index(Request $request): View
    {
        $query = Asset::with(['vendor']);

        // Apply global filters (year, month, search)
        $this->applyGlobalFilters($query, $request, [
            'dateColumn' => 'created_at',
            'searchColumns' => ['name', 'code', 'serial_number', 'location']
        ]);

        // Apply specific filters
        $this->applyStatusFilter($query, $request);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        $assets = $query->latest()->paginate(15)->withQueryString();

        // Stats respecting filters
        $statsQuery = Asset::query();
        if ($request->filled('year')) {
            $statsQuery->whereYear('created_at', $request->year);
        }

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'available' => (clone $statsQuery)->where('status', 'available')->count(),
            'in_use' => (clone $statsQuery)->where('status', 'in_use')->count(),
            'maintenance' => (clone $statsQuery)->where('status', 'maintenance')->count(),
        ];

        // Filter options
        $statuses = [
            'available' => 'Available',
            'in_use' => 'In Use',
            'maintenance' => 'Maintenance',
            'disposed' => 'Disposed',
        ];

        $categories = [
            'network' => 'Network',
            'computer' => 'Computer',
            'office' => 'Office',
            'vehicle' => 'Vehicle',
            'tools' => 'Tools',
            'other' => 'Other',
        ];

        $conditions = [
            'new' => 'New',
            'good' => 'Good',
            'fair' => 'Fair',
            'poor' => 'Poor',
            'broken' => 'Broken',
        ];

        return view('assets.index', compact('assets', 'stats', 'statuses', 'categories', 'conditions'));
    }

    public function create(): View
    {
        $vendors = Vendor::query()
            ->where('status', '=', 'active')
            ->orderBy('name', 'asc')
            ->get();

        return view('assets.create', compact('vendors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:assets',
            'serial_number' => 'nullable|string|max:100',
            'vendor_id' => 'nullable|exists:vendors,id',
            'category' => 'required|in:network,computer,office,vehicle,tools,other',
            'condition' => 'required|in:new,good,fair,poor,broken',
            'status' => 'required|in:available,in_use,maintenance,disposed',
            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'warranty_until' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'assigned_to' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        Asset::create($validated);

        return redirect()->route('assets.index')
            ->with('success', 'Asset berhasil ditambahkan!');
    }

    public function edit(Asset $asset): View
    {
        $vendors = Vendor::query()
            ->where('status', '=', 'active')
            ->orderBy('name', 'asc')
            ->get();

        return view('assets.edit', compact('asset', 'vendors'));
    }

    public function update(Request $request, Asset $asset): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:assets,code,' . $asset->id,
            'serial_number' => 'nullable|string|max:100',
            'vendor_id' => 'nullable|exists:vendors,id',
            'category' => 'required|in:network,computer,office,vehicle,tools,other',
            'condition' => 'required|in:new,good,fair,poor,broken',
            'status' => 'required|in:available,in_use,maintenance,disposed',
            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'warranty_until' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'assigned_to' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $asset->update($validated);

        return redirect()->route('assets.index')
            ->with('success', 'Asset berhasil diperbarui!');
    }

    public function destroy(Asset $asset): RedirectResponse
    {
        Asset::destroy($asset->getKey());

        return redirect()->route('assets.index')
            ->with('success', 'Asset berhasil dihapus!');
    }
}
