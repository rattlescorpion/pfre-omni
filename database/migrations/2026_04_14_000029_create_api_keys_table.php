<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This table manages credentials for external portal integrations and 
     * third-party developer access to the PFRE-Omni ecosystem.
     */
    public function up(): void
    {
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            
            // Identifying the Portal or Integration
            $table->string('name'); // e.g., 'MagicBricks Webhook', 'Housing.com API'
            $table->string('provider')->nullable(); // e.g., 'MagicBricks', 'Facebook', '99acres'
            
            // The actual Key/Secret (Encrypted for safety)
            $table->string('key', 64)->unique();
            $table->string('secret')->nullable(); // Optional secondary secret
            
            // Access Control
            $table->enum('access_level', ['read_only', 'write_only', 'full_access'])->default('read_only');
            $table->boolean('is_active')->default(true);
            
            // Rate Limiting (Protects your server from too many hits from a portal)
            $table->integer('rate_limit_per_minute')->default(60);
            
            // Usage Tracking
            $table->timestamp('last_used_at')->nullable();
            $table->string('last_ip_address', 45)->nullable();
            
            // Expiry for security rotation
            $table->timestamp('expires_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index for fast authentication lookups
            $table->index(['key', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_keys');
    }
};