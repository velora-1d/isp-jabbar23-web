<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePromotionRequest;
use App\Http\Resources\PromotionResource;
use App\Models\Promotion;
use App\Services\PromotionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    protected $promotionService;

    public function __construct(PromotionService $promotionService)
    {
        $this->promotionService = $promotionService;
    }

    public function index(Request $request)
    {
        $promotions = $this->promotionService->list($request->all());
        return PromotionResource::collection($promotions);
    }

    public function store(StorePromotionRequest $request): PromotionResource
    {
        $promotion = $this->promotionService->create($request->validated());
        return new PromotionResource($promotion);
    }

    public function show(Promotion $promotion): PromotionResource
    {
        return new PromotionResource($promotion);
    }

    public function update(StorePromotionRequest $request, Promotion $promotion): PromotionResource
    {
        $promotion = $this->promotionService->update($promotion, $request->validated());
        return new PromotionResource($promotion);
    }

    public function destroy(Promotion $promotion): JsonResponse
    {
        $this->promotionService->delete($promotion);
        return response()->json(['message' => 'Promosi berhasil dihapus.']);
    }

    public function validateCode(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $promotion = $this->promotionService->validateCode(
            $request->code, 
            $request->amount,
            $request->user()?->id
        );

        $discount = $promotion->calculateDiscount($request->amount);

        return response()->json([
            'valid' => true,
            'promotion' => new PromotionResource($promotion),
            'discount' => $discount,
            'final_amount' => $request->amount - $discount,
        ]);
    }
}
