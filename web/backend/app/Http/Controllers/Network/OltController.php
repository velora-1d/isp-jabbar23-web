<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\Olt;
use App\Traits\HasFilters;
use Illuminate\Http\Request;

class OltController extends Controller
{
    use HasFilters;

    public function index(Request $request)
    {
        $query = Olt::query();

        // Apply global filters
        $this->applyGlobalFilters($query, $request, [
            'dateColumn' => 'created_at',
            'searchColumns' => ['name', 'ip_address', 'brand', 'location']
        ]);

        // Apply status filter
        $this->applyStatusFilter($query, $request);

        // Apply type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $olts = $query->latest()->paginate(10)->withQueryString();

        // Stats
        $stats = [
            'total' => Olt::count(),
            'active' => Olt::where('status', 'active')->count(),
            'offline' => Olt::where('status', 'offline')->count(),
            'maintenance' => Olt::where('status', 'maintenance')->count(),
        ];

        // Filter options
        $statuses = [
            'active' => 'Active',
            'offline' => 'Offline',
            'maintenance' => 'Maintenance',
        ];

        $types = [
            'EPON' => 'EPON',
            'GPON' => 'GPON',
            'XGPON' => 'XGPON',
        ];

        return view('network.olts.index', compact('olts', 'stats', 'statuses', 'types'));
    }

    public function create()
    {
        return view('network.olts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:olts,name|max:50',
            'ip_address' => 'nullable|ipv4',
            'brand' => 'nullable|string|max:50',
            'type' => 'required|in:EPON,GPON,XGPON',
            'total_pon_ports' => 'required|integer|min:1',
            'status' => 'required|in:active,offline,maintenance',
            'location' => 'nullable|string|max:255',
        ]);

        Olt::create($validated);

        return redirect()->route('network.olts.index')->with('success', 'OLT created successfully.');
    }

    public function edit(Olt $olt)
    {
        return view('network.olts.edit', compact('olt'));
    }

    public function update(Request $request, Olt $olt)
    {
        $validated = $request->validate([
            'name' => 'required|max:50|unique:olts,name,' . $olt->id,
            'ip_address' => 'nullable|ipv4',
            'brand' => 'nullable|string|max:50',
            'type' => 'required|in:EPON,GPON,XGPON',
            'total_pon_ports' => 'required|integer|min:1',
            'status' => 'required|in:active,offline,maintenance',
            'location' => 'nullable|string|max:255',
        ]);

        $olt->update($validated);

        return redirect()->route('network.olts.index')->with('success', 'OLT updated successfully.');
    }

    public function destroy(Olt $olt)
    {
        $olt->delete();
        return redirect()->route('network.olts.index')->with('success', 'OLT deleted successfully.');
    }

    /**
     * Check ONU Signal (Ajax).
     */
    public function checkSignal(Request $request, Olt $olt)
    {
        $request->validate([
            'onu_index' => 'required|string',
        ]);

        try {
            $service = \App\Services\Olt\OltServiceFactory::make($olt);
            $signal = $service->getOnuSignal($request->onu_index);

            return response()->json($signal);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
