<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReferralResource;
use App\Models\Referral;
use App\Services\ReferralService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    protected $referralService;

    public function __construct(ReferralService $referralService)
    {
        $this->referralService = $referralService;
    }

    public function index(Request $request)
    {
        $referrals = $this->referralService->list($request->all());
        return ReferralResource::collection($referrals);
    }

    public function stats(): JsonResponse
    {
        $stats = [
            'total_referrals' => Referral::count(),
            'total_qualified' => Referral::where('status', 'qualified')->count(),
            'total_reward_paid' => Referral::where('reward_paid', true)->sum('reward_amount'),
            'pending_rewards' => Referral::where('status', 'qualified')->where('reward_paid', false)->sum('reward_amount'),
        ];

        return response()->json($stats);
    }

    public function show(Referral $referral): ReferralResource
    {
        return new ReferralResource($referral->load(['referrer', 'referred']));
    }

    public function payout(Referral $referral): ReferralResource
    {
        $referral = $this->referralService->payout($referral);
        return new ReferralResource($referral);
    }
}
