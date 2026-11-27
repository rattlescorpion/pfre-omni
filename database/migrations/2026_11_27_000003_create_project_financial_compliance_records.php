<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('project_financial_compliance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('project_phase_id')->nullable()->constrained('project_phases')->nullOnDelete();
            $table->string('financial_year', 9);
            $table->string('separate_account_status', 30)->default('not_provided');
            $table->decimal('separate_account_share_percentage', 5, 2)->nullable();
            $table->decimal('withdrawal_percentage', 5, 2)->nullable();
            $table->decimal('certified_completion_percentage', 5, 2)->nullable();
            $table->foreignId('engineer_certificate_document_id')->nullable()->constrained('project_documents')->nullOnDelete();
            $table->foreignId('architect_certificate_document_id')->nullable()->constrained('project_documents')->nullOnDelete();
            $table->foreignId('chartered_accountant_certificate_document_id')->nullable()->constrained('project_documents')->nullOnDelete();
            $table->foreignId('annual_audit_document_id')->nullable()->constrained('project_documents')->nullOnDelete();
            $table->date('annual_audit_completed_on')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['project_id', 'financial_year'], 'project_financial_compliance_year');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_financial_compliance_records');
    }
};
