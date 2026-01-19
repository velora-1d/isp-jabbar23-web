<?php

namespace App\Http\Controllers;

use App\Models\InstallationReport;
use App\Models\WorkOrder;
use App\Models\User;
use Illuminate\Http\Request;

class InstallationReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super-admin|admin|noc|technician');
    }

    public function index(Request $request)
    {
        $query = InstallationReport::with(['workOrder', 'technician', 'customer'])
            ->orderBy('installation_date', 'desc');

        if ($request->filled('technician_id')) {
            $query->where('technician_id', $request->technician_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('installation_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('installation_date', '<=', $request->end_date);
        }

        $reports = $query->paginate(20);

        $stats = [
            'total' => InstallationReport::count(),
            'completed' => InstallationReport::where('status', 'completed')->count(),
            'avg_rating' => InstallationReport::whereNotNull('customer_rating')->avg('customer_rating'),
            'this_month' => InstallationReport::whereMonth('installation_date', now()->month)->count(),
        ];

        $technicians = User::role('noc')->where('is_active', true)->orderBy('name')->get(['id', 'name']);

        return view('field.installation-reports.index', compact('reports', 'stats', 'technicians'));
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
        return view('field.installation-reports.edit', compact('installationReport'));
    }

    public function update(Request $request, InstallationReport $installationReport)
    {
        $validated = $request->validate([
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
