<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Services\Lead\LeadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function __construct(
        protected LeadService $leadService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $leads = Lead::with(['interestedPackage', 'assignedSales'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return response()->json($leads);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'source' => 'required|in:website,whatsapp,referral,walk-in,social_media,other',
            'interested_package_id' => 'nullable|exists:packages,id',
            'assigned_to' => 'nullable|exists:users,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        $lead = $this->leadService->create($validated);

        return response()->json([
            'message' => 'Lead created successfully',
            'data' => $lead->load(['interestedPackage', 'assignedSales'])
        ], 201);
    }

    public function show(Lead $lead): JsonResponse
    {
        return response()->json($lead->load(['interestedPackage', 'assignedSales', 'customer']));
    }

    public function updateStatus(Request $request, Lead $lead): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:new,contacted,qualified,proposal,negotiation,won,lost',
        ]);

        $updated = $this->leadService->updateStatus($lead, $validated['status']);

        return response()->json([
            'message' => 'Lead status updated successfully',
            'data' => $updated
        ]);
    }
}
