<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Services\WorkOrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\WorkOrderResource;

class WorkOrderController extends Controller
{
    public function __construct(
        protected WorkOrderService $workOrderService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $workOrders = WorkOrder::with(['customer', 'technician', 'items.inventoryItem', 'odp'])
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return response()->json(WorkOrderResource::collection($workOrders)->response()->getData(true));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'type'        => 'required|in:installation,repair,dismantling,survey,maintenance',
            'priority'    => 'required|in:low,medium,high,critical',
            'description' => 'required|string',
            'items'       => 'nullable|array',
            'items.*.id'  => 'required|exists:inventory_items,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit'     => 'required|string',
        ]);

        $workOrder = $this->workOrderService->createWorkOrder($validated);

        return response()->json([
            'message' => 'Work order created successfully',
            'data' => new WorkOrderResource($workOrder->load(['customer', 'technician', 'items']))
        ], 201);
    }

    public function show(WorkOrder $workOrder): JsonResponse
    {
        return response()->json(new WorkOrderResource($workOrder->load(['customer', 'technician', 'items.inventoryItem', 'odp'])));
    }

    public function update(Request $request, WorkOrder $workOrder): JsonResponse
    {
        $validated = $request->validate([
            'status'           => 'required|in:pending,scheduled,on_way,in_progress,completed,cancelled',
            'technician_id'    => 'nullable|exists:users,id',
            'scheduled_date'   => 'nullable|date',
            'technician_notes' => 'nullable|string',
            'priority'         => 'sometimes|in:low,medium,high,critical',
        ]);

        $workOrder->update($validated);

        return response()->json([
            'message' => 'Work order updated successfully',
            'data' => new WorkOrderResource($workOrder->fresh(['customer', 'technician', 'items']))
        ]);
    }

    public function destroy(WorkOrder $workOrder): JsonResponse
    {
        $workOrder->delete();
        return response()->json(['message' => 'Work order deleted successfully'], 204);
    }
}
