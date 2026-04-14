<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This table tracks marketing efforts to calculate ROI on lead generation.
     */
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., 'Andheri West Project Launch'
            $table->string('utm_source')->nullable(); // For digital tracking
            
            // Channel categorization
            $table->enum('channel', [
                'Google Ads', 
                'Facebook', 
                'Instagram', 
                'WhatsApp Bulk', 
                'Property Portal', // MagicBricks/Housing.com
                'Newspaper', 
                'Billboard',
                'Referral'
            ]);

            // Financial Tracking
            $table->decimal('budget', 15, 2)->default(0); // Planned spend
            $table->decimal('actual_cost', 15, 2)->default(0); // Actual spend
            
            // Timeline
            $table->date('start_date');
            $table->date('end_date')->nullable();
            
            // Performance Metadata
            $table->string('target_audience')->nullable(); // e.g., 'Doctors', 'HNIs'
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes(); // Keep records for historical ROI analysis
        });

        // Add a foreign key to the leads table to link them to campaigns
        // This assumes your leads table migration is already prepared.
        if (Schema::hasTable('leads')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->foreignId('campaign_id')->nullable()->after('id')->constrained()->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('leads')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->dropConstrainedForeignId('campaign_id');
            });
        }
        Schema::dropIfExists('campaigns');
    }
};