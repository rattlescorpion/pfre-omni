<?php declare(strict_types=1);
namespace App\Services\Crm;
use App\Repositories\Crm\{LeadRepository, ClientRepository};
use App\Services\Shared\{AuditService, NotificationService, CacheService};
use App\Events\Crm\{LeadCreatedEvent, LeadStageChangedEvent, LeadAssignedEvent};
use Core\{Database, Logger};
final class LeadService
{
    public function __construct(
        private readonly LeadRepository $leadRepo,
        private readonly ClientRepository $clientRepo,
        private readonly LeadScoringEngine $scorer,
        private readonly AuditService $audit,
        private readonly NotificationService $notifications,
        private readonly CacheService $cache,
        private readonly Database $db,
        private readonly Logger $logger
    ) {
    }
    public function create(array $data, int $createdBy): array
    {
        // 1. Sanitize all input
        $data = $this->sanitize($data);
        // 2. Generate lead number: PF-LEAD-YYYY-0001
        $data['lead_no'] = $this->generateLeadNo();
        // 3. Check for duplicates (same phone OR same email)
        $duplicate = $this->leadRepo->findDuplicate($data['phone'], $data['email'] ?? null);
        if ($duplicate) {
            $data['is_duplicate'] = 1;
            $data['duplicate_of'] = $duplicate['id'];
        }
        // 4. Auto-assign agent (round-robin by locality specialization)
        if (empty($data['assigned_to'])) {
            $data['assigned_to'] = $this->autoAssignAgent($data['preferred_localities'] ?? []);
            $data['assigned_at'] = now();
        }
        // 5. Create the lead
        $data['created_by'] = $createdBy;
        $leadId = $this->leadRepo->create($data);
        $lead = $this->leadRepo->find($leadId);
        // 6. Calculate initial score
        $score = $this->scorer->calculate($lead);
        $this->leadRepo->update($leadId, [
            'score' => $score['total'],
            'temperature' => $score['temperature'],
            'score_breakdown' => json_encode($score['breakdown']),
        ]);
        // 7. Fire event → listeners: SendNewLeadNotification, CheckDuplicates
        event(new LeadCreatedEvent($lead));
        // 8. Audit log
        $this->audit->log(
            'lead.created',
            'leads',
            $leadId,
            [],
            $lead,
            $createdBy
        );
        // 9. Bust cache
        $this->cache->forget("dashboard:crm_kpis");
        $this->cache->forget("leads:agent:{$data['assigned_to']}");
        return $this->leadRepo->find($leadId);
    }
    public function changeStage(int $leadId, string $newStage, array $data, int $userId): array
    {
        $lead = $this->leadRepo->findOrFail($leadId);
        $oldStage = $lead['stage'];
        if ($oldStage === $newStage)
            return $lead;
        $updateData = [
            'stage' => $newStage,
            'stage_changed_at' => now(),
        ];
        if ($newStage === 'closed_lost') {
            $updateData['lost_reason'] = $data['lost_reason'] ?? null;
        }
        $this->db->beginTransaction();
        try {
            $this->leadRepo->update($leadId, $updateData);
            // Log activity
            $this->leadRepo->addActivity($leadId, [
                'activity_type' => 'stage_change',
                'subject' => "Stage changed: {$oldStage} → {$newStage}",
                'body' => $data['note'] ?? null,
                'performed_by' => $userId,
            ]);
            // Recalculate score
            $updatedLead = $this->leadRepo->find($leadId);
            $score = $this->scorer->calculate($updatedLead);
            $this->leadRepo->update($leadId, [
                'score' => $score['total'],
                'temperature' => $score['temperature'],
                'score_breakdown' => json_encode($score['breakdown']),
            ]);
            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
        event(new LeadStageChangedEvent($updatedLead, $oldStage, $newStage));
        $this->audit->log(
            'lead.stage_changed',
            'leads',
            $leadId,
            ['stage' => $oldStage],
            ['stage' => $newStage],
            $userId
        );
        return $this->leadRepo->find($leadId);
    }
    private function autoAssignAgent(array $localities): ?int
    {
        // 1. Find agents who specialize in these localities
// 2. Among those, pick the one with fewest active leads
        return $this->leadRepo->getNextAgentInQueue($localities);
    }
    private function generateLeadNo(): string
    {
        $year = date('Y');
        $count = $this->leadRepo->countThisYear($year) + 1;
        return 'PF-LEAD-' . $year . '-' . str_pad((string) $count, 4, '0', STR_PAD_LEFT);
    }
    private function sanitize(array $data): array
    {
        $data['phone'] = preg_replace('/[^0-9+]/', '', $data['phone'] ?? '');
        $data['email'] = strtolower(trim($data['email'] ?? ''));
        $data['name'] = ucwords(strtolower(trim($data['name'] ?? '')));
        return $data;
    }
}