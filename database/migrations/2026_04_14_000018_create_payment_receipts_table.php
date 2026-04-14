<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payment_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_no')->unique(); // e.g., PFRE/REC/2026/001
            $table->foreignId('invoice_id')->nullable()->constrained();
            $table->decimal('amount_received', 15, 2);
            $table->string('payment_mode'); // e.g., Cheque, NEFT, UPI, Cash
            $table->string('transaction_reference')->nullable(); // UTR Number
            $table->date('received_at');
            $table->text('internal_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('payment_receipts');
    }
};