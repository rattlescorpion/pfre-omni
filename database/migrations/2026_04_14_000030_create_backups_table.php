<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This table tracks the history and integrity of system backups stored on AWS S3.
     */
    public function up(): void
    {
        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            
            // Backup Metadata
            $table->string('filename'); // e.g., pfre_omni_prod_20260414.sql.gz
            $table->string('disk')->default('s3'); // s3, r2, or local
            $table->string('path'); // S3 Object Path
            
            // Stats
            $table->unsignedBigInteger('size_bytes'); // To monitor storage growth
            $table->string('backup_type')->default('full'); // full, incremental, or database_only
            
            // Security & Integrity
            $table->string('checksum')->nullable(); // SHA-256 to verify file integrity
            $table->boolean('is_encrypted')->default(true);
            
            // Status Tracking
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->text('error_log')->nullable(); // Capture failure reasons
            
            // Retention Management
            $table->timestamp('expires_at')->nullable(); // For auto-cleanup logic
            
            $table->timestamps();
            
            // Index for dashboard reporting
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backups');
    }
};