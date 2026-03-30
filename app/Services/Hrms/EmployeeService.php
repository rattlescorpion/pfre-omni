<?php declare(strict_types=1);

namespace App\Services\Hrms;

use App\Services\BaseService;
use App\Repositories\Hrms\EmployeeRepository;

class EmployeeService extends BaseService
{
    /**
     * Dependency Injection: Laravel automatically provides the EmployeeRepository.
     */
    public function __construct(EmployeeRepository $repo)
    {
        // We must call parent::__construct() to initialize the AuditService from the Base
        parent::__construct();
        $this->repository = $repo;
    }

    /**
     * Custom Logic: Get only active sales agents for the Lead Rotation.
     */
    public function getActiveSalesAgents()
    {
        return $this->repository->getByDepartment('sales', 'active');
    }

    /**
     * Custom Logic: Mark an employee as 'On Leave' or 'Resigned'.
     */
    public function updateStatus(int $id, string $status): bool
    {
        return $this->update($id, ['status' => $status]);
    }
}