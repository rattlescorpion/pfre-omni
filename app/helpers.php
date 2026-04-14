<?php

if (!function_exists('calculate_pfre_gst')) {
    /**
     * Calculates 18% GST (9% CGST + 9% SGST) for Mumbai transactions.
     * * @param float $amount
     * @return array
     */
    function calculate_pfre_gst(float $amount): array
    {
        $gstRate = 0.18;
        $totalGst = $amount * $gstRate;
        $cgst = $totalGst / 2;
        $sgst = $totalGst / 2;

        return [
            'base_amount' => $amount,
            'cgst' => $cgst,
            'sgst' => $sgst,
            'total_gst' => $totalGst,
            'grand_total' => $amount + $totalGst,
        ];
    }
}

if (!function_exists('format_mumbai_currency')) {
    /**
     * Formats numbers to Indian Rupee (INR) format.
     */
    function format_mumbai_currency($amount)
    {
        return '₹' . number_format($amount, 2, '.', ',');
    }
}

if (!function_exists('validate_rera_format')) {
    /**
     * Simple check for MahaRERA registration format (e.g., P518000...)
     */
    function validate_rera_format(string $registrationNumber): bool
    {
        return (bool) preg_match('/^P\d{11}$/', $registrationNumber);
    }
}