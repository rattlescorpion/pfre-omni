<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->decimal('proposed_fsi', 10, 4)->nullable();
            $table->decimal('consumed_fsi', 10, 4)->nullable();
            $table->json('development_facilities')->nullable();
            $table->text('boundary_description')->nullable();
        });

        Schema::table('properties', function (Blueprint $table) {
            $table->string('building_name', 190)->nullable();
            $table->string('society_name', 190)->nullable();
            $table->string('wing', 80)->nullable();
            $table->string('floor_number', 40)->nullable();
            $table->string('configuration', 80)->nullable();
            $table->decimal('built_up_area', 10, 2)->nullable();
            $table->decimal('super_built_up_area', 10, 2)->nullable();
            $table->decimal('exclusive_balcony_area', 10, 2)->nullable();
            $table->decimal('exclusive_verandah_area', 10, 2)->nullable();
            $table->decimal('exclusive_open_terrace_area', 10, 2)->nullable();
            $table->decimal('garage_area', 10, 2)->nullable();
            $table->unsignedSmallInteger('garage_count')->nullable();
        });

        Schema::create('project_land_parcels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('project_phase_id')->nullable()->constrained('project_phases')->nullOnDelete();
            $table->string('parcel_label', 190)->nullable();
            $table->string('survey_number', 120)->nullable();
            $table->string('hissa_number', 120)->nullable();
            $table->string('cts_number', 120)->nullable();
            $table->decimal('land_area_sqm', 14, 2)->nullable();
            $table->string('land_type', 80)->nullable();
            $table->string('title_status', 40)->default('not_reviewed');
            $table->text('encumbrance_details')->nullable();
            $table->text('boundary_description')->nullable();
            $table->decimal('north_latitude', 10, 7)->nullable();
            $table->decimal('north_longitude', 10, 7)->nullable();
            $table->decimal('south_latitude', 10, 7)->nullable();
            $table->decimal('south_longitude', 10, 7)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['project_id', 'survey_number', 'cts_number']);
        });

        Schema::create('directory_company_project_track_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_entity_id')->constrained('directory_entities')->cascadeOnDelete();
            $table->string('project_name', 190);
            $table->string('location', 190)->nullable();
            $table->string('project_type', 60)->nullable();
            $table->string('land_type', 80)->nullable();
            $table->string('reported_status', 60)->nullable();
            $table->date('launch_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->text('delay_details')->nullable();
            $table->text('pending_cases')->nullable();
            $table->text('pending_payments')->nullable();
            $table->string('source_url', 1000)->nullable();
            $table->date('source_checked_on')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['company_entity_id', 'reported_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('directory_company_project_track_records');
        Schema::dropIfExists('project_land_parcels');
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'building_name', 'society_name', 'wing', 'floor_number', 'configuration',
                'built_up_area', 'super_built_up_area', 'exclusive_balcony_area',
                'exclusive_verandah_area', 'exclusive_open_terrace_area', 'garage_area', 'garage_count',
            ]);
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['proposed_fsi', 'consumed_fsi', 'development_facilities', 'boundary_description']);
        });
    }
};
