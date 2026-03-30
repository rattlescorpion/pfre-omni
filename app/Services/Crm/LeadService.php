<?php declare(strict_types=1);

namespace App\Services\Crm;

use App\Services\BaseService;
use App\Repositories\Crm\LeadRepository;
use App\Events\Crm\LeadCreatedEvent;

class LeadService extends BaseService
{
    public function __construct(LeadRepository $repo)
    {
        parent::__construct();
        $this->repository = $repo;
    }

    /**
     * We override the create method to add our Lead-specific 
     * automation (Scoring & Assignment).
     */
    public function create(array $data, ?int $userId = null): array
    {
        // 1. Use the BaseService to create the record & audit it
        $lead = parent::create($data, $userId);

        // 2. Run Lead-specific logic (Automation)
        $this->assignToAgent($lead['id']);
        
        // 3. Fire the Event
        LeadCreatedEvent::dispatch($lead);

        return $lead;
    }

    protected function assignToAgent(int $leadId): void
    {
        $agentId = $this->repository->getNextAgentInQueue();
        if ($agentId) {
            $this->update($leadId, [
                'assigned_to' => $agentId,
                'assigned_at' => now()
            ]);
        }
    }
}