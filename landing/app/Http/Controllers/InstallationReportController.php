<?php

namespace App\Http\Controllers;

use App\Models\InstallationReport;
use App\Models\WorkOrder;
use App\Models\User;
use App\Traits\HasFilters;
use Illuminate\Http\Request;

class InstallationReportController extends Controller
{
    use HasFilters;

    public function __construct()
    {
        $this->middleware('role:super-admin|admin|noc|technician');
    }

    public function index(Request $request)
    {
        $query = InstallationReport::with(['workOrder', 'technician', 'customer']);

        // Apply global filters
        $this->applyGlobalFilters($query, $request, [
            'dateColumn' => 'installation_date',
            'searchColumns' => ['work_performed', 'issues_found', 'customer.name']
        ]);

        // Apply status filter
        $this->applyStatusFilter($query, $request);

        // Apply technician filter
        $this->applyRelationFilter($query, $request, 'technician_id');

        $reports = $query->latest('installation_date')->paginate(20)->withQueryString();

        // Stats
        $statsQuery = InstallationReport::query();
        if ($request->filled('year')) {
            $statsQuery->whereYear('installation_date', $request->year);
        }
        if ($request->filled('month')) {
            $statsQuery->whereMonth('installation_date', $request->month);
        }

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'completed' => (clone $statsQuery)->where('status', 'completed')->count(),
            'avg_rating' => InstallationReport::whereNotNull('customer_rating')->avg('customer_rating'),
            'this_month' => InstallationReport::whereMonth('installation_date', now()->month)->count(),
        ];

        // Filter options
        $technicians = User::role('technician')->where('is_active', true)->orderBy('name')->get(['id', 'name']);
        $statuses = [
            'completed' => 'Completed',
            'partial' => 'Partial',
            'failed' => 'Failed',
            'rescheduled' => 'Rescheduled',
        ];

        return view('field.installation-reports.index', compact('reports', 'stats', 'technicians', 'statuses'));
    }

    public function create(Request $request)
    {
        $workOrders = WorkOrder::where('status', 'completed')
            ->whereDoesntHave('installationReport')
            ->with(['customer', 'technician'])
            ->orderBy('created_at', 'desc')
            ->get();

        $workOrder = null;
        if ($request->filled('work_order_id')) {
            $workOrder = WorkOrder::with(['customer', 'technician'])->find($request->work_order_id);
        }

        return view('field.installation-reports.create', compact('workOrders', 'workOrder'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'work_order_id' => 'required|exists:work_orders,id',
            'installation_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'status' => 'required|in:completed,partial,failed,rescheduled',
            'work_performed' => 'required|string',
            'issues_found' => 'nullable|string',
            'resolution' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $workOrder = WorkOrder::find($validated['work_order_id']);
        $validated['technician_id'] = $workOrder->technician_id;
        $validated['customer_id'] = $workOrder->customer_id;

        InstallationReport::create($validated);

        return redirect()->route('installation-reports.index')
            ->with('success', 'Laporan instalasi berhasil dibuat!');
    }

    public function show(InstallationReport $installationReport)
    {
        $installationReport->load(['workOrder', 'technician', 'customer']);
        return view('field.installation-reports.show', compact('installationReport'));
    }

    public function edit(InstallationReport $installationReport)
    {
        $workOrders = WorkOrder::with('customer')->orderBy('id', 'desc')->get();
        return view('field.installation-reports.edit', compact('installationReport', 'workOrders'));
    }

    public function update(Request $request, InstallationReport $installationReport)
    {
        $validated = $request->validate([
            'work_order_id' => 'required|exists:work_orders,id',
            'installation_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'status' => 'required|in:completed,partial,failed,rescheduled',
            'work_performed' => 'required|string',
            'issues_found' => 'nullable|string',
            'resolution' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $installationReport->update($validated);

        return redirect()->route('installation-reports.index')
            ->with('success', 'Laporan instalasi berhasil diperbarui!');
    }

    public function destroy(InstallationReport $installationReport)
    {
        InstallationReport::destroy($installationReport->id);
        return redirect()->route('installation-reports.index')
            ->with('success', 'Laporan instalasi berhasil dihapus!');
    }
}
