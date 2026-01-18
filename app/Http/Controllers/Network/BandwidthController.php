<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\BandwidthPlan;
use Illuminate\Http\Request;

class BandwidthController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super-admin|admin');
    }

    public function index()
    {
        $plans = BandwidthPlan::orderBy('created_at', 'desc')->paginate(15);
        return view('network.bandwidth.index', compact('plans'));
    }

    public function create()
    {
        return view('network.bandwidth.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:bandwidth_plans',
            'download_speed' => 'required|integer|min:1',
            'upload_speed' => 'required|integer|min:1',
            'burst_download' => 'nullable|integer|min:1',
            'burst_upload' => 'nullable|integer|min:1',
            'priority' => 'required|integer|min:1|max:8',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        BandwidthPlan::create($validated);

        return redirect()->route('network.bandwidth.index')
            ->with('success', 'Bandwidth plan berhasil dibuat!');
    }

    public function edit(BandwidthPlan $bandwidth)
    {
        return view('network.bandwidth.edit', compact('bandwidth'));
    }

    public function update(Request $request, BandwidthPlan $bandwidth)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:bandwidth_plans,code,' . $bandwidth->id,
            'download_speed' => 'required|integer|min:1',
            'upload_speed' => 'required|integer|min:1',
            'burst_download' => 'nullable|integer|min:1',
            'burst_upload' => 'nullable|integer|min:1',
            'priority' => 'required|integer|min:1|max:8',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $bandwidth->update($validated);

        return redirect()->route('network.bandwidth.index')
            ->with('success', 'Bandwidth plan berhasil diperbarui!');
    }

    public function destroy(BandwidthPlan $bandwidth)
    {
        BandwidthPlan::destroy($bandwidth->id);

        return redirect()->route('network.bandwidth.index')
            ->with('success', 'Bandwidth plan berhasil dihapus!');
    }
}
