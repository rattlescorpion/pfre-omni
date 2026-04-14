<?php

return [

    /*
    |--------------------------------------------------------------------------
    | WhatsApp Business API (Interakt / Wati / Meta)
    |--------------------------------------------------------------------------
    | Used for automated lead alerts and sending property brochures.
    */
    'whatsapp' => [
        'enabled' => env('WHATSAPP_ENABLED', false),
        'api_key' => env('WHATSAPP_API_KEY'),
        'base_url' => env('WHATSAPP_BASE_URL', 'https://api.interakt.ai/v1/'),
        'sender_number' => env('WHATSAPP_SENDER_NUMBER'),
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Gateway (MSG91 / Gupshup)
    |--------------------------------------------------------------------------
    | Backup for critical alerts and OTPs for client logins.
    */
    'sms' => [
        'provider' => env('SMS_PROVIDER', 'msg91'),
        'api_key' => env('SMS_API_KEY'),
        'sender_id' => env('SMS_SENDER_ID', 'PFREIN'), // Property Finder Real Estate India
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Gateways (Razorpay)
    |--------------------------------------------------------------------------
    | For collecting token amounts or processing service fees.
    */
    'payments' => [
        'razorpay' => [
            'key_id' => env('RAZORPAY_KEY_ID'),
            'key_secret' => env('RAZORPAY_KEY_SECRET'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Real Estate Specific APIs
    |--------------------------------------------------------------------------
    | APIs for GST verification, CIBIL checks, or Google Maps.
    */
    'services' => [
        'google_maps' => [
            'api_key' => env('GOOGLE_MAPS_API_KEY'),
            'default_center' => ['lat' => 19.0760, 'lng' => 72.8777], // Mumbai Center
        ],
        
        'gst_verification' => [
            'api_key' => env('GST_API_KEY'),
            'endpoint' => env('GST_API_ENDPOINT', 'https://commonapi.mastergst.com/'),
        ],

        'e_sign' => [
            'provider' => 'Digio', // Commonly used in India for Rent Agreements
            'api_key' => env('ESIGN_API_KEY'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cloud Storage (AWS S3)
    |--------------------------------------------------------------------------
    | For storing property photos and legal documents (Partnership Deeds).
    */
    'storage' => [
        'region' => env('AWS_DEFAULT_REGION', 'ap-south-1'), // Mumbai Region
        'bucket' => env('AWS_BUCKET'),
    ],

];