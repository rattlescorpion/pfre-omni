<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Skyline Heights"
            $table->string('maharera_reg_no')->unique()->nullable();
            $table->string('location_area'); // e.g., "Andheri West"
            $table->text('address');
            $table->enum('status', ['Under Construction', 'Ready Possession', 'Pre-Launch']);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('projects');
    }
};