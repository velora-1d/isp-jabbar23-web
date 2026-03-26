<?php

namespace App\Services\Billing;

use App\Models\Package;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class PackageService
{
    /**
     * Get all packages.
     */
    public function getAll(bool $onlyActive = false): Collection
    {
        $query = Package::query();

        if ($onlyActive) {
            $query->active();
        }

        return $query->latest()->get();
    }

    /**
     * Create a new package.
     */
    public function create(array $data): Package
    {
        try {
            return Package::create($data);
        } catch (Exception $e) {
            Log::error("PackageService Error (Create): " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update an existing package.
     */
    public function update(Package $package, array $data): bool
    {
        try {
            return $package->update($data);
        } catch (Exception $e) {
            Log::error("PackageService Error (Update): " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete a package.
     */
    public function delete(Package $package): bool
    {
        try {
            // Check if there are customers using this package
            // You might want to prevent deletion or handle it
            return $package->delete();
        } catch (Exception $e) {
            Log::error("PackageService Error (Delete): " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Find package by ID.
     */
    public function find(int $id): ?Package
    {
        return Package::find($id);
    }
}
