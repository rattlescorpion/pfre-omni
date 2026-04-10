<?php namespace App\Services\Crm;

use App\Services\BaseService;
use App\Repositories\Crm\ERegistrationRepository;

class ERegistrationService extends BaseService 
{
    public function __construct(ERegistrationRepository $repo) {
        parent::__construct();
        $this->repository = $repo;
    }

    /**
     * Check verification status for a registration
     */
    public function checkVerificationStatus(int $id): array
    {
        $registration = $this->repository->find($id);
        
        if (!$registration) {
            return ['status' => 'not_found', 'message' => 'Registration not found'];
        }

        // Basic verification check - can be expanded
        $status = [
            'id' => $registration['id'],
            'kyc_verified' => (!empty($registration['pan']) && !empty($registration['aadhaar'])),
            'documents_complete' => !empty($registration['documents']),
            'status' => 'pending'
        ];

        if ($status['kyc_verified'] && $status['documents_complete']) {
            $status['status'] = 'verified';
        }

        return $status;
    }
}