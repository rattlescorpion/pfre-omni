<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('directory_identity_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('directory_entity_id')->constrained('directory_entities')->cascadeOnDelete();
            $table->string('document_type', 60);
            $table->text('document_number_encrypted')->nullable();
            $table->string('document_number_fingerprint', 64)->nullable()->index();
            $table->string('issuing_country', 2)->default('IN');
            $table->string('issuing_authority', 190)->nullable();
            $table->date('issued_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->string('verification_status', 30)->default('unverified');
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('storage_key')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['directory_entity_id', 'document_type', 'verification_status'], 'directory_identity_docs_entity_type_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('directory_identity_documents');
    }
};
