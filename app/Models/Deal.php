<?php
declare(strict_types=1);

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