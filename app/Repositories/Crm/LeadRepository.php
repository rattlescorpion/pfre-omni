<?php declare(strict_types=1);

namespace App\Repositories\Crm;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LeadRepository
{
    /**
     * Updates an existing lead record.
     */
    public function update($id, array $data): bool
    {
        // If the service sends the whole lead array, we grab just the 'id'
        $actualId = is_array($id) ? ($id['id'] ?? 0) : $id;
        
        return (bool) DB::table('leads')
            ->where('id', $actualId)
            ->update(array_merge($data, ['updated_at' => now()]));
    }

    public function find($id): ?array
    {
        $actualId = is_array($id) ? ($id['id'] ?? 0) : $id;
        $lead = DB::table('leads')->find($actualId);
        return $lead ? (array) $lead : null;
    }

    public function create(array $data): array
    {
        $leadNo = 'LD-' . strtoupper(Str::random(6));

        $id = DB::table('leads')->insertGetId([
            'uuid'       => Str::uuid()->toString(),
            'lead_no'    => $leadNo,
            'name'       => $data['name'],
            'phone'      => $data['phone'],
            'intent'     => $data['intent'] ?? 'buy',
            'stage'      => $data['stage'] ?? 'new',
            'assigned_to'=> $data['assigned_to'] ?? null,
            'created_by' => $data['created_by'] ?? 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return (array) $this->find($id);
    }

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
        $lead = DB::table('leads')->where('phone', $phone)->first();
        return $lead ? (array) $lead : null;
    }

    public function countThisYear($userId): int
    {
        return DB::table('leads')
            ->where('created_by', (int)$userId)
            ->whereYear('created_at', date('Y'))
            ->count();
    }
}