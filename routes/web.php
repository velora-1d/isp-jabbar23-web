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

use App\Http\Controllers\LandingController;

// ============================================
// LANDING PAGE ONLY ROUTES
// ============================================

Route::get('/', [LandingController::class, 'index'])->name('landing.home');
Route::get('/jangkauan', [LandingController::class, 'coverage'])->name('landing.coverage');
Route::get('/hubungi-kami', [LandingController::class, 'contact'])->name('landing.contact');

// Auth Routes (Login for admin access locally if needed, but primarily for dashboard repo)
// You can keep or remove this depending on if you want any login capability on landing repo
// keeping it minimal:
Route::get('/login', function() {
    return redirect()->away('https://jabbardash.ve-lora.my.id/login');
})->name('login');

// ============================================
// Public Webhook (No Auth)
// ============================================
Route::post('payment/webhook', [PaymentController::class, 'handleWebhook'])
    ->name('payment.webhook');

require __DIR__ . '/auth.php';
