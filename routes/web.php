<?php

use Illuminate\Support\Facades\Route;

// Controllers (ensure these exist in app/Http/Controllers)
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Finance\FinanceController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\Hrms\EmployeeController;
use App\Http\Controllers\Crm\TaskController;
use App\Http\Controllers\Crm\ERegistrationController;
use App\Http\Controllers\Inventory\InventoryController;

/*
|--------------------------------------------------------------------------
| PFRE-Omni Platform Web Routes
|--------------------------------------------------------------------------
*/

// 1. PUBLIC LANDING (redirects to dashboard as per first snippet)
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Authentication Required Group (as per second snippet)
Route::middleware(['auth', 'verified'])->group(function () {

    // 2. CORE DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');

    // 3. CRM & LEAD MANAGEMENT
    Route::prefix('crm')->name('crm.')->group(function () {
        Route::resource('leads', LeadController::class);
        Route::resource('tasks', TaskController::class);
        Route::resource('e-registrations', ERegistrationController::class);
        
        Route::post('leads/{lead}/assign', [LeadController::class, 'assign'])->name('leads.assign');
        Route::get('contacts/export', [LeadController::class, 'export'])->name('contacts.export');
    });

    // 4. PROPERTY & PROJECT INVENTORY
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::resource('projects', ProjectController::class);
        Route::resource('properties', PropertyController::class);
        
        Route::get('map-view', [PropertyController::class, 'map'])->name('map');
        Route::post('{id}/status', [InventoryController::class, 'updateStatus'])->name('status.update');
    });

    // 5. FINANCE & TAXATION (Mumbai Specific)
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('invoices', [FinanceController::class, 'index'])->name('invoices.index');
        Route::get('invoices/create', [FinanceController::class, 'create'])->name('invoices.create');
        
        Route::post('calculate-gst', [FinanceController::class, 'gst'])->name('calculate.gst');
        Route::post('calculate-stamp-duty', [FinanceController::class, 'stampDuty'])->name('calculate.stampduty');
        
        Route::get('reports', [FinanceController::class, 'reports'])->name('reports');
    });

    // 6. HRMS (Human Resource Management)
    Route::prefix('hr')->name('hr.')->group(function () {
        Route::resource('employees', EmployeeController::class);
        // Additional attendance/payroll routes can be added here
    });

    // 7. LEGAL & DOCUMENT GENERATOR
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('templates', [DocumentController::class, 'index'])->name('templates.index');
        Route::post('generate/{type}', [DocumentController::class, 'generate'])->name('generate');
        Route::get('archive', [DocumentController::class, 'archive'])->name('archive');
    });
});

// Include Laravel Breeze/Jetstream Auth Routes (if applicable)
// require __DIR__.'/auth.php';