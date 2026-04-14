<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('assigned_to')->constrained('users');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('due_at');
            $table->enum('priority', ['Low', 'Medium', 'High', 'Urgent'])->default('Medium');
            $table->enum('status', ['Pending', 'In Progress', 'Completed', 'Cancelled'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tasks');
    }
};