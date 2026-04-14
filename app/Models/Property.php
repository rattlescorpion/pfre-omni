<?php
declare(strict_types=1);

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