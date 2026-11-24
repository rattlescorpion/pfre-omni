<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('property_ownerships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->foreignId('directory_entity_id')->constrained('directory_entities')->restrictOnDelete();
            $table->decimal('ownership_percentage', 5, 2)->nullable();
            $table->string('ownership_type', 40)->default('owner');
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->string('document_reference', 190)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['property_id', 'deleted_at']);
            $table->index(['directory_entity_id', 'deleted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_ownerships');
    }
};
