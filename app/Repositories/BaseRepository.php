<?php declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

abstract class BaseRepository
{
    /**
     * The name of the database table.
     * Defined in child classes (e.g., 'leads', 'employees').
     */
    protected string $table;

    /**
     * Get all records from the table, ordered by newest first.
     */
    public function getAll(): Collection
    {
        return DB::table($this->table)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * Find a single record by ID. 
     * Handles both integer IDs and whole arrays passed from Services.
     */
    public function find($id): ?array
    {
        $actualId = is_array($id) ? ($id['id'] ?? 0) : $id;
        $result = DB::table($this->table)->find($actualId);
        
        return $result ? (array) $result : null;
    }

    /**
     * Create a new record with a UUID and Timestamps.
     */
    public function create(array $data): array
    {
        $sanitizedData = collect($data)->except(['_token', '_method'])->toArray();

        // 2. Add UUID and Timestamps
        $sanitizedData['uuid'] = $sanitizedData['uuid'] ?? Str::uuid()->toString();
        $sanitizedData['created_at'] = now();
        $sanitizedData['updated_at'] = now();

        $id = DB::table($this->table)->insertGetId($sanitizedData);

        return (array) $this->find($id);
    }

    /**
     * Update an existing record.
     */
    public function update($id, array $data): bool
    {
        $actualId = is_array($id) ? ($id['id'] ?? 0) : $id;

        // Filter out fields that don't belong in the database
        $sanitizedData = collect($data)->except(['_token', '_method'])->toArray();

        return (bool) DB::table($this->table)
            ->where('id', $actualId)
            ->update(array_merge($sanitizedData, ['updated_at' => now()]));
    }

    /**
     * Delete a record (Hard Delete).
     */
    public function delete($id): bool
    {
        $actualId = is_array($id) ? ($id['id'] ?? 0) : $id;

        return (bool) DB::table($this->table)
            ->where('id', $actualId)
            ->delete();
    }
}