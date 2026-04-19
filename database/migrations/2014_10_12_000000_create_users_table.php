<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('avatar')->nullable();
            
            // Security & 2FA
            $table->text('two_factor_secret')->nullable();
            $table->boolean('two_factor_enabled')->default(false);
            $table->boolean('two_factor_confirmed')->default(false);
            $table->text('backup_codes')->nullable();
            
            // Status & Locks
            $table->string('status')->default('active'); // active, locked, suspended
            $table->integer('failed_logins')->default(0);
            $table->timestamp('locked_until')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            
            // User Preferences
            $table->string('language')->default('en');
            $table->string('timezone')->default('Asia/Kolkata');
            $table->string('date_format')->default('Y-m-d');
            $table->string('default_dashboard')->default('general');
            $table->json('notification_preferences')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes(); // This line fixes the exact error you received
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};