<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PropertyInventoryTest extends TestCase
{
    use RefreshDatabase;

    private function createProject(): int
    {
        return DB::table('projects')->insertGetId([
            'name' => 'PFRE Test Project',
            'location_area' => 'Malad West',
            'address' => 'Mumbai, Maharashtra',
            'status' => 'Ready Possession',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_authenticated_user_can_add_property_to_an_existing_project(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $projectId = $this->createProject();

        $response = $this->actingAs($user)->post(route('inventory.properties.store'), [
            'project_id' => $projectId,
            'unit_number' => 'A-1204',
            'carpet_area' => 465,
            'base_price' => 8500000,
            'type' => 'Residential',
            'status' => 'Available',
        ]);

        $this->assertDatabaseHas('properties', [
            'project_id' => $projectId,
            'unit_number' => 'A-1204',
            'carpet_area' => '465.00',
            'base_price' => '8500000.00',
            'type' => 'Residential',
            'status' => 'Available',
        ]);
        $created = DB::table('properties')->where('project_id', $projectId)->where('unit_number', 'A-1204')->first();
        $response->assertRedirect(route('inventory.properties.show', $created->id));
    }

    public function test_property_cannot_be_created_without_a_valid_project(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $response = $this->actingAs($user)->from(route('inventory.properties.create'))
            ->post(route('inventory.properties.store'), [
                'project_id' => 999999,
                'unit_number' => 'A-1204',
                'carpet_area' => 465,
                'base_price' => 8500000,
                'type' => 'Residential',
            ]);

        $response->assertSessionHasErrors('project_id');
        $this->assertDatabaseCount('properties', 0);
    }

    public function test_property_inventory_page_reads_legacy_schema_fields(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $projectId = $this->createProject();
        DB::table('properties')->insert([
            'project_id' => $projectId,
            'unit_number' => 'B-902',
            'carpet_area' => 510,
            'base_price' => 9200000,
            'type' => 'Residential',
            'status' => 'Available',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('inventory.properties.index'))
            ->assertOk()
            ->assertSee('PFRE Test Project')
            ->assertSee('B-902')
            ->assertSee('9,200,000');
    }
}
