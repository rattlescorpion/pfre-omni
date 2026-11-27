<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class MahaReraProject360Test extends TestCase
{
    use RefreshDatabase;

    private function project(): int
    {
        return DB::table('projects')->insertGetId([
            'name' => 'Project 360 Test',
            'location_area' => 'Malad West',
            'address' => 'Mumbai, Maharashtra',
            'status' => 'Under Construction',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function directoryEntity(User $user, string $type, string $name): int
    {
        return DB::table('directory_entities')->insertGetId([
            'entity_type' => $type,
            'display_name' => $name,
            'owner_user_id' => $user->id,
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_project_can_have_separate_maharera_phase_records(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $project = $this->project();

        $this->actingAs($user)->post(route('inventory.projects.phases.store', $project), [
            'phase_name' => 'Phase 1',
            'maharera_registration_number' => 'P51800099999',
            'registration_status' => 'registered',
            'registration_date' => '2026-01-01',
            'validity_end_date' => '2028-12-31',
            'status' => 'under_construction',
        ])->assertSessionHasNoErrors()
          ->assertRedirect(route('inventory.projects.show', $project));

        $this->assertDatabaseHas('project_phases', [
            'project_id' => $project,
            'phase_name' => 'Phase 1',
            'maharera_registration_number' => 'P51800099999',
            'registration_status' => 'registered',
        ]);
    }

    public function test_project_professional_links_existing_contact_or_company_without_deleting_source(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $project = $this->project();
        $contact = $this->directoryEntity($user, 'person', 'Test Engineer');
        $company = $this->directoryEntity($user, 'company', 'Test Architecture Studio');

        $this->actingAs($user)->post(route('inventory.projects.professionals.store', $project), [
            'directory_entity_id' => $contact,
            'professional_role' => 'engineer',
            'is_primary' => '1',
        ])->assertSessionHasNoErrors();

        $this->post(route('inventory.projects.professionals.store', $project), [
            'directory_entity_id' => $company,
            'professional_role' => 'architect',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('project_professionals', [
            'project_id' => $project,
            'directory_entity_id' => $contact,
            'professional_role' => 'engineer',
        ]);
        $this->assertDatabaseHas('project_professionals', [
            'project_id' => $project,
            'directory_entity_id' => $company,
            'professional_role' => 'architect',
        ]);

        $association = DB::table('project_professionals')
            ->where('project_id', $project)->where('directory_entity_id', $contact)->first();

        $this->delete(route('inventory.projects.professionals.destroy', [$project, $association->id]))
            ->assertRedirect(route('inventory.projects.show', $project));

        $this->assertSoftDeleted('project_professionals', ['id' => $association->id]);
        $this->assertDatabaseHas('directory_entities', ['id' => $contact, 'display_name' => 'Test Engineer']);
    }

    public function test_user_cannot_link_another_users_directory_entity_to_project(): void
    {
        $owner = User::factory()->create(['email_verified_at' => now()]);
        $other = User::factory()->create(['email_verified_at' => now()]);
        $project = $this->project();
        $foreignContact = $this->directoryEntity($other, 'person', 'Foreign Contact');

        $this->actingAs($owner)->post(route('inventory.projects.professionals.store', $project), [
            'directory_entity_id' => $foreignContact,
            'professional_role' => 'chartered_accountant',
        ])->assertSessionHasErrors('directory_entity_id');

        $this->assertDatabaseMissing('project_professionals', [
            'project_id' => $project,
            'directory_entity_id' => $foreignContact,
        ]);
    }

    public function test_project_document_upload_is_private_and_unverified_by_default(): void
    {
        Storage::fake('local');
        $user = User::factory()->create(['email_verified_at' => now()]);
        $project = $this->project();

        $this->actingAs($user)->post(route('inventory.projects.documents.store', $project), [
            'document_type' => 'commencement_certificate',
            'title' => 'Commencement Certificate',
            'document' => UploadedFile::fake()->create('cc.pdf', 50, 'application/pdf'),
        ])->assertSessionHasNoErrors();

        $document = DB::table('project_documents')->where('project_id', $project)->first();
        $this->assertNotNull($document);
        $this->assertSame('unverified', $document->verification_status);
        $this->assertSame(0, (int) $document->is_public);
        Storage::disk('local')->assertExists($document->storage_path);
    }
}
