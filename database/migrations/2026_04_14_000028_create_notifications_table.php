<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This table stores in-app alerts for agents and management.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            // Using UUID for the ID is standard for Laravel's Notification system
            $table->uuid('id')->primary();
            
            // The type of notification (e.g., App\Notifications\ReraDeadlineAlert)
            $table->string('type');
            
            // Polymorphic relation to the user being notified
            $table->morphs('notifiable'); 
            
            // JSON payload containing the message, link, and priority
            $table->json('data');
            
            // Specific for Real Estate urgency
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            
            // Categorization for filtering on the dashboard
            $table->string('category')->nullable(); // e.g., 'Lead', 'RERA', 'Finance', 'System'
            
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            // Index for faster unread count queries
            $table->index(['notifiable_id', 'notifiable_type', 'read_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};