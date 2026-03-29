<?php declare(strict_types=1);
namespace App\Services\Dms;
use App\Repositories\Dms\StampDutyRepository;
use Core\Cache;
final class StampDutyService
{
    public function __construct(
        private readonly StampDutyRepository $repo,
        private readonly Cache $cache
    ) {
    }
    /**
     * Calculate complete stamp duty breakdown for Maharashtra
     */
    public function calculate(array $params): array
    {
        // $params: deed_type, consideration, location_type, buyer_gender, property_type
        $rates = $this->getRates(
            $params['deed_type'],
            $params['location_type'],
            $params['buyer_gender'] ?? 'any'
        );
        $consideration = $params['consideration'];
        // Apply Ready Reckoner if applicable (for sale deeds)
        $effectiveValue = $consideration;
        if (isset($params['rr_value']) && bccomp($params['rr_value'], $consideration, 2) > 0) {
            $effectiveValue = $params['rr_value'];
        }
        $stampDuty = bcmul($effectiveValue, bcdiv((string) $rates['stamp_duty_pct'], '100', 6), 2);
        $metroCess = bcmul($effectiveValue, bcdiv((string) $rates['metro_cess_pct'], '100', 6), 2);
        $registrationFee = min(
            bcmul($effectiveValue, bcdiv((string) $rates['registration_fee_pct'], '100', 6), 2),
            (string) $rates['registration_fee_max']
        );
        $lbt = bcmul($effectiveValue, bcdiv((string) $rates['lbt_pct'], '100', 6), 2);
        $total = bcadd($stampDuty, bcadd($metroCess, bcadd($registrationFee, $lbt, 2), 2), 2);
        // Female buyer concession: 1% less stamp duty in Mumbai
        if ($params['buyer_gender'] === 'female' && $params['location_type'] === 'mumbai_municipal') {
            $concession = bcmul($effectiveValue, '0.01', 2);
            $stampDuty = bcsub($stampDuty, $concession, 2);
            $total = bcsub($total, $concession, 2);
        }
        return [
            'consideration_value' => $consideration,
            'effective_value' => $effectiveValue,
            'stamp_duty_rate' => $rates['stamp_duty_pct'] . '%',
            'stamp_duty' => $stampDuty,
            'metro_cess' => $metroCess,
            'registration_fee' => $registrationFee,
            'lbt' => $lbt,
            'total_govt_charges' => $total,
            'breakdown_html' => $this->generateBreakdownTable([
                'Consideration Value' => $consideration,
                'Ready Reckoner Value' => $effectiveValue,
                'Stamp Duty (' . $rates['stamp_duty_pct'] . '%)' => $stampDuty,
                'Metro Cess' => $metroCess,
                'Registration Fee' => $registrationFee,
                'LBT' => $lbt,
                '<b>Total</b>' => '<b>' . $this->formatINR($total) . '</b>',
            ]),
        ];
    }
    private function getRates(string $deedType, string $locationType, string $buyerGender): array
    {
        $cacheKey = "stamp_duty_rates:{$deedType}:{$locationType}:{$buyerGender}";
        return $this->cache->remember(
            $cacheKey,
            7200,
            fn() =>
            $this->repo->getRate($deedType, $locationType, $buyerGender)
        );
    }
    private function formatINR(string $amount): string
    {
        return '₹' . number_format((float) $amount, 2);
    }
    private function generateBreakdownTable(array $rows): string
    {
        $html = '<table class="stamp-duty-table">';
        foreach ($rows as $label => $value) {
            $displayValue = is_string($value) && !str_starts_with($value, '<')
                ? $this->formatINR($value) : $value;
            $html .= "<tr><td>{$label}</td><td>{$displayValue}</td></tr>";
        }
        return $html . '</table>';
    }
}