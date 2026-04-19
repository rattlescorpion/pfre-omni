<?php
declare(strict_types=1);

namespace App\Traits;

trait HasAddress {
    /**
     * Get the formatted full address.
     */
    public function getFullAddressAttribute(): string {
        return collect([
            $this->address, 
            $this->locality ?? null, // Included in case it's used in Property
            $this->city, 
            $this->state, 
            $this->pincode
        ])->filter()->implode(', ');
    }
}