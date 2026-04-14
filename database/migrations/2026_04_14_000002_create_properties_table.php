<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('unit_number'); // e.g., "Flat 1204"
            $table->decimal('carpet_area', 10, 2);
            $table->decimal('base_price', 15, 2);
            $table->enum('type', ['Residential', 'Commercial', 'Plot']);
            $table->enum('status', ['Available', 'Blocked', 'Sold'])->default('Available');
            $table->json('features')->nullable(); // Store amenities like Parking, Balcony
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('properties');
    }
};