<?php declare(strict_types=1);

namespace App\Traits;

/**
 * Enforces high-precision arithmetic for all financial transactions.
 * standard: 2 decimal places for display, 6 for internal calculations.
 */
trait MoneyTrait {
    protected function add(string $a, string $b): string {
        return bcadd($a, $b, 2);
    }
    protected function sub(string $a, string $b): string {
        return bcsub($a, $b, 2);
    }
    protected function mul(string $a, string $b): string {
        return bcmul($a, $b, 2);
    }
    protected function div(string $a, string $b): string {
        return bcdiv($a, $b, 6);
    }
    
    protected function formatInr(string $amount): string {
        return '₹' . number_format((float)$amount, 2);
    }
}