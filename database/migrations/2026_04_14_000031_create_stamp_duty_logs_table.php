<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This table tracks the specific rates and calculations used for Maharashtra 
     * Stamp Duty at the time of document execution.
     */
    public function up(): void
    {
        Schema::create('stamp_duty_logs', function (Blueprint $table) {
            $table->id();
            
            // Linking to the specific transaction
            $table->foreignId('property_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('e_registration_id')->nullable()->constrained()->onDelete('cascade');
            
            // Calculation Context
            $table->decimal('agreement_value', 15, 2);
            $table->decimal('market_value', 15, 2); // Ready Reckoner Rate (RR Rate)
            
            // Rate applied at that point in time
            $table->decimal('stamp_duty_rate', 5, 2); // e.g., 5.00 or 6.00
            $table->decimal('surcharge_rate', 5, 2)->default(1.00); // e.g., Metro Cess
            
            // Final Amounts
            $table->decimal('calculated_stamp_duty', 15, 2);
            $table->decimal('registration_fee', 15, 2);
            
            // Compliance Metadata
            $table->string('article_code')->default('36A'); // Maharashtra Stamp Act Article Code
            $table->string('location_type'); // Urban, Rural, or Influence Zone
            $table->boolean('is_female_incentive_applied')->default(false); // 1% discount logic
            
            $table->foreignId('calculated_by')->nullable()->constrained('users');
            $table->timestamps();
            
            // Indexing for audit reports
            $table->index(['property_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stamp_duty_logs');
    }
};