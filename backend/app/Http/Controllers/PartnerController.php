<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Traits\HasFilters;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    use HasFilters;

    public function index(Request $request)
    {
        $query = Partner::query();

        // Apply global filters
        $this->applyGlobalFilters($query, $request, [
            'dateColumn' => 'created_at',
            'searchColumns' => ['name', 'code', 'email', 'phone']
        ]);

        // Apply status filter
        $this->applyStatusFilter($query, $request);

        $partners = $query->latest()->paginate(15)->withQueryString();

        // Stats
        $stats = [
            'total' => Partner::count(),
            'active' => Partner::where('status', 'active')->count(),
            'inactive' => Partner::where('status', 'inactive')->count(),
        ];

        // Filter options
        $statuses = [
            'active' => 'Active',
            'inactive' => 'Inactive',
        ];

        return view('partners.index', compact('partners', 'stats', 'statuses'));
    }

    public function create()
    {
        return view('partners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:partners,code',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        Partner::create($validated);

        return redirect()->route('partners.index')->with('success', 'Partner berhasil ditambahkan!');
    }

    public function show(Partner $partner)
    {
        return view('partners.show', compact('partner'));
    }

    public function edit(Partner $partner)
    {
        return view('partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:partners,code,' . $partner->id,
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        $partner->update($validated);

        return redirect()->route('partners.index')->with('success', 'Partner berhasil diperbarui!');
    }

    public function destroy(Partner $partner)
    {
        Partner::destroy($partner->id);
        return redirect()->route('partners.index')->with('success', 'Partner berhasil dihapus!');
    }
}
