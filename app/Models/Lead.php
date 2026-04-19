<?php
declare(strict_types=1);

namespace App\Models;

use App\Enums\LeadTemperature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

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
        'temperature'           => LeadTemperature::class, // Enum Cast
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
        // Search the database using the Enum's underlying string value
        return $query->where('temperature', LeadTemperature::HOT->value);
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
        // Because of the cast, $this->temperature is already an Enum object
        return $this->temperature?->badge() ?? LeadTemperature::COLD->badge();
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