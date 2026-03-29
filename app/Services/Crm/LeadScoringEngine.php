<?php declare(strict_types=1);

namespace App\Services\Crm;

class LeadScoringEngine
{
    /**
     * The Service expects 'total', 'temperature', and 'breakdown'.
     */
    public function calculate(array $leadData): array
    {
        $score = 0;
        $breakdown = [];

        // Check for Phone
        if (!empty($leadData['phone'])) {
            $score += 10;
            $breakdown[] = 'Valid contact number provided (+10)';
        }

        // Check for Intent
        if (isset($leadData['intent']) && $leadData['intent'] === 'buy') {
            $score += 20;
            $breakdown[] = 'High-value buying intent identified (+20)';
        } elseif (isset($leadData['intent']) && $leadData['intent'] === 'rent') {
            $score += 5;
            $breakdown[] = 'Rental interest identified (+5)';
        }

        // Determine temperature
        $temperature = ($score >= 20) ? 'hot' : 'warm';

        return [
            'total'       => $score,
            'temperature' => $temperature,
            'breakdown'   => $breakdown // This fixes the 'Undefined array key' error!
        ];
    }
}