<?php
declare(strict_types=1);
// ════════════════════════════════════════════════════════════
// app/Models/User.php
// ════════════════════════════════════════════════════════════
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\{HasMany, BelongsToMany};

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'avatar',
        'two_factor_secret', 'two_factor_enabled', 'two_factor_confirmed', 'backup_codes',
        'status', 'failed_logins', 'locked_until', 'last_login_at', 'last_login_ip',
        'language', 'timezone', 'date_format', 'default_dashboard', 'notification_preferences',
        'email_verified_at',
    ];

    protected $hidden = [
        'password', 'remember_token', 'two_factor_secret', 'backup_codes',
    ];

    protected $casts = [
        'email_verified_at'        => 'datetime',
        'locked_until'             => 'datetime',
        'last_login_at'            => 'datetime',
        'two_factor_enabled'       => 'boolean',
        'two_factor_confirmed'     => 'boolean',
        'backup_codes'             => 'array',
        'notification_preferences' => 'array',
    ];

    // ── Relationships ──────────────────────────────────────
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'assigned_to');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')
                    ->withPivot('is_granted');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    // ── Helpers ────────────────────────────────────────────
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasPermission(string $permission): bool
    {
        // Check user-specific override first
        $userPerm = $this->permissions()->where('name', $permission)->first();
        if ($userPerm) {
            return (bool) $userPerm->pivot->is_granted;
        }
        // Then check via roles
        return $this->roles()
            ->whereHas('permissions', fn ($q) => $q->where('name', $permission))
            ->exists();
    }

    public function isLocked(): bool
    {
        if ($this->status === 'locked') {
            if ($this->locked_until && $this->locked_until->isPast()) {
                $this->update(['status' => 'active', 'failed_logins' => 0, 'locked_until' => null]);
                return false;
            }
            return true;
        }
        return false;
    }

    public function recordLogin(string $ip): void
    {
        $this->update([
            'last_login_at'   => now(),
            'last_login_ip'   => $ip,
            'failed_logins'   => 0,
            'locked_until'    => null,
        ]);
    }

    public function recordFailedLogin(): void
    {
        $maxAttempts = (int) config('auth.max_attempts', 5);
        $newCount    = $this->failed_logins + 1;
        $updates     = ['failed_logins' => $newCount];

        if ($newCount >= $maxAttempts) {
            $updates['status']       = 'locked';
            $updates['locked_until'] = now()->addMinutes(15);
        }

        $this->update($updates);
    }
}

// ════════════════════════════════════════════════════════════
// app/Models/Lead.php
// ════════════════════════════════════════════════════════════
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne};

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lead_no', 'name', 'phone', 'alt_phone', 'email', 'whatsapp_no',
        'source_id', 'sub_source', 'utm_source', 'utm_medium', 'utm_campaign', 'portal_lead_id',
        'intent', 'property_type', 'config',
        'budget_min', 'budget_max',
        'preferred_localities', 'preferred_floor', 'facing',
        'requires_loan', 'loan_amount',
        'score', 'temperature', 'score_breakdown',
        'stage', 'stage_changed_at', 'lost_reason',
        'assigned_to', 'assigned_at',
        'last_activity_at', 'last_activity_type', 'total_activities',
        'next_followup_at', 'next_followup_type',
        'is_duplicate', 'duplicate_of', 'deal_id',
        'notes', 'tags', 'created_by',
    ];

    protected $casts = [
        'budget_min'            => 'decimal:2',
        'budget_max'            => 'decimal:2',
        'loan_amount'           => 'decimal:2',
        'preferred_localities'  => 'array',
        'score_breakdown'       => 'array',
        'tags'                  => 'array',
        'requires_loan'         => 'boolean',
        'is_duplicate'          => 'boolean',
        'stage_changed_at'      => 'datetime',
        'assigned_at'           => 'datetime',
        'last_activity_at'      => 'datetime',
        'next_followup_at'      => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────
    public function source(): BelongsTo
    {
        return $this->belongsTo(LeadSource::class, 'source_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(LeadActivity::class)->latest('performed_at');
    }

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    public function shortlists(): HasMany
    {
        return $this->hasMany(PropertyShortlist::class);
    }

    public function visits(): HasMany
    {
        return $this->hasMany(PropertyVisit::class);
    }

    // ── Scopes ─────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->whereNotIn('stage', ['closed_won', 'closed_lost']);
    }

    public function scopeHot($query)
    {
        return $query->where('temperature', 'hot');
    }

    public function scopeDueFollowup($query)
    {
        return $query->where('next_followup_at', '<=', now())
                     ->active();
    }

    public function scopeAssignedTo($query, int $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    // ── Accessors ──────────────────────────────────────────
    public function getBudgetDisplayAttribute(): string
    {
        if (!$this->budget_min || !$this->budget_max) return 'Budget not specified';
        return '₹' . number_format($this->budget_min / 100000, 1) . 'L–'
             . number_format($this->budget_max / 100000, 1) . 'L';
    }

    public function getTemperatureBadgeAttribute(): string
    {
        return match($this->temperature) {
            'hot'  => '<span class="badge-hot">🔥 Hot</span>',
            'warm' => '<span class="badge-warm">☀️ Warm</span>',
            default => '<span class="badge-cold">❄️ Cold</span>',
        };
    }

    public function addActivity(array $data): LeadActivity
    {
        $activity = $this->activities()->create(array_merge($data, [
            'performed_at' => $data['performed_at'] ?? now(),
        ]));

        $this->update([
            'last_activity_at'   => $activity->performed_at,
            'last_activity_type' => $activity->activity_type,
            'total_activities'   => $this->total_activities + 1,
        ]);

        return $activity;
    }
}

// ════════════════════════════════════════════════════════════
// app/Models/Property.php
// ════════════════════════════════════════════════════════════
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne};

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'prop_no', 'listing_type', 'property_type', 'config',
        'address', 'locality', 'landmark', 'city', 'pincode', 'latitude', 'longitude',
        'society_name', 'building_name', 'wing', 'floor_no', 'total_floors', 'flat_no',
        'carpet_sqft', 'builtup_sqft', 'superbuiltup_sqft', 'plot_sqft',
        'asking_price', 'price_negotiable',
        'monthly_rent', 'security_deposit', 'maintenance_pm',
        'bedrooms', 'bathrooms', 'balconies', 'parking', 'parking_type',
        'furnishing', 'facing', 'age_years', 'possession_type', 'possession_date',
        'cts_no', 'survey_no', 'hissa_no', 'land_type', 'rera_no', 'sro_jurisdiction',
        'owner_name', 'owner_phone', 'owner_email', 'owner_client_id',
        'status', 'amenities', 'portal_published',
        'total_beds', 'available_beds', 'pg_for', 'meals_included',
        'power_load_kw', 'car_parking_count',
        'view_count', 'inquiry_count', 'listed_at',
        'notes', 'tags', 'is_exclusive', 'assigned_to', 'created_by',
    ];

    protected $casts = [
        'asking_price'       => 'decimal:2',
        'monthly_rent'       => 'decimal:2',
        'security_deposit'   => 'decimal:2',
        'carpet_sqft'        => 'decimal:1',
        'builtup_sqft'       => 'decimal:1',
        'latitude'           => 'decimal:8',
        'longitude'          => 'decimal:8',
        'amenities'          => 'array',
        'portal_published'   => 'array',
        'tags'               => 'array',
        'price_negotiable'   => 'boolean',
        'meals_included'     => 'boolean',
        'is_exclusive'       => 'boolean',
        'listed_at'          => 'date',
        'possession_date'    => 'date',
    ];

    // ── Relationships ──────────────────────────────────────
    public function media(): HasMany
    {
        return $this->hasMany(PropertyMedia::class);
    }

    public function primaryMedia(): HasOne
    {
        return $this->hasOne(PropertyMedia::class)->where('is_primary', true);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function ownerClient(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'owner_client_id');
    }

    public function leases(): HasMany
    {
        return $this->hasMany(Lease::class);
    }

    public function activeLeases(): HasMany
    {
        return $this->hasMany(Lease::class)->where('status', 'active');
    }

    public function valuations(): HasMany
    {
        return $this->hasMany(Valuation::class)->latest('valuation_date');
    }

    public function maintenanceRequests(): HasMany
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    public function propertyTaxes(): HasMany
    {
        return $this->hasMany(PropertyTax::class);
    }

    // ── Scopes ─────────────────────────────────────────────
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeInLocality($query, string|array $locality)
    {
        return $query->whereIn('locality', (array)$locality);
    }

    public function scopeBudgetRange($query, float $min, float $max)
    {
        return $query->whereBetween('asking_price', [$min, $max]);
    }

    public function scopeRentRange($query, float $min, float $max)
    {
        return $query->whereBetween('monthly_rent', [$min, $max]);
    }

    // ── Accessors ──────────────────────────────────────────
    public function getPriceDisplayAttribute(): string
    {
        if ($this->listing_type === 'rent') {
            return '₹' . number_format((float)$this->monthly_rent) . '/mo';
        }
        $price = (float)$this->asking_price;
        if ($price >= 10000000) {
            return '₹' . number_format($price / 10000000, 2) . ' Cr';
        }
        return '₹' . number_format($price / 100000, 2) . ' L';
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }
}

// ════════════════════════════════════════════════════════════
// app/Models/Deal.php
// ════════════════════════════════════════════════════════════
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Deal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'deal_no', 'lead_id', 'client_id', 'property_id', 'deal_type',
        'property_address', 'config', 'carpet_sqft',
        'deal_value', 'brokerage_pct', 'brokerage_amount',
        'gst_pct', 'cgst_amount', 'sgst_amount', 'total_brokerage_with_gst',
        'documentation_fee', 'documentation_fee_gst',
        'co_broker_name', 'co_broker_phone', 'co_broker_firm', 'co_broker_pct', 'co_broker_amount',
        'stage', 'agreement_date', 'registration_date', 'possession_date',
        'payment_schedule', 'brokerage_status', 'brokerage_received', 'brokerage_pending',
        'invoice_id', 'notes', 'created_by', 'assigned_to',
    ];

    protected $casts = [
        'deal_value'             => 'decimal:2',
        'brokerage_pct'          => 'decimal:2',
        'brokerage_amount'       => 'decimal:2',
        'cgst_amount'            => 'decimal:2',
        'sgst_amount'            => 'decimal:2',
        'total_brokerage_with_gst' => 'decimal:2',
        'co_broker_amount'       => 'decimal:2',
        'brokerage_received'     => 'decimal:2',
        'payment_schedule'       => 'array',
        'agreement_date'         => 'date',
        'registration_date'      => 'date',
        'possession_date'        => 'date',
    ];

    // ── Relationships ──────────────────────────────────────
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    // ── Accessors ──────────────────────────────────────────
    public function getNetCommissionAttribute(): string
    {
        return bcsub(
            (string)$this->brokerage_amount,
            (string)$this->co_broker_amount,
            2
        );
    }

    public function getBrokeragePendingAttribute(): string
    {
        return bcsub(
            (string)$this->total_brokerage_with_gst,
            (string)$this->brokerage_received,
            2
        );
    }

    // ── Computed GST ───────────────────────────────────────
    public function calculateGST(string $partyState = 'Maharashtra'): void
    {
        $taxable = (string) $this->brokerage_amount;
        $rate    = '18';

        if ($partyState === 'Maharashtra') {
            $this->cgst_amount = bcmul($taxable, bcdiv('9', '100', 6), 2);
            $this->sgst_amount = bcmul($taxable, bcdiv('9', '100', 6), 2);
        } else {
            $this->cgst_amount = '0';
            $this->sgst_amount = '0';
        }

        $totalGst = bcadd($this->cgst_amount, $this->sgst_amount, 2);
        $this->total_brokerage_with_gst = bcadd($taxable, $totalGst, 2);
    }
}

// ════════════════════════════════════════════════════════════
// app/Models/Client.php
// ════════════════════════════════════════════════════════════
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_no', 'type', 'name', 'company_name',
        'phone', 'alt_phone', 'email', 'whatsapp_no',
        'address', 'city', 'state', 'pincode', 'country',
        'aadhar_no', 'pan_no', 'passport_no', 'gstin',
        'annual_income', 'net_worth_approx', 'investment_appetite',
        'first_transaction_date', 'last_transaction_date',
        'total_transactions', 'total_brokerage_paid',
        'referred_by', 'referral_source',
        'rating', 'notes', 'tags', 'assigned_to', 'created_by',
    ];

    protected $casts = [
        'annual_income'          => 'decimal:2',
        'net_worth_approx'       => 'decimal:2',
        'total_brokerage_paid'   => 'decimal:2',
        'tags'                   => 'array',
        'first_transaction_date' => 'date',
        'last_transaction_date'  => 'date',
    ];

    protected $hidden = ['aadhar_no', 'pan_no', 'passport_no'];

    // ── Relationships ──────────────────────────────────────
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'assigned_to');
    }

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class);
    }

    public function requirements(): HasMany
    {
        return $this->hasMany(ClientRequirement::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function referredBy(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'referred_by');
    }

    public function kyc(): HasMany
    {
        return $this->hasMany(KycRecord::class, 'entity_id')
                    ->where('entity_type', 'client');
    }

    public function investments(): HasMany
    {
        return $this->hasMany(InvestmentPortfolio::class);
    }
}

// ════════════════════════════════════════════════════════════
// app/Models/Lease.php
// ════════════════════════════════════════════════════════════
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

// ════════════════════════════════════════════════════════════
// app/Models/Invoice.php
// ════════════════════════════════════════════════════════════
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasOne};

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no', 'invoice_type', 'invoice_date', 'due_date', 'fiscal_year_id',
        'client_id', 'client_name', 'client_address', 'client_gstin', 'client_pan', 'client_state',
        'deal_id', 'lease_id',
        'line_items',
        'subtotal', 'discount_pct', 'discount_amount', 'taxable_amount',
        'cgst_amount', 'sgst_amount', 'igst_amount', 'total_gst', 'round_off', 'total_amount',
        'amount_paid', 'amount_pending', 'payment_terms',
        'status',
        'journal_entry_id',
        'bank_name', 'bank_account_no', 'bank_ifsc', 'upi_id', 'payment_link',
        'notes', 'pdf_path', 'sent_at', 'paid_at', 'created_by',
    ];

    protected $casts = [
        'invoice_date'   => 'date',
        'due_date'       => 'date',
        'subtotal'       => 'decimal:2',
        'taxable_amount' => 'decimal:2',
        'cgst_amount'    => 'decimal:2',
        'sgst_amount'    => 'decimal:2',
        'igst_amount'    => 'decimal:2',
        'total_gst'      => 'decimal:2',
        'total_amount'   => 'decimal:2',
        'amount_paid'    => 'decimal:2',
        'amount_pending' => 'decimal:2',
        'line_items'     => 'array',
        'sent_at'        => 'datetime',
        'paid_at'        => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    public function fiscalYear(): BelongsTo
    {
        return $this->belongsTo(FiscalYear::class);
    }

    public function eInvoice(): HasOne
    {
        return $this->hasOne(EInvoiceRecord::class);
    }

    // ── Scopes ─────────────────────────────────────────────
    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', 'paid')
                     ->where('due_date', '<', now());
    }

    public function isOverdue(): bool
    {
        return $this->status !== 'paid'
            && $this->due_date !== null
            && $this->due_date->isPast();
    }

    // ── Helpers ────────────────────────────────────────────
    public function recordPayment(string $amount, string $mode = 'upi'): void
    {
        $newPaid = bcadd((string)$this->amount_paid, $amount, 2);
        $newStatus = bccomp($newPaid, (string)$this->total_amount, 2) >= 0
            ? 'paid' : 'partial';

        $this->update([
            'amount_paid'    => $newPaid,
            'amount_pending' => bcsub((string)$this->total_amount, $newPaid, 2),
            'status'         => $newStatus,
            'paid_at'        => $newStatus === 'paid' ? now() : null,
        ]);
    }
}

// ════════════════════════════════════════════════════════════
// app/Models/Employee.php
// ════════════════════════════════════════════════════════════
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne};

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'emp_no', 'user_id', 'first_name', 'last_name', 'email', 'phone',
        'dob', 'gender', 'blood_group', 'marital_status',
        'address', 'city', 'state', 'pincode',
        'aadhar_no', 'pan_no', 'uan_no', 'esic_no',
        'department', 'designation', 'grade', 'reporting_to',
        'joining_date', 'confirmation_date', 'exit_date', 'exit_reason',
        'employment_type', 'work_location',
        'bank_name', 'bank_account_no', 'bank_ifsc',
        'status', 'photo_path', 'notes',
    ];

    protected $casts = [
        'dob'               => 'date',
        'joining_date'      => 'date',
        'confirmation_date' => 'date',
        'exit_date'         => 'date',
    ];

    protected $hidden = ['aadhar_no', 'pan_no', 'uan_no', 'bank_account_no'];

    // ── Relationships ──────────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reportingTo(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'reporting_to');
    }

    public function salaryStructure(): HasOne
    {
        return $this->hasOne(SalaryStructure::class)->latestOfMany();
    }

    public function payrollEntries(): HasMany
    {
        return $this->hasMany(PayrollEntry::class);
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(AttendanceLog::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function benefits(): HasMany
    {
        return $this->hasMany(EmployeeBenefit::class);
    }

    // ── Helpers ────────────────────────────────────────────
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function isOnProbation(): bool
    {
        return $this->confirmation_date === null
            || $this->confirmation_date->isFuture();
    }

    public function yearsOfService(): float
    {
        return round($this->joining_date->diffInDays(now()) / 365, 2);
    }
}