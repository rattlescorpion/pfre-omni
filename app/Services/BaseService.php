<?php declare(strict_types=1);

namespace App\Services;

use App\Services\Shared\AuditService;
use Illuminate\Support\Collection;

abstract class BaseService
{
    /**
     * The Repository instance assigned by the child service.
     */
    protected $repository;

    /**
     * The Audit Service for tracking history.
     */
    protected AuditService $audit;

    public function __construct()
    {
        // Automatically fetch the AuditService from Laravel's container
        $this->audit = app(AuditService::class);
    }

    /**
     * Get all records via the repository.
     */
    public function getAll(): Collection
    {
        return $this->repository->getAll();
    }

    /**
     * Find a record by ID.
     */
    public function find($id): ?array
    {
        return $this->repository->find($id);
    }

    /**
     * Create a record and automatically log the audit trail.
     */
    public function create(array $data, ?int $userId = null): array
    {
        $record = $this->repository->create($data);

        // Auto-audit: This replaces manual logging in every controller
        $this->audit->log(
            action: 'created',
            entityType: $this->getTableName(),
            entityId: $record['id'],
            newValues: $record,
            userId: $userId ?? 1
        );

        return $record;
    }

    /**
     * Update a record and log exactly what changed.
     */
    public function update($id, array $data, ?int $userId = null): bool
    {
        $oldRecord = $this->find($id);
        $updated = $this->repository->update($id, $data);

        if ($updated) {
            $newRecord = $this->find($id);
            
            $this->audit->log(
                action: 'updated',
                entityType: $this->getTableName(),
                entityId: $id,
                oldValues: $oldRecord ?? [],
                newValues: $newRecord ?? [],
                userId: $userId ?? 1
            );
        }

        return $updated;
    }

    /**
     * Internal helper to identify which table we are auditing.
     */
    protected function getTableName(): string
    {
        // We now call the method getTable() instead of the property $table
        return method_exists($this->repository, 'getTable') 
            ? $this->repository->getTable() 
            : 'system_module';
    }
}