<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partner;
use App\Models\SyncMapping;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Redirect Technician to their own dashboard
        if ($request->user() && $request->user()->hasRole('noc')) {
            return app(\App\Http\Controllers\TechnicianController::class)->dashboard($request);
        }

        $queryPartners = Partner::query();
        $queryCustomers = SyncMapping::query();

        // Filter by month
        if ($request->filled('month')) {
            $queryPartners->whereMonth('created_at', $request->month);
            $queryCustomers->whereMonth('created_at', $request->month);
        }
        
        // Filter by year
        if ($request->filled('year')) {
            $queryPartners->whereYear('created_at', $request->year);
            $queryCustomers->whereYear('created_at', $request->year);
        }

        // 1. Hitung Total Mitra
        $totalPartners = $queryPartners->count();

        // 2. Hitung Total Pelanggan Mapping
        $totalCustomers = $queryCustomers->count();

        // 3. Hitung Pelanggan Aktif vs Suspend (Respects filters)
        $activeCustomers = (clone $queryCustomers)->where('status', 'ACTIVE')->count();
        $suspendedCustomers = (clone $queryCustomers)->where('status', 'SUSPENDED')->count();
        
        // 4. Hitung Pertumbuhan (New this Month - Global context)
        $newPartnersThisMonth = Partner::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();
            
        $newCustomersThisMonth = SyncMapping::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();

        // 4. Ambil 5 Transaksi Terakhir (Filtered)
        $latestPartners = $queryPartners->latest()->take(5)->get();

        // Get available years for filter
        // We use a safe fallback if no data exists
        $partnerYears = Partner::selectRaw('YEAR(created_at) as year')->distinct()->pluck('year')->toArray();
        $customerYears = SyncMapping::selectRaw('YEAR(created_at) as year')->distinct()->pluck('year')->toArray();
        $years = array_unique(array_merge($partnerYears, $customerYears));
        rsort($years);
        if (empty($years)) {
            $years = [date('Y')];
        }

        return view('dashboard', compact(
            'totalPartners',
            'totalCustomers',
            'activeCustomers',
            'suspendedCustomers',
            'latestPartners',
            'years',
            'newPartnersThisMonth',
            'newCustomersThisMonth'
        ));
    }
}
