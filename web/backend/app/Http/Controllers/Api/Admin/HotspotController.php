<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
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
    }

    /**
     * List all hotspot vouchers.
     */
    public function index(Request $request)
    {
        $query = HotspotVoucher::with(['profile', 'router', 'creator']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('router_id')) {
            $query->where('router_id', $request->router_id);
        }

        $vouchers = $query->latest()->paginate($request->input('per_page', 20));

        return response()->json($vouchers);
    }

    /**
     * List all hotspot profiles.
     */
    public function profiles()
    {
        $profiles = HotspotProfile::withCount('vouchers')->get();
        return response()->json($profiles);
    }

    /**
     * Store a new hotspot profile.
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

        $profile = HotspotProfile::create($validated);

        return response()->json([
            'message' => 'Hotspot profile created successfully',
            'data' => $profile
        ], 201);
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
            $vouchers = $this->hotspotService->generateVouchers($router, $profile, $validated['count']);
            
            return response()->json([
                'message' => "Successfully generated {$validated['count']} vouchers",
                'data' => $vouchers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to generate vouchers: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete vouchers.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        
        HotspotVoucher::whereIn('id', $request->ids)->delete();

        return response()->json(['message' => 'Vouchers deleted successfully']);
    }
}
