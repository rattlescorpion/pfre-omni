<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * This table allows for granular control over the PFRE-Omni platform.
     */
    public function up(): void
    {
        // 1. Permissions Table
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'block-property', 'generate-deed'
            $table->string('display_name');  // e.g., 'Block Property Unit', 'Generate Legal Deed'
            $table->string('module');        // e.g., 'Inventory', 'Finance', 'CRM'
            $table->timestamps();
        });

        // 2. Pivot table: Permission - Role (Many-to-Many)
        // This links specific capabilities to the roles created in migration #21
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name')->default('web'); // ADD THIS LINE
            $table->string('display_name')->nullable();
            $table->string('module')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
    }
};