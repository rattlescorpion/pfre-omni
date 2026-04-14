<?php
declare(strict_types=1);

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    /**
     * We extend Spatie's Role model so we can use all of their powerful 
     * permission methods, while also allowing mass assignment for our 
     * custom PFRE-Omni database columns.
     */
    protected $fillable = [
        'name',
        'guard_name',
        'display_name',
        'is_system_role',
        'description',
    ];

    protected $casts = [
        'is_system_role' => 'boolean',
    ];
}