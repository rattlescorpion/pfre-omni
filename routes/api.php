<?php declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Import Controllers (Ensure these match your actual namespaces)
use App\Http\Controllers\Api\Crm\ERegistrationApiController;
use App\Http\Controllers\Api\Crm\LeadApiController;
use App\Http\Controllers\Api\Inventory\PropertyApiController;
use App\Http\Controllers\Api\Auth\AuthApiController;

/*
|--------------------------------------------------------------------------
| API Routes - Version 1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // 1. AUTHENTICATION (Public)
    Route::post('/login', [AuthApiController::class, 'login']);
    Route::post('/register', [AuthApiController::class, 'register']);

    // 2. PUBLIC PROPERTY SEARCH (SEO/Marketing optimized)
    Route::get('/properties/search', [PropertyApiController::class, 'search']);
    Route::get('/properties/{uuid}', [PropertyApiController::class, 'show']);

    // ---------------------------------------------------------
    // PROTECTED ENTERPRISE ROUTES (Requires Sanctum Token)
    // ---------------------------------------------------------
    Route::middleware('auth:sanctum')->group(function () {
        
        // USER PROFILE
        Route::get('/user', fn(Request $request) => $request->user());
        Route::post('/logout', [AuthApiController::class, 'logout']);

        // CLUSTER 1: CRM (Leads & eRegistrations)
        Route::prefix('crm')->group(function () {
            Route::apiResource('leads', LeadApiController::class);
            
            // eRegistration Module (The 33-field Master)
            Route::get('/registrations', [ERegistrationApiController::class, 'index']);
            Route::post('/registrations', [ERegistrationApiController::class, 'store']);
            Route::get('/registrations/{id}', [ERegistrationApiController::class, 'show']);
        });

        // CLUSTER 2: PROPERTY & INVENTORY (ERP)
        Route::prefix('inventory')->group(function () {
            Route::apiResource('properties', PropertyApiController::class);
            Route::post('/properties/{id}/block', [PropertyApiController::class, 'blockUnit']);
        });

        // CLUSTER 3: FINANCE & ACCOUNTING (Double-Entry)
        Route::prefix('finance')->group(function () {
            Route::get('/invoices', [App\Http\Controllers\Api\Finance\InvoiceApiController::class, 'index']);
            Route::post('/journal-entries', [App\Http\Controllers\Api\Finance\JournalApiController::class, 'store']);
        });

        // CLUSTER 4: HRMS (Payroll & Attendance)
        Route::prefix('hrms')->group(function () {
            Route::get('/payroll', [App\Http\Controllers\Api\Hrms\PayrollApiController::class, 'index']);
            Route::post('/attendance/clock-in', [App\Http\Controllers\Api\Hrms\AttendanceApiController::class, 'clockIn']);
        });

    });
});