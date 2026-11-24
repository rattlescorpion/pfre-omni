<?php

declare(strict_types=1);

namespace App\Services\Directory;

use App\Models\DirectoryEntity;
use App\Models\Property;
use App\Models\PropertyOwnership;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PropertyOwnershipService
{
    /**
     * Attach an entity to a property only when the user can manage both records.
     * The legacy inventory model has no confirmed tenant_id; ownership is
     * restricted to the creator or assigned agent until a formal policy exists.
     */
    public function attach(int $userId, int $propertyId, int $entityId, array $attributes): PropertyOwnership
    {
        return DB::transaction(function () use ($userId, $propertyId, $entityId, $attributes) {
            $property = Property::query()->lockForUpdate()->findOrFail($propertyId);
            $entity = DirectoryEntity::query()->lockForUpdate()->findOrFail($entityId);

            if (!in_array($userId, array_filter([
                $property->created_by,
                $property->assigned_to,
            ], fn ($value) => $value !== null), true)) {
                abort(404);
            }

            if ((int) $entity->owner_user_id !== $userId) {
                abort(404);
            }

            $percentage = $attributes['ownership_percentage'] ?? null;
            if ($percentage !== null && ((float) $percentage < 0.01 || (float) $percentage > 100)) {
                throw ValidationException::withMessages([
                    'ownership_percentage' => 'Ownership percentage must be greater than 0 and no more than 100.',
                ]);
            }

            $from = $attributes['valid_from'] ?? null;
            $until = $attributes['valid_until'] ?? null;
            if ($from && $until && $until < $from) {
                throw ValidationException::withMessages([
                    'valid_until' => 'The end date must be on or after the start date.',
                ]);
            }

            return PropertyOwnership::create([
                'property_id' => $property->id,
                'directory_entity_id' => $entity->id,
                'ownership_percentage' => $percentage,
                'ownership_type' => $attributes['ownership_type'] ?? 'owner',
                'valid_from' => $from,
                'valid_until' => $until,
                'document_reference' => $attributes['document_reference'] ?? null,
                'notes' => $attributes['notes'] ?? null,
                'created_by' => $userId,
            ]);
        });
    }

    public function archive(int $userId, PropertyOwnership $ownership): void
    {
        $property = Property::query()->findOrFail($ownership->property_id);
        $entity = DirectoryEntity::query()->findOrFail($ownership->directory_entity_id);

        if (!in_array($userId, array_filter([
            $property->created_by,
            $property->assigned_to,
        ], fn ($value) => $value !== null), true) || (int) $entity->owner_user_id !== $userId) {
            abort(404);
        }

        $ownership->delete();
    }
}
