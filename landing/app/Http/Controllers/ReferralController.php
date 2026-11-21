<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Models\Customer;
use App\Traits\HasFilters;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    use HasFilters;

    public function __construct()
    {
        $this->middleware('role:super-admin|admin|sales-cs');
    }

    public function index(Request $request)
    {
        $query = Referral::with(['referrer', 'referred']);

        // Apply global filters
        $this->applyGlobalFilters($query, $request, [
            'dateColumn' => 'created_at',
            'searchColumns' => ['referrer.name', 'referred.name', 'referral_code']
        ]);

        // Apply status filter
        $this->applyStatusFilter($query, $request);

        $referrals = $query->latest()->paginate(20)->withQueryString();

        // Stats
        $statsQuery = Referral::query();
        if ($request->filled('year')) {
            $statsQuery->whereYear('created_at', $request->year);
        }

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'pending' => (clone $statsQuery)->where('status', 'pending')->count(),
            'qualified' => (clone $statsQuery)->where('status', 'qualified')->count(),
            'total_rewards' => (clone $statsQuery)->where('reward_paid', true)->sum('reward_amount'),
        ];

        // Filter options
        $statuses = [
            'pending' => 'Pending',
            'qualified' => 'Qualified',
            'rewarded' => 'Rewarded',
            'expired' => 'Expired',
        ];

        return view('marketing.referrals.index', compact('referrals', 'stats', 'statuses'));
    }

    public function create()
    {
        $customers = Customer::where('status', 'active')->orderBy('name')->get(['id', 'name']);
        return view('marketing.referrals.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'referrer_id' => 'required|exists:customers,id',
            'reward_amount' => 'required|numeric|min:0',
        ]);

        Referral::create($validated);

        return redirect()->route('referrals.index')
            ->with('success', 'Kode referral berhasil dibuat!');
    }

    public function markQualified(Referral $referral)
    {
        $referral->update([
            'status' => 'qualified',
            'qualified_at' => now(),
        ]);

        return back()->with('success', 'Referral dikualifikasi!');
    }

    public function payReward(Referral $referral)
    {
        $referral->update([
            'status' => 'rewarded',
            'reward_paid' => true,
            'rewarded_at' => now(),
        ]);

        return back()->with('success', 'Reward berhasil dibayarkan!');
    }

    public function destroy(Referral $referral)
    {
        $referral->delete();
        return redirect()->route('referrals.index')
            ->with('success', 'Referral berhasil dihapus!');
    }
}
