<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Odp;
use App\Services\Network\InfrastructureService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OdpController extends Controller
{
    public function __construct(
        protected InfrastructureService $infrastructureService
    ) {}

    public function index(): JsonResponse
    {
        return response()->json($this->infrastructureService->getAllOdps());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'unique:odps,name'],
            'address'     => ['nullable', 'string'],
            'latitude'    => ['nullable', 'numeric'],
            'longitude'   => ['nullable', 'numeric'],
            'total_ports' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'status'      => ['required', 'in:active,maintenance,full'],
        ]);

        $odp = $this->infrastructureService->createOdp($validated);
        return response()->json($odp, 201);
    }

    public function show(Odp $odp): JsonResponse
    {
        return response()->json($odp);
    }

    public function update(Request $request, Odp $odp): JsonResponse
    {
        $validated = $request->validate([
            'name'        => ['sometimes', 'required', 'string', 'unique:odps,name,' . $odp->id],
            'address'     => ['nullable', 'string'],
            'latitude'    => ['nullable', 'numeric'],
            'longitude'   => ['nullable', 'numeric'],
            'total_ports' => ['sometimes', 'required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'status'      => ['sometimes', 'required', 'in:active,maintenance,full'],
        ]);

        $this->infrastructureService->updateOdp($odp, $validated);
        return response()->json($odp);
    }

    public function destroy(Odp $odp): JsonResponse
    {
        $this->infrastructureService->deleteOdp($odp);
        return response()->json(['message' => 'ODP dihapus']);
    }
}
