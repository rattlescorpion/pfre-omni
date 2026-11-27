<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('developer_directory_entity_id')->nullable()->after('name')
                ->constrained('directory_entities')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('project_type', 40)->nullable();
            $table->text('description')->nullable();
            $table->date('launch_date')->nullable();
            $table->date('proposed_completion_date')->nullable();
            $table->date('actual_completion_date')->nullable();
            $table->decimal('land_area_sqm', 14, 2)->nullable();
            $table->unsignedSmallInteger('total_buildings')->nullable();
            $table->unsignedSmallInteger('total_wings')->nullable();
            $table->unsignedInteger('total_units')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('website_url', 500)->nullable();
            $table->index(['project_type', 'status']);
        });

        Schema::create('project_phases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->string('phase_name', 190);
            $table->string('maharera_registration_number', 100)->nullable()->unique();
            $table->string('registration_status', 40)->default('not_provided');
            $table->date('registration_date')->nullable();
            $table->date('validity_start_date')->nullable();
            $table->date('validity_end_date')->nullable();
            $table->date('proposed_completion_date')->nullable();
            $table->date('actual_completion_date')->nullable();
            $table->decimal('land_area_sqm', 14, 2)->nullable();
            $table->string('status', 40)->default('planned');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['project_id', 'status']);
        });

        Schema::create('project_professionals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('project_phase_id')->nullable()->constrained('project_phases')->nullOnDelete();
            $table->foreignId('directory_entity_id')->constrained('directory_entities')->cascadeOnDelete();
            $table->foreignId('owner_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('professional_role', 40);
            $table->boolean('is_primary')->default(false);
            $table->string('engagement_status', 30)->default('active');
            $table->date('engaged_from')->nullable();
            $table->date('engaged_until')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['project_id', 'professional_role']);
            $table->index(['directory_entity_id', 'professional_role']);
            $table->unique(
                ['project_id', 'project_phase_id', 'directory_entity_id', 'professional_role'],
                'project_professional_unique'
            );
        });

        Schema::create('project_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('project_phase_id')->nullable()->constrained('project_phases')->nullOnDelete();
            $table->string('document_type', 60);
            $table->string('title', 190);
            $table->string('reference_number', 120)->nullable();
            $table->string('issuing_authority', 190)->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('storage_disk', 40)->default('private');
            $table->string('storage_path', 1000)->nullable();
            $table->string('verification_status', 30)->default('unverified');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->boolean('is_public')->default(false);
            $table->text('notes')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['project_id', 'document_type']);
            $table->index(['project_phase_id', 'verification_status']);
        });

        Schema::create('project_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('project_phase_id')->nullable()->constrained('project_phases')->nullOnDelete();
            $table->foreignId('project_document_id')->nullable()->constrained('project_documents')->nullOnDelete();
            $table->string('approval_type', 80);
            $table->string('issuing_authority', 190)->nullable();
            $table->string('reference_number', 120)->nullable();
            $table->string('approval_status', 30)->default('pending');
            $table->date('application_date')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('valid_until')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['project_id', 'approval_status']);
            $table->index(['project_phase_id', 'approval_type']);
        });

        Schema::create('project_progress_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('project_phase_id')->nullable()->constrained('project_phases')->nullOnDelete();
            $table->date('period_start');
            $table->date('period_end');
            $table->date('published_on')->nullable();
            $table->decimal('completion_percentage', 5, 2)->nullable();
            $table->unsignedInteger('apartments_booked')->nullable();
            $table->unsignedInteger('plots_booked')->nullable();
            $table->unsignedInteger('garages_booked')->nullable();
            $table->json('approvals_received')->nullable();
            $table->json('approvals_pending')->nullable();
            $table->string('reported_project_status', 80)->nullable();
            $table->text('progress_summary')->nullable();
            $table->string('source_url', 1000)->nullable();
            $table->timestamp('source_checked_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['project_id', 'period_end']);
        });

        Schema::create('project_due_diligence_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('project_phase_id')->nullable()->constrained('project_phases')->nullOnDelete();
            $table->string('check_type', 60);
            $table->string('check_status', 30)->default('pending');
            $table->date('checked_on')->nullable();
            $table->string('reference_number', 120)->nullable();
            $table->text('findings')->nullable();
            $table->text('action_required')->nullable();
            $table->foreignId('evidence_document_id')->nullable()->constrained('project_documents')->nullOnDelete();
            $table->foreignId('checked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['project_id', 'check_type', 'check_status'], 'project_due_diligence_lookup');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_due_diligence_checks');
        Schema::dropIfExists('project_progress_updates');
        Schema::dropIfExists('project_approvals');
        Schema::dropIfExists('project_documents');
        Schema::dropIfExists('project_professionals');
        Schema::dropIfExists('project_phases');
        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex(['project_type', 'status']);
            $table->dropConstrainedForeignId('developer_directory_entity_id');
            $table->dropConstrainedForeignId('created_by');
            $table->dropConstrainedForeignId('updated_by');
            $table->dropColumn([
                'project_type', 'description', 'launch_date', 'proposed_completion_date',
                'actual_completion_date', 'land_area_sqm', 'total_buildings', 'total_wings',
                'total_units', 'latitude', 'longitude', 'website_url',
            ]);
        });
    }
};
