<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * This table is the core of your Role-Based Access Control (RBAC).
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'proprietor', 'sales_manager', 'agent'
            $table->string('display_name');  // e.g., 'Business Owner', 'Field Executive'
            $table->text('description')->nullable();
            $table->boolean('is_system_role')->default(false); // Protects core roles from deletion
            $table->timestamps();
        });

        // Pivot table for User-Role relationship (Many-to-Many)
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name')->default('web'); // ADD THIS LINE
            $table->string('display_name')->nullable();
            $table->boolean('is_system_role')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');
    }
};