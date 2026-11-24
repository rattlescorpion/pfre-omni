<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DirectoryEntity extends Model
{
    use SoftDeletes;

    protected $table = 'directory_entities';

    protected $fillable = [
        'entity_type', 'display_name', 'first_name', 'middle_name', 'last_name',
        'legal_name', 'trading_name', 'organisation_type', 'pan', 'gstin', 'cin',
        'website', 'status', 'notes', 'owner_user_id', 'created_by', 'updated_by',
    ];

    protected $hidden = ['pan', 'gstin', 'cin'];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function communicationPoints(): BelongsToMany
    {
        return $this->belongsToMany(
            DirectoryCommunicationPoint::class,
            'directory_communication_assignments',
            'entity_id',
            'communication_point_id'
        )->withPivot(['purpose', 'is_primary', 'consent_status', 'verified_at', 'valid_from', 'valid_until'])
         ->withTimestamps();
    }

    public function branches(): HasMany
    {
        return $this->hasMany(DirectoryCompanyBranch::class, 'company_entity_id');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(DirectoryAddress::class, 'entity_id');
    }

    public function scopeOwnedBy($query, int $userId)
    {
        return $query->where('owner_user_id', $userId);
    }
}
