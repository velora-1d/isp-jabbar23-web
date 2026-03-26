<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Services\Billing\PackageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function __construct(
        protected PackageService $packageService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $onlyActive = $request->boolean('only_active', false);
        $packages = $this->packageService->getAll($onlyActive);
        
        return response()->json($packages);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'speed_down'  => ['required', 'integer', 'min:1'],
            'speed_up'    => ['required', 'integer', 'min:1'],
            'price'       => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['required', 'boolean'],
        ]);

        $package = $this->packageService->create($validated);

        return response()->json($package, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Package $package): JsonResponse
    {
        return response()->json($package);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $package): JsonResponse
    {
        $validated = $request->validate([
            'name'        => ['sometimes', 'required', 'string', 'max:255'],
            'speed_down'  => ['sometimes', 'required', 'integer', 'min:1'],
            'speed_up'    => ['sometimes', 'required', 'integer', 'min:1'],
            'price'       => ['sometimes', 'required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['sometimes', 'required', 'boolean'],
        ]);

        $this->packageService->update($package, $validated);

        return response()->json($package);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package): JsonResponse
    {
        $this->packageService->delete($package);

        return response()->json([
            'message' => 'Paket berhasil dihapus'
        ]);
    }
}
