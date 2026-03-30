<?php declare(strict_types=1);

namespace App\Services\Crm;

use App\Services\BaseService;
use App\Repositories\Crm\TaskRepository;

class TaskService extends BaseService
{
    /**
     * Dependency Injection: We tell Laravel to give us the TaskRepository
     * whenever this service is started.
     */
    public function __construct(TaskRepository $repo)
    {
        $this->repository = $repo;
    }

    /**
     * Custom Business Logic: Get only tasks due today.
     * (This is an example of adding logic beyond the BaseService)
     */
    public function getTodaysTasks()
    {
        return $this->repository->getUpcoming(now()->toDateString());
    }
}