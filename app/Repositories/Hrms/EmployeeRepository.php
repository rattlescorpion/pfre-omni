<?php declare(strict_types=1);

namespace App\Repositories\Hrms;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class EmployeeRepository extends BaseRepository
{
    protected string $table = 'employees';

    /**
     * Specific query to filter employees by department and status.
     */
    public function getByDepartment(string $dept, string $status = 'active')
    {
        return DB::table($this->table)
            ->where('department', $dept)
            ->where('status', $status)
            ->get();
    }
}