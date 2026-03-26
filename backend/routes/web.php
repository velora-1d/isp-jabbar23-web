<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KnowledgeBaseController;
use App\Http\Controllers\SlaController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes (CLEANED - API-ONLY MODE)
|--------------------------------------------------------------------------
|
| Dokumentasi: Sebagian besar routing telah dipindahkan ke Next.js via API.
| Route di bawah ini hanya untuk modul yang belum termigrasi sepenuhnya.
|
*/

// Redirect root to Login
Route::get('/', function () {
    return redirect('/login');
});

// DEBUG ROUTE
Route::get('/debug-host', function() {
    return 'Laravel Host: ' . request()->getHost() . ' | Config URL: ' . config('app.url');
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Fallback Dashboard for Session check
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ============================================
    // MODUL BELUM TER-MIGRASI (KEEP)
    // ============================================

    // Support & KB
    Route::middleware(['role:super_admin|admin|sales-cs|noc|technician'])->group(function () {
        Route::resource('knowledge-base', KnowledgeBaseController::class);
        Route::resource('messages', MessageController::class)->except(['edit', 'update', 'destroy']);
    });

    // SLA Management
    Route::middleware(['role:super_admin|admin|noc'])->group(function () {
        Route::resource('sla', SlaController::class)->except(['show']);
    });

    // Accounting & Reports (Partial Keep)
    Route::middleware(['role:super_admin|admin|finance'])->group(function () {
        Route::resource('expenses', ExpenseController::class);
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('revenue', [ReportController::class, 'revenue'])->name('revenue');
            Route::get('profit-loss', [ReportController::class, 'profitLoss'])->name('profit-loss');
        });
    });

    // Profile Management (Laravel Breeze Default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Public Webhook (Sering digunakan payment gateway)
Route::post('payment/webhook', [App\Http\Controllers\PaymentController::class, 'handleWebhook'])
    ->name('payment.webhook');

require __DIR__ . '/auth.php';
