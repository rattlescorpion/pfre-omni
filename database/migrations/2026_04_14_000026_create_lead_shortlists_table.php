<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This table acts as a 'Shopping Cart' for Real Estate leads.
     */
    public function up(): void
    {
        Schema::create('lead_shortlists', function (Blueprint $table) {
            $table->id();
            
            // The Lead (Doctor, Lawyer, etc.)
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            
            // The Property (Specific Flat or Commercial Unit)
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            
            // Metadata for Sales Analysis
            $table->integer('interest_level')->default(3); // 1-5 Scale
            $table->text('agent_notes')->nullable(); // Why did they like this unit?
            
            // Status Tracking
            $table->enum('status', [
                'active', 
                'rejected_by_client', 
                'unavailable', // If the unit gets sold to someone else
                'converted'    // If this unit becomes the final deal
            ])->default('active');

            $table->timestamps();

            // Prevent duplicate shortlisting of the same property for the same lead
            $table->unique(['lead_id', 'property_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_shortlists');
    }
};