<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('client_id')->constrained('leads'); 
            $table->foreignId('property_id')->nullable()->constrained();
            $table->decimal('sub_total', 15, 2);
            $table->decimal('cgst_amount', 15, 2); // 9%
            $table->decimal('sgst_amount', 15, 2); // 9%
            $table->decimal('total_amount', 15, 2);
            $table->enum('payment_status', ['Unpaid', 'Partially Paid', 'Paid', 'Cancelled']);
            $table->date('due_date');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('invoices');
    }
};