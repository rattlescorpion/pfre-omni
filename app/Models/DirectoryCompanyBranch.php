<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DirectoryCompanyBranch extends Model
{
    use SoftDeletes;

    protected $table = 'directory_company_branches';

    protected $fillable = [
        'company_entity_id', 'branch_name', 'branch_type', 'branch_code',
        'gstin', 'phone', 'email', 'is_head_office', 'status', 'notes', 'created_by',
    ];

    protected $hidden = ['gstin'];

    protected $casts = ['is_head_office' => 'boolean'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(DirectoryEntity::class, 'company_entity_id');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(DirectoryAddress::class, 'company_branch_id');
    }
}
