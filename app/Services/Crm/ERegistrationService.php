<?php namespace App\Services\Crm;

use App\Services\BaseService;
use App\Repositories\Crm\ERegistrationRepository;

class ERegistrationService extends BaseService 
{
    public function __construct(ERegistrationRepository $repo) {
        parent::__construct();
        $this->repository = $repo;
    }
}