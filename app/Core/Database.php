<?php declare(strict_types=1);

namespace App\Core;

use Illuminate\Support\Facades\DB;

/**
 * This class acts as a bridge for the custom Database 
 * requirements in your Master Prompt.
 */
class Database
{
    public function table(string $name)
    {
        return DB::table($name);
    }

    public function beginTransaction(): void
    {
        DB::beginTransaction();
    }

    public function commit(): void
    {
        DB::commit();
    }

    public function rollBack(): void
    {
        DB::rollBack();
    }
}