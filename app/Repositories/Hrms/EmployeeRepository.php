<?php declare(strict_types=1);

namespace App\Repositories\Hrms;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class EmployeeRepository extends BaseRepository
{
    protected string $table = 'employees';

    /**
     * Define mass-assignable fields for employees.
     */
    protected array $fillable = [
        'name',
        'email',
        'phone',
        'employee_id',
        'department',
        'designation',
        'joining_date',
        'salary',
        'status',
        'manager_id',
        'address',
        'emergency_contact',
        'date_of_birth',
        'gender',
        'qualification',
        'experience_years',
    ];

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