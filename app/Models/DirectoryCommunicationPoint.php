<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DirectoryCommunicationPoint extends Model
{
    protected $table = 'directory_communication_points';

    protected $fillable = ['channel_type', 'value', 'normalised_value', 'is_verified'];

    protected $hidden = ['normalised_value'];

    public function entities(): BelongsToMany
    {
        return $this->belongsToMany(
            DirectoryEntity::class,
            'directory_communication_assignments',
            'communication_point_id',
            'entity_id'
        )->withPivot(['purpose', 'is_primary', 'consent_status', 'verified_at', 'valid_from', 'valid_until'])
         ->withTimestamps();
    }
}
