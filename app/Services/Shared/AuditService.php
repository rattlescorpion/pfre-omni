<?php declare(strict_types=1);

namespace App\Services\Shared;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class AuditService
{
    public function log(
        string $action, 
        string $entityType, 
        $entityId, 
        array $oldValues = [], 
        array $newValues = [], 
        ?int $userId = null
    ): void {
        $actualId = is_array($entityId) ? ($entityId['id'] ?? 0) : $entityId;

        DB::table('audit_log')->insert([
            'user_id'     => $userId ?? 1,
            'action'      => $action,
            'entity_type' => $entityType,
            'entity_id'   => $actualId,
            // CRITICAL FIX: Convert arrays to JSON strings
            'old_values'  => json_encode($oldValues),
            'new_values'  => json_encode($newValues), 
            'ip_address'  => Request::ip(),
            'user_agent'  => Request::userAgent(),
            'created_at'  => now(),
        ]);
    }
}