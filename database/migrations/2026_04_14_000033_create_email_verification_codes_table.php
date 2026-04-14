<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This table stores One-Time Passcodes (OTP) for secure customer portal access.
     */
    public function up(): void
    {
        Schema::create('email_verification_codes', function (Blueprint $table) {
            $table->id();
            
            // The email associated with the Lead or Client
            $table->string('email')->index();
            
            // The hashed or plain code (Depending on security preference)
            $table->string('code'); 
            
            // Purpose of the code
            $table->enum('purpose', [
                'portal_login', 
                'document_download', 
                'e_sign_verification',
                'email_update'
            ])->default('portal_login');

            // Metadata for security auditing
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            
            // Expiry and Usage
            $table->boolean('is_used')->default(false);
            $table->timestamp('expires_at');
            $table->timestamps();

            // Index to quickly find valid codes for an email
            $table->index(['email', 'code', 'expires_at', 'is_used'], 'valid_code_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_verification_codes');
    }
};