<?php declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

abstract class BaseRepository
{
    /**
     * The name of the database table.
     * Defined in the child class (e.g., 'leads', 'properties').
     */
    protected string $table;

    /**
     * Get all records from the table.
     */
    public function getAll(): Collection
    {
        return DB::table($this->table)
            ->whereNull('deleted_at') // Assumes you use soft deletes
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Find a single record by ID.
     */
    public function find($id): ?array
    {
        $actualId = is_array($id) ? ($id['id'] ?? 0) : $id;
        $result = DB::table($this->table)->find($actualId);
        
        return $result ? (array) $result : null;
    }

    /**
     * Create a new record with a UUID.
     */
    public function create(array $data): array
    {
        // Add timestamps and UUID if they don't exist in the data array
        $data['uuid'] = $data['uuid'] ?? Str::uuid()->toString();
        $data['created_at'] = now();
        $data['updated_at'] = now();

        $id = DB::table($this->table)->insertGetId($data);

        return (array) $this->find($id);
    }

    /**
     * Update an existing record.
     */
    public function update($id, array $data): bool
    {
        $actualId = is_array($id) ? ($id['id'] ?? 0) : $id;

        return (bool) DB::table($this->table)
            ->where('id', $actualId)
            ->update(array_merge($data, ['updated_at' => now()]));
    }

    /**
     * Delete a record (Soft Delete).
     */
    public function delete($id): bool
    {
        $actualId = is_array($id) ? ($id['id'] ?? 0) : $id;

        return (bool) DB::table($this->table)
            ->where('id', $actualId)
            ->update(['deleted_at' => now()]);
    }
}