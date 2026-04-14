<?php declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Import Controllers
use App\Http\Controllers\Api\Auth\AuthApiController;
use App\Http\Controllers\Api\Crm\LeadApiController;
use App\Http\Controllers\Api\Crm\ERegistrationApiController;
use App\Http\Controllers\Api\Inventory\PropertyApiController;
use App\Http\Controllers\Api\Finance\InvoiceApiController;
use App\Http\Controllers\Api\Finance\JournalApiController;
use App\Http\Controllers\Api\Hrms\PayrollApiController;
use App\Http\Controllers\Api\Hrms\AttendanceApiController;

/*
|--------------------------------------------------------------------------
| PFRE-OMNI API Routes - Version 1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    /**
     * 1. AUTHENTICATION (Public)
     * Throttled to prevent brute-force on your professional portal.
     */
    Route::middleware('throttle:auth')->group(function () {
        Route::post('/login', [AuthApiController::class, 'login']);
        Route::post('/register', [AuthApiController::class, 'register']);
    });

    /**
     * 2. PUBLIC PROPERTY SEARCH
     * Optimized for SEO and marketing portals.
     */
    Route::middleware('throttle:api')->group(function () {
        Route::get('/properties/search', [PropertyApiController::class, 'search']);
        Route::get('/properties/{uuid}', [PropertyApiController::class, 'show']);
    });

    // ---------------------------------------------------------
    // PROTECTED ENTERPRISE ROUTES (Requires Sanctum Token)
    // ---------------------------------------------------------
    Route::middleware('auth:sanctum')->group(function () {
        
        // USER SESSION MANAGEMENT
        Route::get('/user', fn(Request $request) => $request->user());
        Route::post('/logout', [AuthApiController::class, 'logout']);

        /**
         * CLUSTER 1: CRM (Leads & eRegistrations)
         * Includes the "33-field Master" for critical eRegistrations.
         */
        Route::prefix('crm')->name('api.crm.')->group(function () {
            Route::apiResource('leads', LeadApiController::class);

            // eRegistration Module - Critical operations with high throttle protection
            Route::middleware('throttle:critical')->group(function () {
                Route::post('/registrations', [ERegistrationApiController::class, 'store']);
            });
            Route::get('/registrations', [ERegistrationApiController::class, 'index']);
            Route::get('/registrations/{id}', [ERegistrationApiController::class, 'show']);
        });

        /**
         * CLUSTER 2: PROPERTY & INVENTORY (ERP)
         * Management of Mumbai residential/commercial units.
         */
        Route::prefix('inventory')->name('api.inventory.')->group(function () {
            Route::apiResource('properties', PropertyApiController::class);
            
            // ERP Specific: Locking a unit after a token/booking is received
            Route::post('/properties/{id}/block', [PropertyApiController::class, 'blockUnit']);
        });

        /**
         * CLUSTER 3: FINANCE & ACCOUNTING
         * Double-entry logic and GST-compliant invoicing.
         */
        Route::prefix('finance')->name('api.finance.')->group(function () {
            Route::get('/invoices', [InvoiceApiController::class, 'index']);
            Route::post('/journal-entries', [JournalApiController::class, 'store']);
        });

        /**
         * CLUSTER 4: HRMS
         * Payroll processing and biometric/app-based attendance.
         */
        Route::prefix('hrms')->name('api.hrms.')->group(function () {
            Route::get('/payroll', [PayrollApiController::class, 'index']);
            Route::post('/attendance/clock-in', [AttendanceApiController::class, 'clockIn']);
        });

    });
});