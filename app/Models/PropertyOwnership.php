<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyOwnership extends Model
{
    use SoftDeletes;

    protected $table = 'property_ownerships';

    protected $fillable = [
        'property_id',
        'directory_entity_id',
        'ownership_percentage',
        'ownership_type',
        'valid_from',
        'valid_until',
        'document_reference',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'ownership_percentage' => 'decimal:2',
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function entity(): BelongsTo
    {
        return $this->belongsTo(DirectoryEntity::class, 'directory_entity_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
