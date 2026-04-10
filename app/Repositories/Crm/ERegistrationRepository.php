<?php namespace App\Repositories\Crm;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class ERegistrationRepository extends BaseRepository 
{
    protected string $table = 'e_registrations';

    /**
     * Define mass-assignable fields for e-registrations.
     */
    protected array $fillable = [
        'lead_id',
        'property_id',
        'created_by',
        'registration_number',
        'stamp_duty_amount',
        'registration_fees',
        'total_amount',
        'payment_status',
        'document_status',
        'gstin',
        'pan_number',
        'aadhaar_number',
        'bank_reference',
        'transaction_id',
        'registration_date',
        'stamp_duty_paid',
        'registration_completed',
        'document_submitted',
        'verification_status',
        'notes',
    ];

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