<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This table stores internal, non-customer-facing intelligence shared among agents.
     */
    public function up(): void
    {
        Schema::create('lead_notes', function (Blueprint $table) {
            $table->id();
            
            // Linking to the Lead
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            
            // The Agent/User providing the insight
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Content
            $table->text('content');
            
            // Categorization for filtering
            $table->enum('type', [
                'general', 
                'requirement', // e.g., "Wants East facing"
                'objection',   // e.g., "Price too high for this area"
                'finance',     // e.g., "Home loan already pre-approved"
                'internal'     // e.g., "Handle with care, referral from boss"
            ])->default('general');

            // Visibility/Urgency
            $table->boolean('is_pinned')->default(false); // For high-priority info
            $table->boolean('is_private')->default(false); // Only visible to the author and Proprietor
            
            $table->timestamps();
            $table->softDeletes(); // Allow recovery of accidentally deleted insights

            // Index for quick retrieval of a lead's history
            $table->index(['lead_id', 'is_pinned', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_notes');
    }
};