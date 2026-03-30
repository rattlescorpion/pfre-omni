<?php namespace App\Repositories\Inventory;

use App\Repositories\BaseRepository;

class InventoryRepository extends BaseRepository 
{
    protected string $table = 'inventory_items';
}