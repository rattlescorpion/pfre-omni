<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('followups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['Call', 'WhatsApp', 'Email', 'Site Visit', 'Meeting']);
            $table->text('remarks');
            $table->dateTime('next_followup_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('followups');
    }
};