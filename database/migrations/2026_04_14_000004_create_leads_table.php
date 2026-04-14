<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('category'); // Doctor, Lawyer, Agent, etc.
            $table->json('phone_numbers'); // Stores multiple numbers
            $table->json('emails'); // Stores multiple emails
            $table->string('source')->nullable(); // Facebook, MagicBricks, Walk-in
            $table->enum('priority', ['Hot', 'Warm', 'Cold'])->default('Warm');
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('leads');
    }
};