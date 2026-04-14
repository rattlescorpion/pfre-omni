<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This table tracks physical property inspections by potential leads.
     */
    public function up(): void
    {
        Schema::create('site_visits', function (Blueprint $table) {
            $table->id();
            
            // Relationships
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('project_id')->constrained()->onDelete('cascade'); // Main building site
            $table->foreignId('conducted_by')->constrained('users'); // The agent present
            
            // Scheduling & Timing
            $table->dateTime('scheduled_at');
            $table->dateTime('actual_arrival')->nullable();
            $table->dateTime('actual_departure')->nullable();
            
            // Location Verification (For field agent accountability)
            $table->string('checkin_latitude')->nullable();
            $table->string('checkin_longitude')->nullable();
            
            // Sales Feedback
            $table->enum('status', ['Scheduled', 'Completed', 'Cancelled', 'No Show'])->default('Scheduled');
            $table->enum('client_feedback', ['Hot', 'Warm', 'Cold', 'Rejected'])->nullable();
            $table->text('remarks')->nullable(); // Specific concerns (e.g., Vastu, Price, Possession date)
            
            // Post-Visit Actions
            $table->boolean('revisit_requested')->default(false);
            $table->date('followup_date')->nullable();

            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance reporting
            $table->index(['project_id', 'status', 'scheduled_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_visits');
    }
};