<?php

if (!function_exists('formatIndianPhoneNumber')) {
    /**
     * Format phone number for Indian standards
     *
     * @param string $phone
     * @return string
     */
    function formatIndianPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/\D/', '', $phone);

        // Handle different phone number formats
        if (strlen($phone) === 10) {
            // 10 digit number
            return '+91 ' . substr($phone, 0, 5) . ' ' . substr($phone, 5);
        } elseif (strlen($phone) === 12 && str_starts_with($phone, '91')) {
            // 12 digit with 91 prefix
            return '+91 ' . substr($phone, 2, 5) . ' ' . substr($phone, 7);
        } elseif (strlen($phone) === 13 && str_starts_with($phone, '091')) {
            // 13 digit with 091 prefix
            return '+91 ' . substr($phone, 3, 5) . ' ' . substr($phone, 8);
        }

        return $phone; // Return as-is if format not recognized
    }
}

if (!function_exists('formatIndianCurrency')) {
    /**
     * Format amount in Indian Rupees
     *
     * @param float $amount
     * @return string
     */
    function formatIndianCurrency(float $amount): string
    {
        return '₹' . number_format($amount, 2, '.', ',');
    }
}

if (!function_exists('getIndianStates')) {
    /**
     * Get list of Indian states and union territories
     *
     * @return array
     */
    function getIndianStates(): array
    {
        return [
            'AP' => 'Andhra Pradesh',
            'AR' => 'Arunachal Pradesh',
            'AS' => 'Assam',
            'BR' => 'Bihar',
            'CG' => 'Chhattisgarh',
            'GA' => 'Goa',
            'GJ' => 'Gujarat',
            'HR' => 'Haryana',
            'HP' => 'Himachal Pradesh',
            'JK' => 'Jammu and Kashmir',
            'JH' => 'Jharkhand',
            'KA' => 'Karnataka',
            'KL' => 'Kerala',
            'MP' => 'Madhya Pradesh',
            'MH' => 'Maharashtra',
            'MN' => 'Manipur',
            'ML' => 'Meghalaya',
            'MZ' => 'Mizoram',
            'NL' => 'Nagaland',
            'OR' => 'Odisha',
            'PB' => 'Punjab',
            'RJ' => 'Rajasthan',
            'SK' => 'Sikkim',
            'TN' => 'Tamil Nadu',
            'TG' => 'Telangana',
            'TR' => 'Tripura',
            'UP' => 'Uttar Pradesh',
            'UT' => 'Uttarakhand',
            'WB' => 'West Bengal',
            'AN' => 'Andaman and Nicobar Islands',
            'CH' => 'Chandigarh',
            'DN' => 'Dadra and Nagar Haveli and Daman and Diu',
            'DL' => 'Delhi',
            'LA' => 'Ladakh',
            'LD' => 'Lakshadweep',
            'PY' => 'Puducherry',
        ];
    }
}

if (!function_exists('validateIndianPincode')) {
    /**
     * Validate Indian PIN code
     *
     * @param string $pincode
     * @return bool
     */
    function validateIndianPincode(string $pincode): bool
    {
        return preg_match('/^[1-9][0-9]{5}$/', $pincode) === 1;
    }
}

if (!function_exists('formatIndianDate')) {
    /**
     * Format date in Indian format (DD/MM/YYYY)
     *
     * @param string $date
     * @return string
     */
    function formatIndianDate(string $date): string
    {
        $timestamp = strtotime($date);
        return date('d/m/Y', $timestamp);
    }
}

if (!function_exists('getIndianTimezones')) {
    /**
     * Get Indian time zones
     *
     * @return array
     */
    function getIndianTimezones(): array
    {
        return [
            'Asia/Kolkata' => 'IST (Indian Standard Time)',
            'Asia/Calcutta' => 'IST (Legacy)',
        ];
    }
}