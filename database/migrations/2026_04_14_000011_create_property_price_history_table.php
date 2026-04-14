<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('property_price_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->decimal('old_price', 15, 2);
            $table->decimal('new_price', 15, 2);
            $table->string('reason')->nullable(); // e.g., "Market appreciation" or "Festival offer"
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('property_price_history');
    }
};