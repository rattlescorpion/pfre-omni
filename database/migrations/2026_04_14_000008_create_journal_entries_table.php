<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date');
            $table->string('description');
            $table->string('reference_no')->nullable(); // Link to Invoice or Payroll ID
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->string('account_type'); // e.g., Cash, Bank, Revenue, Expense
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('journal_entries');
    }
};