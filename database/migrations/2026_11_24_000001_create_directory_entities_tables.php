<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('directory_entities', function (Blueprint $table) {
            $table->id();
            $table->enum('entity_type', ['person', 'company'])->index();
            $table->string('display_name');
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('legal_name')->nullable();
            $table->string('trading_name')->nullable();
            $table->string('organisation_type')->nullable();
            $table->string('pan', 10)->nullable();
            $table->string('gstin', 15)->nullable();
            $table->string('cin', 21)->nullable();
            $table->string('website')->nullable();
            $table->string('status')->default('active')->index();
            $table->text('notes')->nullable();
            $table->foreignId('owner_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['owner_user_id', 'entity_type', 'status']);
        });

        Schema::create('directory_communication_points', function (Blueprint $table) {
            $table->id();
            $table->enum('channel_type', ['mobile', 'phone', 'email']);
            $table->string('value', 254);
            $table->string('normalised_value', 254)->index();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
            $table->unique(['channel_type', 'normalised_value'], 'directory_channel_value_unique');
        });

        Schema::create('directory_communication_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entity_id')->constrained('directory_entities')->cascadeOnDelete();
            $table->foreignId('communication_point_id')->constrained('directory_communication_points')->cascadeOnDelete();
            $table->string('purpose')->default('general');
            $table->boolean('is_primary')->default(false);
            $table->string('consent_status')->default('unknown');
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->timestamps();
            $table->unique(['entity_id', 'communication_point_id', 'purpose'], 'directory_assignment_unique');
            $table->index(['entity_id', 'is_primary']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('directory_communication_assignments');
        Schema::dropIfExists('directory_communication_points');
        Schema::dropIfExists('directory_entities');
    }
};
