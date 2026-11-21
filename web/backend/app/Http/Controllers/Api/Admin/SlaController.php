<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSlaRequest;
use App\Http\Requests\Admin\UpdateSlaRequest;
use App\Http\Resources\SlaPolicyResource;
use App\Models\SlaPolicy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SlaController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = SlaPolicy::query();

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $policies = $query->latest()->paginate($request->integer('per_page', 15));

        return SlaPolicyResource::collection($policies);
    }

    public function store(StoreSlaRequest $request): SlaPolicyResource
    {
        $policy = SlaPolicy::create($request->validated());

        return new SlaPolicyResource($policy);
    }

    public function show(SlaPolicy $sla): SlaPolicyResource
    {
        return new SlaPolicyResource($sla);
    }

    public function update(UpdateSlaRequest $request, SlaPolicy $sla): SlaPolicyResource
    {
        $sla->update($request->validated());

        return new SlaPolicyResource($sla);
    }

    public function destroy(SlaPolicy $sla): JsonResponse
    {
        $sla->delete();

        return response()->json([
            'message' => 'SLA Policy berhasil dihapus'
        ]);
    }
}
