<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('e_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained();
            $table->string('document_number')->unique(); // SRO Registration No.
            
            // Core Compliance Fields (Expanding towards your 33-field requirement)
            $table->string('party_one_name'); // Lessor/Seller
            $table->string('party_two_name'); // Lessee/Buyer
            $table->date('execution_date');
            $table->date('expiry_date')->nullable();
            
            // Financials for Stamp Duty & GST
            $table->decimal('agreement_value', 15, 2);
            $table->decimal('stamp_duty_paid', 15, 2);
            $table->decimal('registration_fee', 10, 2);
            $table->decimal('gst_amount', 15, 2)->default(0); // For your 18% calculation
            
            $table->string('scan_copy_path')->nullable(); // PDF Path
            $table->enum('filing_status', ['Pending', 'Submitted', 'Approved', 'Rejected']);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('e_registrations');
    }
};