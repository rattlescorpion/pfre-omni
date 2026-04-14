<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('work_date');
            $table->timestamp('clock_in')->nullable();
            $table->timestamp('clock_out')->nullable();
            $table->string('clock_in_location')->nullable(); // GPS Lat/Long for field staff
            $table->enum('status', ['Present', 'Absent', 'Late', 'Half-Day'])->default('Present');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('attendance');
    }
};