<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('directory_entity_relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_entity_id')->constrained('directory_entities')->cascadeOnDelete();
            $table->foreignId('target_entity_id')->constrained('directory_entities')->cascadeOnDelete();
            $table->string('relationship_type', 80);
            $table->text('notes')->nullable();
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['source_entity_id', 'relationship_type']);
            $table->index(['target_entity_id', 'relationship_type']);
            $table->unique(
                ['source_entity_id', 'target_entity_id', 'relationship_type'],
                'directory_relationship_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('directory_entity_relationships');
    }
};
