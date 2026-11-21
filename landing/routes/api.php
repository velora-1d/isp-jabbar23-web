<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerApiController;
use App\Http\Controllers\Api\TicketApiController;
use App\Http\Controllers\Api\TechnicianApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
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

    Route::get('/technician/inventory', [TechnicianApiController::class, 'inventory']);
    Route::get('/technician/attendance', [TechnicianApiController::class, 'attendance']);
    Route::post('/technician/attendance/in', [TechnicianApiController::class, 'clockIn']);
    Route::post('/technician/attendance/out', [TechnicianApiController::class, 'clockOut']);
});
