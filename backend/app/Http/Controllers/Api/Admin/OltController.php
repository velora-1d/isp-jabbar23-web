<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Olt;
use App\Services\Network\InfrastructureService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OltController extends Controller
{
    public function __construct(
        protected InfrastructureService $infrastructureService
    ) {}

    public function index(): JsonResponse
    {
        return response()->json($this->infrastructureService->getAllOlts());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'            => ['required', 'string', 'unique:olts,name'],
            'ip_address'      => ['nullable', 'ip'],
            'brand'           => ['nullable', 'string'],
            'type'            => ['required', 'string'],
            'total_pon_ports' => ['required', 'integer', 'min:1'],
            'location'        => ['nullable', 'string'],
            'status'          => ['required', 'in:active,offline,maintenance'],
            'username'        => ['nullable', 'string'],
            'password'        => ['nullable', 'string'],
            'port'            => ['nullable', 'integer'],
            'community'       => ['nullable', 'string'],
            'server_profile'  => ['nullable', 'string'],
        ]);

        $olt = $this->infrastructureService->createOlt($validated);
        return response()->json($olt, 201);
    }

    public function show(Olt $olt): JsonResponse
    {
        return response()->json($olt);
    }

    public function update(Request $request, Olt $olt): JsonResponse
    {
        $validated = $request->validate([
            'name'            => ['sometimes', 'required', 'string', 'unique:olts,name,' . $olt->id],
            'ip_address'      => ['nullable', 'ip'],
            'brand'           => ['nullable', 'string'],
            'type'            => ['sometimes', 'required', 'string'],
            'total_pon_ports' => ['sometimes', 'required', 'integer', 'min:1'],
            'location'        => ['nullable', 'string'],
            'status'          => ['sometimes', 'required', 'in:active,offline,maintenance'],
            'username'        => ['nullable', 'string'],
            'password'        => ['nullable', 'string'],
            'port'            => ['nullable', 'integer'],
            'community'       => ['nullable', 'string'],
            'server_profile'  => ['nullable', 'string'],
        ]);

        $this->infrastructureService->updateOlt($olt, $validated);
        return response()->json($olt);
    }

    public function destroy(Olt $olt): JsonResponse
    {
        $this->infrastructureService->deleteOlt($olt);
        return response()->json(['message' => 'OLT dihapus']);
    }
}
