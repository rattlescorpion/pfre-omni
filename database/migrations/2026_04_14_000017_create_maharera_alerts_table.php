<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('maharera_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->date('expiry_date');
            $table->string('alert_frequency'); // e.g., '30_days_before', 'weekly'
            $table->boolean('is_resolved')->default(false);
            $table->timestamp('last_notified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('maharera_alerts');
    }
};