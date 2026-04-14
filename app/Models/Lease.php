<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Lease extends Model
{
    use HasFactory;

    protected $fillable = [
        'lease_no', 'property_id', 'tenant_id', 'owner_client_id', 'deal_id',
        'lease_type', 'start_date', 'end_date', 'lock_in_months',
        'monthly_rent', 'security_deposit', 'security_deposit_received', 'maintenance_pm',
        'escalation_pct', 'escalation_after_months',
        'billing_date', 'grace_days', 'late_fee_pct',
        'agreement_date', 'agreement_path', 'sro_registered', 'sro_doc_no', 'sro_registered_date',
        'notice_period_days', 'renewal_status',
        'status', 'termination_date', 'termination_reason',
        'notes', 'created_by',
    ];

    protected $casts = [
        'monthly_rent'               => 'decimal:2',
        'security_deposit'           => 'decimal:2',
        'security_deposit_received'  => 'decimal:2',
        'maintenance_pm'             => 'decimal:2',
        'escalation_pct'             => 'decimal:2',
        'late_fee_pct'               => 'decimal:2',
        'sro_registered'             => 'boolean',
        'start_date'                 => 'date',
        'end_date'                   => 'date',
        'agreement_date'             => 'date',
        'sro_registered_date'        => 'date',
        'termination_date'           => 'date',
    ];

    // ── Relationships ──────────────────────────────────────
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function rentCollections(): HasMany
    {
        return $this->hasMany(RentCollection::class)->latest('billing_year')->latest('billing_month');
    }

    public function maintenanceRequests(): HasMany
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(Inspection::class);
    }

    // ── Helpers ────────────────────────────────────────────
    public function isExpiringSoon(int $days = 30): bool
    {
        return $this->status === 'active'
            && $this->end_date->isBetween(now(), now()->addDays($days));
    }

    public function daysUntilExpiry(): int
    {
        return (int) now()->diffInDays($this->end_date, false);
    }

    public function getRentWithEscalation(): string
    {
        if (!$this->escalation_pct || !$this->escalation_after_months) {
            return (string) $this->monthly_rent;
        }
        $monthsElapsed = (int) $this->start_date->diffInMonths(now());
        $periods       = (int) floor($monthsElapsed / $this->escalation_after_months);
        $rate          = (string) $this->monthly_rent;
        for ($i = 0; $i < $periods; $i++) {
            $rate = bcmul($rate, bcadd('1', bcdiv((string)$this->escalation_pct, '100', 6), 6), 2);
        }
        return $rate;
    }
}