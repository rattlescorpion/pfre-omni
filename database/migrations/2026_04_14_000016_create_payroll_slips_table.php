<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payroll_slips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained();
            $table->string('month_year'); // e.g., "April 2026"
            $table->decimal('basic_pay', 15, 2);
            $table->decimal('allowances', 15, 2)->default(0);
            $table->decimal('deductions', 15, 2)->default(0); // PF, Tax, Leaves
            $table->decimal('net_salary', 15, 2);
            $table->date('payment_date')->nullable();
            $table->enum('status', ['Draft', 'Paid', 'On Hold'])->default('Draft');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('payroll_slips');
    }
};