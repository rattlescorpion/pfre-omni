<?php
declare(strict_types=1);

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