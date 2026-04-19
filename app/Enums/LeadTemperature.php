<?php
declare(strict_types=1);

namespace App\Enums;

enum LeadTemperature: string {
    case HOT = 'hot';
    case WARM = 'warm';
    case COLD = 'cold';

    public function badge(): string {
        return match($this) {
            self::HOT => '<span class="badge-hot">🔥 Hot</span>',
            self::WARM => '<span class="badge-warm">☀️ Warm</span>',
            self::COLD => '<span class="badge-cold">❄️ Cold</span>',
        };
    }
}