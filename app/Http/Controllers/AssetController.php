<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AssetController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super-admin|admin');
    }

    public function index(): View
    {
        $assets = Asset::query()
            ->with(['vendor'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => Asset::query()->count('*'),
            'available' => Asset::query()->where('status', '=', 'available')->count('*'),
            'in_use' => Asset::query()->where('status', '=', 'in_use')->count('*'),
            'maintenance' => Asset::query()->where('status', '=', 'maintenance')->count('*'),
        ];

        return view('assets.index', compact('assets', 'stats'));
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
        $asset->delete();

        return redirect()->route('assets.index')
            ->with('success', 'Asset berhasil dihapus!');
    }
}
