<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// DEBUG ROUTE (Temp)
Route::get('/debug-host', function() {
    return 'Laravel Host: ' . request()->getHost() . ' | Config URL: ' . config('app.url') . ' | Config Dash: ' . config('app.dashboard_domain');
});

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\SchedulingController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\RecurringBillingController;
use App\Http\Controllers\HotspotController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\KnowledgeBaseController;
use App\Http\Controllers\SlaController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\ApiManagementController;
use App\Http\Controllers\InstallationReportController;

// ============================================
// DASHBOARD ONLY ROUTES
// ============================================

Route::redirect('/', '/login');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // ... (Keep all existing dashboard routes here) ...
        // User & Role Management
        Route::resource('users', UserController::class);
    
        // Package Management
        Route::resource('packages', PackageController::class);
    
        // Lead/CRM Management
        Route::post('leads/{lead}/convert', [LeadController::class, 'convert'])->name('leads.convert');
        Route::resource('leads', LeadController::class);
        Route::resource('contracts', ContractController::class);
        Route::resource('partners', PartnerController::class);
    
        // Payment Management
        Route::post('payments/{payment}/verify', [PaymentController::class, 'verify'])->name('payments.verify');
        Route::post('payments/{payment}/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm');
        Route::post('payments/{payment}/reject', [PaymentController::class, 'reject'])->name('payments.reject');
        Route::resource('payments', PaymentController::class);
    
        // Inventory Management
        Route::post('inventory/adjust', [InventoryController::class, 'adjustStock'])->name('inventory.adjust');
        Route::post('inventory/store-serials', [InventoryController::class, 'storeSerials'])->name('inventory.store-serials');
        Route::post('inventory/assign-serial', [InventoryController::class, 'assignSerial'])->name('inventory.assign-serial');
        Route::resource('inventory', InventoryController::class);
    
        // Work Order / Field Ops
        Route::post('work-orders/{workOrder}/status', [WorkOrderController::class, 'updateStatus'])->name('work-orders.update-status');
        Route::post('work-orders/{workOrder}/add-item', [WorkOrderController::class, 'addItem'])->name('work-orders.add-item');
        Route::delete('work-order-items/{item}', [WorkOrderController::class, 'removeItem'])->name('work-order-items.destroy');
        Route::resource('work-orders', WorkOrderController::class);
    
        // Technician Management
        Route::resource('technicians', TechnicianController::class);
        Route::patch('technicians/{technician}/toggle-active', [TechnicianController::class, 'toggleActive'])
            ->name('technicians.toggleActive');
    
        // Vendor/Supplier Management
        Route::resource('vendors', \App\Http\Controllers\VendorController::class);
    
        // Asset Management
        Route::resource('assets', \App\Http\Controllers\AssetController::class);
    
        // Customer Management
        Route::patch('customers/{customer}/status', [CustomerController::class, 'updateStatus'])
            ->name('customers.updateStatus');
        Route::resource('customers', CustomerController::class);
        Route::get('customers/{customer}/payment-history', [CustomerController::class, 'paymentHistory'])
            ->name('customers.paymentHistory');
    
        // Invoice Routes (specific routes before resource)
        Route::patch('invoices/{invoice}/pay', [InvoiceController::class, 'markAsPaid'])
            ->name('invoices.markAsPaid');
        Route::get('invoices/scanner', [InvoiceController::class, 'scanner'])
            ->name('invoices.scanner');
        Route::post('invoices/lookup-token', [InvoiceController::class, 'lookupByToken'])
            ->name('invoices.lookupByToken');
        Route::post('invoices/pay-token', [InvoiceController::class, 'payByToken'])
            ->name('invoices.payByToken');
        Route::post('invoices/generate', [InvoiceController::class, 'generate'])->name('invoices.generate');
        Route::resource('invoices', InvoiceController::class);
    
        // Reports
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    
        // Tickets / Helpdesk
        Route::resource('tickets', TicketController::class);
    
        // Hotspot Voucher System
        Route::get('hotspot/vouchers', [HotspotController::class, 'index'])->name('hotspot.vouchers.index');
        Route::post('hotspot/vouchers/generate', [HotspotController::class, 'generate'])->name('hotspot.vouchers.generate');
        Route::get('hotspot/vouchers/print', [HotspotController::class, 'print'])->name('hotspot.vouchers.print');
        Route::get('hotspot/profiles', [HotspotController::class, 'profiles'])->name('hotspot.profiles.index');
        Route::post('hotspot/profiles', [HotspotController::class, 'storeProfile'])->name('hotspot.profiles.store');

        // Accounting & Expenses
        Route::resource('expenses', ExpenseController::class);

        // Settings & Logs
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index'); // Kept existing settings index route
        Route::get('logs', [AuditLogController::class, 'index'])->name('logs.index');
        Route::patch('settings', [SettingController::class, 'update'])->name('settings.update');
    
        // Payment Gateways
        Route::get('settings/payment-gateways', [\App\Http\Controllers\PaymentGatewayController::class, 'index'])->name('settings.payment-gateways');
        Route::post('settings/payment-gateways', [\App\Http\Controllers\PaymentGatewayController::class, 'update'])->name('settings.payment-gateways.update');
        Route::post('settings/payment-gateways/test/{gateway}', [\App\Http\Controllers\PaymentGatewayController::class, 'testConnection'])->name('settings.payment-gateways.test');
    
        // Scheduling
        Route::get('/scheduling', [SchedulingController::class, 'index'])->name('scheduling.index');
        Route::get('/scheduling/events', [SchedulingController::class, 'events'])->name('scheduling.events');
    
        // Billing Module
        Route::prefix('billing')->name('billing.')->group(function () {
            // Recurring Billing
            Route::get('recurring', [RecurringBillingController::class, 'index'])->name('recurring');
            Route::get('recurring/{customer}', [RecurringBillingController::class, 'show'])->name('recurring.show');
            Route::patch('recurring/{customer}/billing-date', [RecurringBillingController::class, 'updateBillingDate'])->name('recurring.update-billing-date');
    
            // Proforma Invoices
            Route::get('proforma', [\App\Http\Controllers\ProformaInvoiceController::class, 'index'])->name('proforma');
            Route::get('proforma/create', [\App\Http\Controllers\ProformaInvoiceController::class, 'create'])->name('proforma.create');
            Route::post('proforma', [\App\Http\Controllers\ProformaInvoiceController::class, 'store'])->name('proforma.store');
            Route::get('proforma/{proforma}', [\App\Http\Controllers\ProformaInvoiceController::class, 'show'])->name('proforma.show');
            Route::post('proforma/{proforma}/convert', [\App\Http\Controllers\ProformaInvoiceController::class, 'convert'])->name('proforma.convert');
            Route::post('proforma/{proforma}/cancel', [\App\Http\Controllers\ProformaInvoiceController::class, 'cancel'])->name('proforma.cancel');
    
            // Credit Notes
            Route::get('credit-notes', [\App\Http\Controllers\CreditNoteController::class, 'index'])->name('credit-notes');
            Route::get('credit-notes/create', [\App\Http\Controllers\CreditNoteController::class, 'create'])->name('credit-notes.create');
            Route::post('credit-notes', [\App\Http\Controllers\CreditNoteController::class, 'store'])->name('credit-notes.store');
            Route::get('credit-notes/{creditNote}', [\App\Http\Controllers\CreditNoteController::class, 'show'])->name('credit-notes.show');
            Route::post('credit-notes/{creditNote}/apply', [\App\Http\Controllers\CreditNoteController::class, 'apply'])->name('credit-notes.apply');
            Route::post('credit-notes/{creditNote}/cancel', [\App\Http\Controllers\CreditNoteController::class, 'cancel'])->name('credit-notes.cancel');
        });
    
        // Network Module
        Route::group(['prefix' => 'network', 'as' => 'network.'], function () {
            Route::resource('odps', \App\Http\Controllers\Network\OdpController::class);
            Route::resource('olts', \App\Http\Controllers\Network\OltController::class);
            Route::get('monitoring', [\App\Http\Controllers\Network\NetworkMonitoringController::class, 'index'])->name('monitoring.index');
            Route::post('monitoring/ping', [\App\Http\Controllers\Network\NetworkMonitoringController::class, 'ping'])->name('monitoring.ping');
            Route::get('monitoring/{id}/stats', [\App\Http\Controllers\Network\NetworkMonitoringController::class, 'getRouterStats'])->name('monitoring.stats');
            
            // OLT Signal Check
            Route::get('olts/{olt}/check-signal', [\App\Http\Controllers\Network\OltController::class, 'checkSignal'])->name('olts.check-signal');
    
            // Routers / Mikrotik
            Route::resource('routers', \App\Http\Controllers\Network\RouterController::class);
            Route::post('routers/{router}/sync', [\App\Http\Controllers\Network\RouterController::class, 'sync'])->name('routers.sync');
            Route::post('routers/{router}/test', [\App\Http\Controllers\Network\RouterController::class, 'testConnection'])->name('routers.test');
            Route::post('routers/{router}/import', [\App\Http\Controllers\Network\RouterController::class, 'importCustomers'])->name('routers.import');
    
            // IP Address Management (IPAM)
            Route::get('ipam', [\App\Http\Controllers\Network\IpamController::class, 'index'])->name('ipam.index');
            Route::get('ipam/pools/create', [\App\Http\Controllers\Network\IpamController::class, 'createPool'])->name('ipam.pools.create');
            Route::post('ipam/pools', [\App\Http\Controllers\Network\IpamController::class, 'storePool'])->name('ipam.pools.store');
            Route::delete('ipam/pools/{pool}', [\App\Http\Controllers\Network\IpamController::class, 'destroyPool'])->name('ipam.pools.destroy');
            Route::post('ipam/allocate', [\App\Http\Controllers\Network\IpamController::class, 'allocate'])->name('ipam.allocate');
            Route::post('ipam/release/{address}', [\App\Http\Controllers\Network\IpamController::class, 'release'])->name('ipam.release');
    
            // Bandwidth Management
            Route::resource('bandwidth', \App\Http\Controllers\Network\BandwidthController::class);
    
            // Network Topology
            Route::get('topology', [\App\Http\Controllers\Network\TopologyController::class, 'index'])->name('topology.index');
            Route::get('topology/data', [\App\Http\Controllers\Network\TopologyController::class, 'data'])->name('topology.data');
        }); // End of Network Group
    
        // HRD & Internal Routes
        Route::middleware(['role:super-admin|admin'])->group(function () {
            // Attendance
            Route::resource('attendance', \App\Http\Controllers\AttendanceController::class);
            Route::post('attendance/clock-in', [\App\Http\Controllers\AttendanceController::class, 'clockIn'])->name('attendance.clockIn');
            Route::post('attendance/clock-out', [\App\Http\Controllers\AttendanceController::class, 'clockOut'])->name('attendance.clockOut');
            Route::get('attendance-history', [\App\Http\Controllers\AttendanceController::class, 'history'])->name('attendance.history');
    
            // Payroll
            Route::resource('payroll', \App\Http\Controllers\PayrollController::class);
            Route::patch('payroll/{payroll}/approve', [\App\Http\Controllers\PayrollController::class, 'approve'])->name('payroll.approve');
            Route::patch('payroll/{payroll}/paid', [\App\Http\Controllers\PayrollController::class, 'markPaid'])->name('payroll.markPaid');
    
            // Leave Management
            Route::resource('leave', \App\Http\Controllers\LeaveController::class);
            Route::patch('leave/{leave}/approve', [\App\Http\Controllers\LeaveController::class, 'approve'])->name('leave.approve');
            Route::patch('leave/{leave}/reject', [\App\Http\Controllers\LeaveController::class, 'reject'])->name('leave.reject');
        });
    
        // Inventory Routes
        Route::middleware(['role:super-admin|admin|finance'])->group(function () {
            // Purchase Orders
            Route::resource('purchase-orders', \App\Http\Controllers\PurchaseOrderController::class);
            Route::patch('purchase-orders/{purchase_order}/approve', [\App\Http\Controllers\PurchaseOrderController::class, 'approve'])->name('purchase-orders.approve');
            Route::patch('purchase-orders/{purchase_order}/cancel', [\App\Http\Controllers\PurchaseOrderController::class, 'cancel'])->name('purchase-orders.cancel');
        });
    
        // Payment Manual & Gateway
        Route::resource('payments', PaymentController::class);
    
    
        Route::post('payment/{invoice}/create', [PaymentController::class, 'createTransaction'])
            ->name('payment.create');
    
        // Reports Routes
        Route::middleware(['role:super-admin|admin|finance'])->prefix('reports')->name('reports.')->group(function () {
            Route::get('revenue', [ReportController::class, 'revenue'])->name('revenue');
            Route::get('profit-loss', [ReportController::class, 'profitLoss'])->name('profit-loss');
            Route::get('customers', [ReportController::class, 'customers'])->name('customers');
            Route::get('network', [ReportController::class, 'network'])->name('network');
            Route::get('commissions', [ReportController::class, 'commissions'])->name('commissions');
        });
    
        // Audit Logs (Super Admin Only)
        Route::middleware(['role:super-admin'])->group(function () {
            Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
            Route::get('audit-logs/{auditLog}', [AuditLogController::class, 'show'])->name('audit-logs.show');
            Route::delete('audit-logs', [AuditLogController::class, 'destroy'])->name('audit-logs.clear');
        });
    
        // Customer Messages
        Route::middleware(['role:super-admin|admin|sales-cs'])->group(function () {
            Route::resource('messages', MessageController::class)->except(['edit', 'update', 'destroy']);
            Route::get('messages/conversation/{customer}', [MessageController::class, 'show'])->name('messages.conversation');
            Route::post('messages/quick', [MessageController::class, 'sendQuick'])->name('messages.quick');
        });
    
        // Installation Reports
        Route::middleware(['role:super-admin|admin|noc|technician'])->group(function () {
            Route::resource('installation-reports', InstallationReportController::class);
        });
    
        // Promotions
        Route::middleware(['role:super-admin|admin|sales-cs|finance'])->group(function () {
            Route::patch('promotions/{promotion}/toggle-active', [PromotionController::class, 'toggleActive'])->name('promotions.toggle-active');
            Route::resource('promotions', PromotionController::class);
        });
    
        // Knowledge Base
        Route::middleware(['role:super-admin|admin|sales-cs|noc|technician'])->group(function () {
            Route::resource('knowledge-base', KnowledgeBaseController::class);
        });
    
        // SLA Management
        Route::middleware(['role:super-admin|admin|noc'])->group(function () {
            Route::resource('sla', SlaController::class)->except(['show']);
        });
    
        // Campaigns
        Route::middleware(['role:super-admin|admin|sales-cs'])->group(function () {
            Route::post('campaigns/{campaign}/launch', [CampaignController::class, 'launch'])->name('campaigns.launch');
            Route::post('campaigns/{campaign}/cancel', [CampaignController::class, 'cancel'])->name('campaigns.cancel');
            Route::resource('campaigns', CampaignController::class);
        });
    
        // Referrals
        Route::middleware(['role:super-admin|admin|sales-cs'])->group(function () {
            Route::post('referrals/{referral}/qualify', [ReferralController::class, 'markQualified'])->name('referrals.qualify');
            Route::post('referrals/{referral}/pay', [ReferralController::class, 'payReward'])->name('referrals.pay');
            Route::resource('referrals', ReferralController::class)->except(['edit', 'update', 'show']);
        });
    
        // GPS Tracking
        Route::middleware(['role:super-admin|admin|noc'])->group(function () {
            Route::get('tracking', [TrackingController::class, 'index'])->name('tracking.index');
        });
    
        // Backup (Super Admin Only)
        Route::middleware(['role:super-admin'])->group(function () {
            Route::get('backup', [BackupController::class, 'index'])->name('backup.index');
            Route::post('backup', [BackupController::class, 'create'])->name('backup.create');
            Route::get('backup/{filename}/download', [BackupController::class, 'download'])->name('backup.download');
            Route::delete('backup/{filename}', [BackupController::class, 'destroy'])->name('backup.destroy');
        });
    
        // API Management (Super Admin Only)
        Route::middleware(['role:super-admin'])->group(function () {
            Route::patch('api-management/{apiKey}/toggle', [ApiManagementController::class, 'toggleActive'])->name('api-management.toggle');
            Route::post('api-management/{apiKey}/regenerate', [ApiManagementController::class, 'regenerate'])->name('api-management.regenerate');
            Route::resource('api-management', ApiManagementController::class)->except(['edit', 'update', 'show']);
        });
        
    // Profile Routes (Inside Dashboard)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================================
// Public Webhook (No Auth)
// ============================================
Route::post('payment/webhook', [PaymentController::class, 'handleWebhook'])
    ->name('payment.webhook');

require __DIR__ . '/auth.php';
