<?php declare(strict_types=1);

namespace App\Repositories\Crm;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class PropertyRepository extends BaseRepository
{
    protected string $table = 'properties';

    // All standard CRUD (Create, Read, Update, Delete) is now inherited from Base!
}