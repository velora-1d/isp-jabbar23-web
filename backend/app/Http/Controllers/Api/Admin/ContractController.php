<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Customer;
use App\Services\Contract\ContractService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function __construct(
        protected ContractService $contractService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $contracts = Contract::with('customer')
            ->when($request->customer_id, fn($q) => $q->where('customer_id', $request->customer_id))
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return response()->json($contracts);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'terms' => 'nullable|string',
        ]);

        $customer = Customer::findOrFail($validated['customer_id']);
        $contract = $this->contractService->createDraft($customer, $validated);

        return response()->json([
            'message' => 'Contract draft created successfully',
            'data' => $contract
        ], 201);
    }

    public function sign(Request $request, Contract $contract): JsonResponse
    {
        $request->validate([
            'signature' => 'required|string', // Base64 image
        ]);

        $signed = $this->contractService->signDigitally(
            $contract, 
            $request->signature, 
            $request->ip()
        );

        return response()->json([
            'message' => 'Contract signed successfully',
            'data' => $signed
        ]);
    }

    public function show(Contract $contract): JsonResponse
    {
        return response()->json($contract->load('customer'));
    }
}
