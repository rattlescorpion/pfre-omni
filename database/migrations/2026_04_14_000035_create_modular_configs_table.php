<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This table manages the activation state of core ERP modules (Feature Flags).
     */
    public function up(): void
    {
        Schema::create('modular_configs', function (Blueprint $table) {
            $table->id();
            
            // Core Identity
            $table->string('module_name')->unique(); // e.g., 'finance', 'hrms', 'legal', 'crm'
            $table->string('display_name'); // e.g., 'Finance & Accounting Module'
            $table->text('description')->nullable();
            
            // Toggle Switch
            $table->boolean('is_active')->default(true);
            
            // Advanced Configuration
            // Stores which modules MUST be active for this one to work (e.g., 'payroll' needs 'hrms')
            $table->json('dependencies')->nullable(); 
            
            // Module-specific JSON configurations (e.g., {"default_currency": "INR"})
            $table->json('settings')->nullable(); 
            
            // Access Control
            $table->boolean('is_core_module')->default(false); // Prevents disabling vital modules like 'Inventory'
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modular_configs');
    }
};