<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('employee_id')->unique(); // e.g., PFRE-001
            $table->string('designation');
            $table->decimal('basic_salary', 15, 2);
            $table->string('pan_number', 10)->nullable();
            $table->string('bank_account_no')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->date('date_of_joining');
            $table->enum('employment_status', ['Active', 'On Leave', 'Terminated', 'Probation']);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('employees');
    }
};