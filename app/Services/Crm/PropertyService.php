<?php declare(strict_types=1);

namespace App\Services\Crm;

use App\Services\BaseService;
use App\Repositories\Crm\PropertyRepository;

class PropertyService extends BaseService
{
    public function __construct(PropertyRepository $repo)
    {
        parent::__construct();
        $this->repository = $repo;
    }

    /**
     * Example: Custom logic to mark a property as 'Sold'
     */
    public function markAsSold(int $id): bool
    {
        return $this->update($id, ['status' => 'sold']);
    }
}