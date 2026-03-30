<?php declare(strict_types=1);

namespace App\Repositories\Crm;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class LeadRepository extends BaseRepository
{
    protected string $table = 'leads';

    /**
     * Keep only lead-specific logic here.
     */
    public function getNextAgentInQueue(): ?int
    {
        $agent = DB::table('employees')
            ->where('status', 'active')
            ->where('department', 'sales')
            ->orderBy('last_lead_assigned_at', 'asc')
            ->first();

        return $agent ? (int)$agent->id : null;
    }

    public function findDuplicate(string $phone): ?array
    {
        $lead = DB::table($this->table)->where('phone', $phone)->first();
        return $lead ? (array) $lead : null;
    }
}