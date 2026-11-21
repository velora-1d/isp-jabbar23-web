<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Requests\Api\Admin\StoreCustomerRequest;
use App\Http\Requests\Api\Admin\UpdateCustomerRequest;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct(
        protected CustomerService $customerService
    ) {}

    /**
     * Display a listing of customers with stats and options.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->all();
        
        return response()->json([
            'customers' => $this->customerService->index($filters),
            'stats' => $this->customerService->getStats($filters),
            'options' => $this->customerService->getFilterOptions(),
        ]);
    }

    /**
     * Update customer status.
     */
    public function updateStatus(Request $request, Customer $customer): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Customer::STATUSES)),
            'notes' => 'nullable|string'
        ]);

        $customer->update(['status' => $validated['status']]);
        
        if ($request->filled('notes')) {
            $latestLog = $customer->statusLogs()->latest()->first();
            if ($latestLog instanceof \Illuminate\Database\Eloquent\Model) {
                $latestLog->update(['notes' => $validated['notes']]);
            }
        }

        return response()->json([
            'message' => 'Status pelanggan berhasil diperbarui.',
            'customer' => $customer->load('package')
        ]);
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer): JsonResponse
    {
        return response()->json(
            $customer->load([
                'package', 
                'partner',
                'technician', 
                'router',
                'olt',
                'statusLogs.changedByUser', 
                'inventorySerials.item',
                'invoices' => fn($query) => $query->latest()->limit(12) // Last year of invoices
            ])
        );
    }

    /**
     * Get data for customer form.
     */
    public function formData(): JsonResponse
    {
        return response()->json($this->customerService->getFormData());
    }

    /**
     * Store a newly created customer.
     */
    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $customer = $this->customerService->create($request->validated());
        
        return response()->json([
            'message' => 'Pelanggan berhasil ditambahkan.',
            'customer' => $customer
        ], 201);
    }

    /**
     * Update the specified customer.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer): JsonResponse
    {
        $updatedCustomer = $this->customerService->update($customer, $request->validated());
        
        return response()->json([
            'message' => 'Data pelanggan berhasil diperbarui.',
            'customer' => $updatedCustomer
        ]);
    }

    /**
     * Sync customer data to MikroTik manually.
     */
    public function syncMikrotik(Customer $customer): JsonResponse
    {
        try {
            $success = $this->customerService->syncToMikrotik($customer);
            
            if ($success) {
                return response()->json([
                    'message' => 'Sinkronisasi MikroTik berhasil',
                    'success' => true
                ]);
            }

            return response()->json([
                'message' => 'Sinkronisasi MikroTik gagal. Periksa log untuk detail.',
                'success' => false
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
