<?php

namespace App\Http\Controllers;

use App\Models\SlaPolicy;
use App\Traits\HasFilters;
use Illuminate\Http\Request;

class SlaController extends Controller
{
    use HasFilters;

    public function __construct()
    {
        $this->middleware('role:super-admin|admin|noc');
    }

    public function index(Request $request)
    {
        $query = SlaPolicy::query();

        // Apply global filters
        $this->applyGlobalFilters($query, $request, [
            'dateColumn' => 'created_at',
            'searchColumns' => ['name', 'description']
        ]);

        // Apply priority filter
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Apply active filter
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === '1');
        }

        $policies = $query->orderBy('priority', 'desc')->paginate(15)->withQueryString();

        $stats = [
            'total' => SlaPolicy::count(),
            'active' => SlaPolicy::where('is_active', true)->count(),
        ];

        // Filter options
        $priorities = [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'critical' => 'Critical',
        ];

        return view('support.sla.index', compact('policies', 'stats', 'priorities'));
    }

    public function create()
    {
        return view('support.sla.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,critical',
            'first_response_hours' => 'required|integer|min:1',
            'resolution_hours' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        SlaPolicy::create($validated);

        return redirect()->route('sla.index')
            ->with('success', 'SLA Policy berhasil dibuat!');
    }

    public function edit(SlaPolicy $sla)
    {
        return view('support.sla.edit', compact('sla'));
    }

    public function update(Request $request, SlaPolicy $sla)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,critical',
            'first_response_hours' => 'required|integer|min:1',
            'resolution_hours' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $sla->update($validated);

        return redirect()->route('sla.index')
            ->with('success', 'SLA Policy berhasil diperbarui!');
    }

    public function destroy(SlaPolicy $sla)
    {
        $sla->delete();
        return redirect()->route('sla.index')
            ->with('success', 'SLA Policy berhasil dihapus!');
    }
}
