<?php declare(strict_types=1);

namespace App\Repositories\Crm;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class PropertyRepository extends BaseRepository
{
    protected string $table = 'properties';

    /**
     * Define mass-assignable fields for properties.
     */
    protected array $fillable = [
        'name',
        'type',
        'price',
        'address',
        'locality',
        'city',
        'state',
        'pincode',
        'area_sqft',
        'bedrooms',
        'bathrooms',
        'parking',
        'furnished',
        'status',
        'description',
        'owner_name',
        'owner_phone',
        'owner_email',
        'commission_rate',
    ];

    // All standard CRUD (Create, Read, Update, Delete) is now inherited from Base!
}