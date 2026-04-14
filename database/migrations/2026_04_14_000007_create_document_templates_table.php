<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('document_templates', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // e.g., "Standard Leave & License Agreement"
            $table->string('slug')->unique();
            $table->longText('content'); // HTML with placeholders like {{client_name}}
            $table->string('version')->default('1.0');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('document_templates');
    }
};