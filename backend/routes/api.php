<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerApiController;
use App\Http\Controllers\Api\TicketApiController;
use App\Http\Controllers\Api\TechnicianApiController;
use App\Http\Controllers\Api\Admin\PackageController;
use App\Http\Controllers\Api\Admin\OltController;
use App\Http\Controllers\Api\Admin\OdpController;
use App\Http\Controllers\Api\Admin\InventoryController;
use App\Http\Controllers\Api\Admin\WorkOrderController;
use App\Http\Controllers\Api\Admin\LeadController;
use App\Http\Controllers\Api\Admin\ContractController;
use App\Http\Controllers\Api\Admin\ReferralController;
use App\Http\Controllers\Api\Admin\InvoiceController as AdminInvoiceController;
use App\Http\Controllers\Api\Admin\ExpenseController;
use App\Http\Controllers\Api\Admin\AttendanceController;
use App\Http\Controllers\Api\Admin\AnalyticsController;
use App\Http\Controllers\Api\Admin\TicketController;
use App\Http\Controllers\Api\Admin\NetworkController;
use App\Http\Controllers\Api\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Api\Admin\IpamController;
use App\Http\Controllers\Api\Admin\TopologyController;
use App\Http\Controllers\Api\Admin\ProformaController;
use App\Http\Controllers\Api\Admin\CreditNoteController;
use App\Http\Controllers\Api\Admin\PayrollController;
use App\Http\Controllers\Api\Admin\PromotionController;
use App\Http\Controllers\Api\Admin\LeaveController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
// Payment Webhook (Public)
Route::post('payment/notification', [App\Http\Controllers\Api\PaymentController::class, 'handleNotification']);

Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Customer Features
    Route::get('/customer/dashboard', [CustomerApiController::class, 'dashboard']);
    Route::get('/customer/invoices', [CustomerApiController::class, 'invoices']);
    Route::post('/customer/pay', [CustomerApiController::class, 'createPayment']);
    Route::post('/customer/password', [CustomerApiController::class, 'updatePassword']);
    Route::post('/customer/profile', [CustomerApiController::class, 'updateProfile']);

    // Ticket Features
    Route::get('/customer/tickets', [TicketApiController::class, 'index']);
    Route::post('/customer/tickets', [TicketApiController::class, 'store']);

    // Technician Features
    Route::get('/technician/dashboard', [TechnicianApiController::class, 'dashboard']);
    Route::get('/technician/jobs', [TechnicianApiController::class, 'jobs']);
    Route::post('/technician/jobs/{id}/status', [TechnicianApiController::class, 'updateStatus']);
    
    // Work Orders (Wave 9)
    Route::get('/technician/work-orders', [TechnicianApiController::class, 'myWorkOrders']);
    Route::put('/technician/work-orders/{workOrder}/status', [TechnicianApiController::class, 'updateWorkOrderStatus']);

    Route::get('/technician/inventory', [TechnicianApiController::class, 'inventory']);
    Route::get('/technician/attendance', [TechnicianApiController::class, 'attendance']);
    Route::post('/technician/attendance/in', [TechnicianApiController::class, 'clockIn']);
    Route::post('/technician/attendance/out', [TechnicianApiController::class, 'clockOut']);

    // Admin Features (CRM Migration)
    Route::prefix('admin')->middleware('role:admin|super_admin')->group(function () {
        Route::get('/analytics', [AnalyticsController::class, 'index']);
        Route::get('/customers/form-data', [AdminCustomerController::class, 'formData']);
        Route::get('/customers', [AdminCustomerController::class, 'index']);
        Route::post('/customers', [AdminCustomerController::class, 'store']);
        Route::put('/customers/{customer}', [AdminCustomerController::class, 'update']);
        Route::patch('/customers/{customer}/status', [AdminCustomerController::class, 'updateStatus']);
        Route::post('/customers/{customer}/sync-mikrotik', [AdminCustomerController::class, 'syncMikrotik']);
        
        // Tickets
        Route::apiResource('tickets', TicketController::class);

        
        // Network Monitoring
        Route::get('/network/routers', [NetworkController::class, 'routers']);
        Route::get('/network/monitor/{router}', [NetworkController::class, 'monitor']);

        // Packages
        Route::apiResource('packages', PackageController::class);

        // Infrastructure
        Route::apiResource('olts', OltController::class);
        Route::apiResource('odps', OdpController::class);

        // Inventory
        Route::get('/inventory/locations', [InventoryController::class, 'locations']);
        Route::get('/inventory/categories', [InventoryController::class, 'categories']);
        Route::post('/inventory/transactions', [InventoryController::class, 'transaction']);
        Route::apiResource('inventory', InventoryController::class);

        // Work Orders
        Route::patch('work-orders/{work_order}/status', [WorkOrderController::class, 'updateStatus']);
        Route::apiResource('work-orders', WorkOrderController::class);
        Route::apiResource('knowledge-base', App\Http\Controllers\Api\Admin\KnowledgeBaseController::class);
        Route::apiResource('sla', App\Http\Controllers\Api\Admin\SlaController::class);

        // Leads & CRM
        Route::patch('leads/{lead}/status', [LeadController::class, 'updateStatus']);
        Route::apiResource('leads', LeadController::class);

        // Contracts
        Route::post('contracts/{contract}/sign', [ContractController::class, 'sign']);
        Route::apiResource('contracts', ContractController::class);

        // Referrals
        Route::get('referrals/stats', [ReferralController::class, 'stats']);
        Route::post('referrals/{referral}/pay', [ReferralController::class, 'payReward']);
        Route::apiResource('referrals', ReferralController::class);

        // Billing & Invoices
        Route::get('invoices/{invoice}/snap-token', [App\Http\Controllers\Api\PaymentController::class, 'getSnapToken']);
        Route::post('invoices/generate', [AdminInvoiceController::class, 'generate']);
        Route::post('invoices/{invoice}/pay', [AdminInvoiceController::class, 'pay']);
        Route::apiResource('invoices', AdminInvoiceController::class);

        // Settings & Config
        Route::get('settings', [App\Http\Controllers\Api\Admin\SettingController::class, 'index']);
        Route::post('settings', [App\Http\Controllers\Api\Admin\SettingController::class, 'update']);

        // Expenses
        Route::get('expenses/stats', [ExpenseController::class, 'stats']);
        Route::apiResource('expenses', ExpenseController::class);

        // Hotspot
        Route::get('hotspot/vouchers', [App\Http\Controllers\Api\Admin\HotspotController::class, 'index']);
        Route::get('hotspot/profiles', [App\Http\Controllers\Api\Admin\HotspotController::class, 'profiles']);
        Route::post('hotspot/profiles', [App\Http\Controllers\Api\Admin\HotspotController::class, 'storeProfile']);
        Route::post('hotspot/generate', [App\Http\Controllers\Api\Admin\HotspotController::class, 'generate']);
        Route::post('hotspot/bulk-delete', [App\Http\Controllers\Api\Admin\HotspotController::class, 'bulkDelete']);

        // IPAM
        Route::get('ipam/pools', [IpamController::class, 'index']);
        Route::get('ipam/pools/{pool}', [IpamController::class, 'showPool']);
        Route::post('ipam/pools', [IpamController::class, 'storePool']);
        Route::delete('ipam/pools/{pool}', [IpamController::class, 'destroyPool']);
        Route::post('ipam/allocate', [IpamController::class, 'allocate']);
        Route::post('ipam/release/{address}', [IpamController::class, 'release']);

        // Topology
        Route::get('topology/data', [TopologyController::class, 'data']);

        // Proforma Invoices
        Route::get('proforma', [ProformaController::class, 'index']);
        Route::post('proforma', [ProformaController::class, 'store']);
        Route::get('proforma/{proforma}', [ProformaController::class, 'show']);
        Route::post('proforma/{proforma}/convert', [ProformaController::class, 'convert']);
        Route::post('proforma/{proforma}/cancel', [ProformaController::class, 'cancel']);

        // Credit Notes
        Route::get('credit-notes', [CreditNoteController::class, 'index']);
        Route::post('credit-notes', [CreditNoteController::class, 'store']);
        Route::get('credit-notes/{creditNote}', [CreditNoteController::class, 'show']);
        Route::post('credit-notes/{creditNote}/apply', [CreditNoteController::class, 'apply']);
        Route::post('credit-notes/{creditNote}/cancel', [CreditNoteController::class, 'cancel']);

        // Attendance
        Route::post('attendance/clock-in', [AttendanceController::class, 'clockIn']);
        Route::post('attendance/clock-out', [AttendanceController::class, 'clockOut']);
        Route::get('attendance/today', [AttendanceController::class, 'today']);
        Route::apiResource('attendances', AttendanceController::class);

        // Payroll
        Route::get('payroll', [PayrollController::class, 'index']);
        Route::post('payroll', [PayrollController::class, 'store']);
        Route::get('payroll/{payroll}', [PayrollController::class, 'show']);
        Route::put('payroll/{payroll}', [PayrollController::class, 'update']);
        Route::post('payroll/{payroll}/approve', [PayrollController::class, 'approve']);
        Route::post('payroll/{payroll}/pay', [PayrollController::class, 'markPaid']);
        Route::delete('payroll/{payroll}', [PayrollController::class, 'destroy']);

        // Leave Management
        Route::get('leave', [LeaveController::class, 'index']);
        Route::post('leave', [LeaveController::class, 'store']);
        Route::get('leave/{leave}', [LeaveController::class, 'show']);
        Route::post('leave/{leave}/approve', [LeaveController::class, 'approve']);
        Route::post('leave/{leave}/reject', [LeaveController::class, 'reject']);
        Route::delete('leave/{leave}', [LeaveController::class, 'destroy']);
        // Marketing
        Route::get('referrals/stats', [ReferralController::class, 'stats']);
        Route::post('referrals/{referral}/payout', [ReferralController::class, 'payout']);
        Route::apiResource('referrals', ReferralController::class)->only(['index', 'show']);
        
        Route::apiResource('promotions', PromotionController::class);
        Route::post('promotions/validate', [PromotionController::class, 'validateCode']);
    });
});
