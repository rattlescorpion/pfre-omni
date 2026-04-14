<?php
declare(strict_types=1);

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