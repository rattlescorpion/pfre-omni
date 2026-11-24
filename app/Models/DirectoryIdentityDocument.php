<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DirectoryIdentityDocument extends Model
{
    use SoftDeletes;

    protected $table = 'directory_identity_documents';

    protected $fillable = [
        'directory_entity_id',
        'document_type',
        'document_number_encrypted',
        'document_number_fingerprint',
        'issuing_country',
        'issuing_authority',
        'issued_at',
        'expires_at',
        'verification_status',
        'verified_at',
        'verified_by',
        'storage_key',
        'notes',
        'created_by',
    ];

    protected $hidden = [
        'document_number_encrypted',
        'document_number_fingerprint',
        'storage_key',
        'notes',
    ];

    protected $casts = [
        'document_number_encrypted' => 'encrypted',
        'issued_at' => 'date',
        'expires_at' => 'date',
        'verified_at' => 'datetime',
    ];

    public function entity(): BelongsTo
    {
        return $this->belongsTo(DirectoryEntity::class, 'directory_entity_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
