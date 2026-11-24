<?php

namespace Tests\Feature;

use App\Models\DirectoryEntity;
use App\Models\Property;
use App\Models\PropertyOwnership;
use App\Models\User;
use App\Services\Directory\PropertyOwnershipService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class PropertyOwnershipServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_link_a_directory_entity_to_a_property(): void
    {
        $user = User::factory()->create();
        $property = Property::factory()->create(['created_by' => $user->id]);
        $entity = DirectoryEntity::create([
            'entity_type' => 'person',
            'display_name' => 'Test Owner',
            'owner_user_id' => $user->id,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        $link = app(PropertyOwnershipService::class)->attach($user->id, $property->id, $entity->id, [
            'ownership_percentage' => 50,
            'ownership_type' => 'owner',
        ]);

        $this->assertDatabaseHas('property_ownerships', [
            'id' => $link->id,
            'property_id' => $property->id,
            'directory_entity_id' => $entity->id,
            'ownership_percentage' => '50.00',
        ]);
    }

    public function test_user_cannot_link_another_users_directory_entity(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $property = Property::factory()->create(['created_by' => $user->id]);
        $entity = DirectoryEntity::create([
            'entity_type' => 'person',
            'display_name' => 'Private Owner',
            'owner_user_id' => $other->id,
            'created_by' => $other->id,
            'updated_by' => $other->id,
        ]);

        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);

        app(PropertyOwnershipService::class)->attach($user->id, $property->id, $entity->id, []);
    }

    public function test_ownership_percentage_cannot_exceed_one_hundred(): void
    {
        $user = User::factory()->create();
        $property = Property::factory()->create(['created_by' => $user->id]);
        $entity = DirectoryEntity::create([
            'entity_type' => 'person',
            'display_name' => 'Test Owner',
            'owner_user_id' => $user->id,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        $this->expectException(ValidationException::class);

        app(PropertyOwnershipService::class)->attach($user->id, $property->id, $entity->id, [
            'ownership_percentage' => 100.01,
        ]);
    }
}
