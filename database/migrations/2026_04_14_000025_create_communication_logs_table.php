<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This table stores a history of all outbound and inbound automated communication.
     */
    public function up(): void
    {
        Schema::create('communication_logs', function (Blueprint $table) {
            $table->id();
            
            // Linking to the Lead
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            
            // Channel Info
            $table->enum('type', ['WhatsApp', 'Email', 'SMS', 'Voice Call']);
            $table->string('direction')->default('outbound'); // outbound or inbound
            
            // Content Tracking
            $table->string('subject')->nullable(); // Mainly for Emails
            $table->longText('content'); 
            $table->string('template_name')->nullable(); // For WhatsApp/Email templates used
            
            // Delivery Status Tracking (Real-time sync via Webhooks)
            $table->string('message_id')->nullable()->unique(); // API Provider's Message ID
            $table->enum('status', [
                'queued', 
                'sent', 
                'delivered', 
                'read', 
                'failed', 
                'replied'
            ])->default('queued');
            
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->text('error_message')->nullable(); // To debug API failures
            
            // Metadata
            $table->foreignId('sent_by')->nullable()->constrained('users'); // System vs Specific Agent
            $table->timestamps();
            
            // Indexing for faster CRM history lookups
            $table->index(['lead_id', 'type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communication_logs');
    }
};