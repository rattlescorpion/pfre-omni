<?php

namespace Tests\Feature;

use App\Models\DirectoryEntity;
use App\Models\PropertyOwnership;
use App\Models\User;
use App\Services\Directory\PropertyOwnershipService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class PropertyOwnershipServiceTest extends TestCase
{
    use RefreshDatabase;

    private function createPropertyFor(User $user): int
    {
        $projectId = DB::table('projects')->insertGetId([
            'name' => 'Test Project',
            'location_area' => 'Malad West',
            'address' => 'Mumbai',
            'status' => 'Ready Possession',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return DB::table('properties')->insertGetId([
            'project_id' => $projectId,
            'unit_number' => 'A-101',
            'carpet_area' => 500,
            'base_price' => 7500000,
            'type' => 'Residential',
            'status' => 'Available',
            'created_by' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createEntityFor(User $user, string $name = 'Test Owner'): DirectoryEntity
    {
        return DirectoryEntity::create([
            'entity_type' => 'person',
            'display_name' => $name,
            'owner_user_id' => $user->id,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);
    }

    public function test_owner_can_link_a_directory_entity_to_a_property(): void
    {
        $user = User::factory()->create();
        $propertyId = $this->createPropertyFor($user);
        $entity = $this->createEntityFor($user);

        $link = app(PropertyOwnershipService::class)->attach($user->id, $propertyId, $entity->id, [
            'ownership_percentage' => 50,
            'ownership_type' => 'owner',
        ]);

        $this->assertDatabaseHas('property_ownerships', [
            'id' => $link->id,
            'property_id' => $propertyId,
            'directory_entity_id' => $entity->id,
            'ownership_percentage' => '50.00',
        ]);
    }

    public function test_user_cannot_link_another_users_directory_entity(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $propertyId = $this->createPropertyFor($user);
        $entity = $this->createEntityFor($other, 'Private Owner');

        $this->expectException(HttpException::class);

        app(PropertyOwnershipService::class)->attach($user->id, $propertyId, $entity->id, []);
    }

    public function test_ownership_percentage_cannot_exceed_one_hundred(): void
    {
        $user = User::factory()->create();
        $propertyId = $this->createPropertyFor($user);
        $entity = $this->createEntityFor($user);

        $this->expectException(ValidationException::class);

        app(PropertyOwnershipService::class)->attach($user->id, $propertyId, $entity->id, [
            'ownership_percentage' => 100.01,
        ]);
    }

    public function test_owner_can_archive_an_ownership_link(): void
    {
        $user = User::factory()->create();
        $propertyId = $this->createPropertyFor($user);
        $entity = $this->createEntityFor($user);
        $link = app(PropertyOwnershipService::class)->attach($user->id, $propertyId, $entity->id, []);

        app(PropertyOwnershipService::class)->archive($user->id, $link);

        $this->assertSoftDeleted('property_ownerships', ['id' => $link->id]);
    }
}
