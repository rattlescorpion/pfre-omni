<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DirectoryAddress extends Model
{
    use SoftDeletes;

    protected $table = 'directory_addresses';

    protected $fillable = [
        'entity_id', 'company_branch_id', 'address_type', 'line_1', 'line_2',
        'building_name', 'society_name', 'wing', 'flat_unit_number', 'road',
        'landmark', 'area', 'village', 'taluka_sub_district', 'sub_division',
        'district', 'division', 'state', 'country', 'postal_code',
        'police_station', 'post_office', 'survey_number', 'hissa_number',
        'cts_number', 'railway_station', 'metro_station', 'bus_stop',
        'fire_station', 'latitude', 'longitude', 'is_primary', 'notes',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_primary' => 'boolean',
    ];

    public function entity(): BelongsTo
    {
        return $this->belongsTo(DirectoryEntity::class, 'entity_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(DirectoryCompanyBranch::class, 'company_branch_id');
    }
}
