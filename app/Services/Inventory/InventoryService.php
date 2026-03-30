<?php declare(strict_types=1);

namespace App\Services\Inventory;

use App\Services\BaseService;
use App\Repositories\Inventory\InventoryRepository;

class InventoryService extends BaseService
{
    public function __construct(InventoryRepository $repo)
    {
        $this->repository = $repo;
    }

    /**
     * Custom Business Logic: Check if any items are running low on stock.
     */
    public function getLowStockAlerts()
    {
        return $this->repository->getBelowReorderLevel();
    }
}