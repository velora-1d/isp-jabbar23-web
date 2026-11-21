<?php

namespace App\Http\Controllers;

use App\Models\HotspotProfile;
use App\Models\HotspotVoucher;
use App\Models\Router;
use App\Services\Network\HotspotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HotspotController extends Controller
{
    protected $hotspotService;

    public function __construct(HotspotService $hotspotService)
    {
        $this->hotspotService = $hotspotService;
        $this->middleware('permission:view hotspots')->only(['index', 'profiles']);
        $this->middleware('permission:manage hotspots')->only(['generate', 'storeProfile']);
    }

    /**
     * Dashboard for Hotspot Vouchers.
     */
    public function index(Request $request)
    {
        $vouchers = HotspotVoucher::with(['profile', 'router', 'creator'])
            ->latest()
            ->paginate(20);

        $profiles = HotspotProfile::all();
        $routers = Router::all();

        return view('hotspot.vouchers.index', compact('vouchers', 'profiles', 'routers'));
    }

    /**
     * List of Hotspot Profiles.
     */
    public function profiles()
    {
        $profiles = HotspotProfile::withCount('vouchers')->get();
        return view('hotspot.profiles.index', compact('profiles'));
    }

    /**
     * Store new Hotspot Profile.
     */
    public function storeProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'display_name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'validity_hours' => 'required|integer|min:1',
            'data_limit_mb' => 'nullable|integer|min:0',
        ]);

        HotspotProfile::create($validated);

        return back()->with('success', 'Hotspot Profile created successfully!');
    }

    /**
     * Generate bulk vouchers.
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'router_id' => 'required|exists:routers,id',
            'hotspot_profile_id' => 'required|exists:hotspot_profiles,id',
            'count' => 'required|integer|min:1|max:500',
        ]);

        $router = Router::findOrFail($validated['router_id']);
        $profile = HotspotProfile::findOrFail($validated['hotspot_profile_id']);

        try {
            $this->hotspotService->generateVouchers($router, $profile, $validated['count']);
            return back()->with('success', "Success! Generated {$validated['count']} vouchers.");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to generate vouchers: ' . $e->getMessage()]);
        }
    }

    /**
     * Print vouchers.
     */
    public function print(Request $request)
    {
        $voucherIds = $request->input('ids', []);
        $vouchers = HotspotVoucher::with('profile')->whereIn('id', $voucherIds)->get();

        if ($vouchers->isEmpty()) {
            return back()->withErrors(['error' => 'Please select vouchers to print.']);
        }

        return view('hotspot.vouchers.print', compact('vouchers'));
    }
}
