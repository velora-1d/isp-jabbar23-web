<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Traits\HasFilters;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PackageController extends Controller
{
    use HasFilters;

    /**
     * Display a listing of packages.
     */
    public function index(Request $request): View
    {
        $query = Package::query();

        // Apply global filters
        $this->applyGlobalFilters($query, $request, [
            'dateColumn' => 'created_at',
            'searchColumns' => ['name', 'description']
        ]);

        // Apply active status filter
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === '1');
        }

        $packages = $query->latest()->paginate(15)->withQueryString();

        // Stats
        $stats = [
            'total' => Package::count(),
            'active' => Package::where('is_active', true)->count(),
            'inactive' => Package::where('is_active', false)->count(),
        ];

        return view('packages.index', compact('packages', 'stats'));
    }

    /**
     * Show the form for creating a new package.
     */
    public function create(): View
    {
        return view('packages.create');
    }

    /**
     * Store a newly created package.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'speed_down' => 'required|integer|min:1',
            'speed_up' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Package::create($validated);

        return redirect()->route('packages.index')
            ->with('success', 'Paket internet berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified package.
     */
    public function edit(Package $package): View
    {
        return view('packages.edit', compact('package'));
    }

    /**
     * Update the specified package.
     */
    public function update(Request $request, Package $package): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'speed_down' => 'required|integer|min:1',
            'speed_up' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $package->update($validated);

        return redirect()->route('packages.index')
            ->with('success', 'Paket internet berhasil diupdate!');
    }

    /**
     * Remove the specified package.
     */
    public function destroy(Package $package): RedirectResponse
    {
        $package->delete();

        return redirect()->route('packages.index')
            ->with('success', 'Paket internet berhasil dihapus!');
    }
}
