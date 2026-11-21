<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Package;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Olt;
use App\Services\Network\PppoeService;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerService
{
    public function __construct(
        protected PppoeService $pppoeService
    ) {}

    /**
     * Get paginated customer list with filters.
     */
    public function index(array $filters): LengthAwarePaginator
    {
        // Section 8.4 Rules: Eager loading to avoid N+1 and optimize performance
        $query = Customer::with(['package', 'technician', 'partner', 'router', 'olt']);

        $this->applyFilters($query, $filters);

        return $query->latest()->paginate($filters['limit'] ?? 10);
    }

    /**
     * Get customer statistics for the index page.
     */
    public function getStats(array $filters): array
    {
        $query = Customer::query();
        
        if (isset($filters['year'])) {
            $query->whereYear('created_at', $filters['year']);
        }
        if (isset($filters['month'])) {
            $query->whereMonth('created_at', $filters['month']);
        }

        return [
            'total' => (clone $query)->count(),
            'active' => (clone $query)->where('status', 'active')->count(),
            'pending' => (clone $query)->whereIn('status', [
                Customer::STATUS_REGISTERED,
                Customer::STATUS_SURVEY,
                Customer::STATUS_APPROVED,
                Customer::STATUS_SCHEDULED,
                Customer::STATUS_INSTALLING
            ])->count(),
            'suspended' => (clone $query)->where('status', 'suspended')->count(),
        ];
    }

    /**
     * Get all filter options (packages, locations, etc.).
     */
    public function getFilterOptions(): array
    {
        return [
            'statuses' => Customer::STATUSES,
            'packages' => Package::orderBy('name')->get(['id', 'name']),
            'locations' => [
                'kelurahan' => Customer::distinct()->whereNotNull('kelurahan')->where('kelurahan', '!=', '')->pluck('kelurahan')->sort()->values(),
                'kecamatan' => Customer::distinct()->whereNotNull('kecamatan')->where('kecamatan', '!=', '')->pluck('kecamatan')->sort()->values(),
                'kabupaten' => Customer::distinct()->whereNotNull('kabupaten')->where('kabupaten', '!=', '')->pluck('kabupaten')->sort()->values(),
                'provinsi' => Customer::distinct()->whereNotNull('provinsi')->where('provinsi', '!=', '')->pluck('provinsi')->sort()->values(),
            ]
        ];
    }

    /**
     * Get data for creating/editing a customer.
     */
    public function getFormData(): array
    {
        // First, get the default router to fetch profiles if possible
        // Be more lenient with statuses in dev/fix mode to ensure data appears
        $defaultRouter = \App\Models\Router::whereIn('status', ['active', 'online', 'up'])->first() 
            ?? \App\Models\Router::first();
            
        $pppoeProfiles = $defaultRouter ? $this->pppoeService->getProfiles($defaultRouter) : [];

        // Ensure all collections are transformed to arrays or handled defensively
        return [
            // 1. Layanan Internet
            'packages' => Package::active()->orderBy('name')->get(['id', 'name', 'price']) ?? [],
            
            // 6. Teknisi (Include technician and noc roles)
            'technicians' => User::role(['technician', 'noc'])->orderBy('name')->get(['id', 'name']) ?? [], 
            
            // 2. OLT Tujuan
            'olts' => Olt::whereIn('status', ['active', 'online', 'up', 'online', 'online'])->orderBy('name')->get(['id', 'name', 'type']) ?? [],
            
            // 3. Gateway Router
            'routers' => \App\Models\Router::whereIn('status', ['active', 'online', 'up'])->orderBy('name')->get(['id', 'name']) ?? [],
            
            // 4. PPPoE Profiles
            'pppoe_profiles' => collect($pppoeProfiles)->map(function($p) {
                return is_array($p) ? $p : ['id' => $p, 'name' => $p];
            })->values()->toArray() ?: [['id' => 'default', 'name' => 'default']],
            
            // 5. Partner Agent
            'partners' => \App\Models\Partner::orderBy('name')->get(['id', 'name']) ?? [],
            
            'statuses' => Customer::STATUSES,
        ];
    }

    /**
     * Create a new customer.
     */
    public function create(array $data): Customer
    {
        $customer = Customer::create($data);
        
        // Sync to MikroTik if router and pppoe credentials are provided
        if ($customer->router_id && $customer->pppoe_username) {
            $this->pppoeService->syncSecret($customer);
        }

        return $customer;
    }

    /**
     * Update an existing customer.
     */
    public function update(Customer $customer, array $data): Customer
    {
        $customer->update($data);

        // Re-sync to MikroTik if status or network data changed
        if ($customer->wasChanged(['status', 'package_id', 'pppoe_username', 'pppoe_password', 'mikrotik_ip', 'router_id'])) {
            $this->pppoeService->syncSecret($customer);
        }

        return $customer;
    }

    /**
     * Internal method to apply filters.
     */
    private function applyFilters(Builder $query, array $filters): void
    {
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('customer_id', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('address', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['package_id']) && $filters['package_id'] !== 'all') {
            $query->where('package_id', $filters['package_id']);
        }

        foreach (['kelurahan', 'kecamatan', 'kabupaten', 'provinsi'] as $location) {
            if (!empty($filters[$location]) && $filters[$location] !== 'all') {
                $query->where($location, $filters[$location]);
            }
        }
    }

    /**
     * Manually trigger sync to MikroTik.
     */
    public function syncToMikrotik(Customer $customer): bool
    {
        if (!$customer->router_id || !$customer->pppoe_username) {
            return false;
        }

        return $this->pppoeService->syncSecret($customer);
    }
}
