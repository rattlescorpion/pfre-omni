<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProjectInventoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_and_update_a_project(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $response = $this->actingAs($user)->post(route('inventory.projects.store'), [
            'name' => 'Orca Heights',
            'maharera_reg_no' => 'P51800012345',
            'location_area' => 'Malad West',
            'address' => 'Goregaon-Mulund Link Road, Mumbai',
            'status' => 'Under Construction',
        ]);

        $this->assertDatabaseHas('projects', ['name' => 'Orca Heights', 'maharera_reg_no' => 'P51800012345']);
        $project = DB::table('projects')->where('name', 'Orca Heights')->first();
        $response->assertRedirect(route('inventory.projects.show', $project->id));

        $this->actingAs($user)->put(route('inventory.projects.update', $project->id), [
            'name' => 'Orca Heights Phase 1',
            'maharera_reg_no' => 'P51800012345',
            'location_area' => 'Malad West',
            'address' => 'Updated address, Mumbai',
            'status' => 'Ready Possession',
        ])->assertRedirect(route('inventory.projects.show', $project->id));

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Orca Heights Phase 1',
            'status' => 'Ready Possession',
        ]);
    }

    public function test_project_profile_displays_its_linked_properties(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $projectId = DB::table('projects')->insertGetId([
            'name' => 'Project Profile Test',
            'location_area' => 'Andheri West',
            'address' => 'Mumbai',
            'status' => 'Pre-Launch',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('properties')->insert([
            'project_id' => $projectId,
            'unit_number' => 'A-1001',
            'carpet_area' => 465,
            'base_price' => 9000000,
            'type' => 'Residential',
            'status' => 'Available',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($user)->get(route('inventory.projects.show', $projectId))
            ->assertOk()
            ->assertSee('Project Profile Test')
            ->assertSee('A-1001');
    }
}
