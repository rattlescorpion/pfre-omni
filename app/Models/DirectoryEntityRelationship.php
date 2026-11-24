<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DirectoryEntityRelationship extends Model
{
    use SoftDeletes;

    protected $table = 'directory_entity_relationships';

    protected $fillable = [
        'source_entity_id', 'target_entity_id', 'relationship_type',
        'notes', 'valid_from', 'valid_until', 'created_by',
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    public function source(): BelongsTo
    {
        return $this->belongsTo(DirectoryEntity::class, 'source_entity_id');
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(DirectoryEntity::class, 'target_entity_id');
    }
}
