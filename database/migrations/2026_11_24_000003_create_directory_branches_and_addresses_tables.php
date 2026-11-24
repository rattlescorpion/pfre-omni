<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('directory_company_branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_entity_id')->constrained('directory_entities')->cascadeOnDelete();
            $table->string('branch_name', 190);
            $table->string('branch_type', 60)->default('office');
            $table->string('branch_code', 80)->nullable();
            $table->string('gstin', 15)->nullable();
            $table->string('phone', 32)->nullable();
            $table->string('email', 254)->nullable();
            $table->boolean('is_head_office')->default(false);
            $table->string('status', 30)->default('active');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['company_entity_id', 'status']);
            $table->unique(['company_entity_id', 'branch_code'], 'directory_company_branch_code_unique');
        });

        Schema::create('directory_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entity_id')->nullable()->constrained('directory_entities')->cascadeOnDelete();
            $table->foreignId('company_branch_id')->nullable()->constrained('directory_company_branches')->cascadeOnDelete();
            $table->string('address_type', 50)->default('primary');
            $table->string('line_1', 190)->nullable();
            $table->string('line_2', 190)->nullable();
            $table->string('building_name', 190)->nullable();
            $table->string('society_name', 190)->nullable();
            $table->string('wing', 80)->nullable();
            $table->string('flat_unit_number', 80)->nullable();
            $table->string('road', 190)->nullable();
            $table->string('landmark', 190)->nullable();
            $table->string('area', 190)->nullable();
            $table->string('village', 190)->nullable();
            $table->string('taluka_sub_district', 190)->nullable();
            $table->string('sub_division', 190)->nullable();
            $table->string('district', 190)->nullable();
            $table->string('division', 190)->nullable();
            $table->string('state', 190)->nullable();
            $table->string('country', 2)->default('IN');
            $table->string('postal_code', 12)->nullable();
            $table->string('police_station', 190)->nullable();
            $table->string('post_office', 190)->nullable();
            $table->string('survey_number', 100)->nullable();
            $table->string('hissa_number', 100)->nullable();
            $table->string('cts_number', 100)->nullable();
            $table->string('railway_station', 190)->nullable();
            $table->string('metro_station', 190)->nullable();
            $table->string('bus_stop', 190)->nullable();
            $table->string('fire_station', 190)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('is_primary')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['entity_id', 'address_type']);
            $table->index(['company_branch_id', 'address_type']);
            $table->index(['postal_code', 'country']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('directory_addresses');
        Schema::dropIfExists('directory_company_branches');
    }
};
