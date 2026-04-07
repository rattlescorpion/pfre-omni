<?php namespace App\Repositories\Crm;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class ERegistrationRepository extends BaseRepository 
{
    protected string $table = 'e_registrations';

    public function getAllWithLinks()
    {
        return DB::table($this->table)
            ->leftJoin('leads', 'e_registrations.lead_id', '=', 'leads.id')
            ->leftJoin('properties', 'e_registrations.property_id', '=', 'properties.id')
            ->leftJoin('employees', 'e_registrations.created_by', '=', 'employees.id')
            ->select(
                'e_registrations.*',
                'leads.full_name as lead_name',
                'properties.title as property_title',
                'employees.name as agent_name'
            )
            ->orderBy('e_registrations.created_at', 'desc')
            ->get();
    }
}