<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Razorpay Configuration
    |--------------------------------------------------------------------------
    | Used for booking advances, EMI collections, maintenance charges,
    | and any other payment flows within the PFRE Omni Platform.
    */

    // -------------------------------------------------------------------------
    // API Credentials
    // -------------------------------------------------------------------------
    'key_id'     => env('RAZORPAY_KEY_ID', ''),
    'key_secret' => env('RAZORPAY_KEY_SECRET', ''),

    // -------------------------------------------------------------------------
    // Webhook
    // -------------------------------------------------------------------------
    'webhook_secret'    => env('RAZORPAY_WEBHOOK_SECRET', ''),
    'webhook_url'       => env('APP_URL', '') . '/api/webhooks/razorpay',

    // -------------------------------------------------------------------------
    // Business / Merchant Details
    // -------------------------------------------------------------------------
    'merchant' => [
        'name'          => env('PFRE_COMPANY_NAME', 'Property Finder Real Estate'),
        'description'   => 'Real Estate Payment Gateway',
        'image'         => env('APP_URL', '') . '/images/logo.png',
        'color'         => '#1A1A2E',       // Brand primary colour
        'prefill' => [
            'email'     => env('PFRE_COMPANY_EMAIL', ''),
            'contact'   => env('PFRE_COMPANY_PHONE', ''),
        ],
        'notes' => [
            'platform'  => 'PFRE Omni Platform',
        ],
    ],

    // -------------------------------------------------------------------------
    // Currency & Locale
    // -------------------------------------------------------------------------
    'currency'          => 'INR',
    'currency_display'  => '₹',
    'amount_multiplier' => 100, // Razorpay accepts paise

    // -------------------------------------------------------------------------
    // Payment Methods
    // -------------------------------------------------------------------------
    'methods' => [
        'upi'           => true,
        'card'          => true,
        'netbanking'    => true,
        'wallet'        => true,
        'emi'           => true,
        'bank_transfer' => true,
    ],

    // -------------------------------------------------------------------------
    // EMI Configuration
    // -------------------------------------------------------------------------
    'emi' => [
        'enabled'       => true,
        'min_amount'    => 500000,      // ₹5,000 minimum for EMI
        'tenures'       => [3, 6, 9, 12, 18, 24, 36],   // months
        'no_cost_emi'   => true,
    ],

    // -------------------------------------------------------------------------
    // Payment Link Settings
    // -------------------------------------------------------------------------
    'payment_link' => [
        'expire_by_hours'       => 72,      // Link expires in 72h
        'reminder_enable'       => true,
        'reminder_delay_mins'   => 60,
        'sms_notify'            => true,
        'email_notify'          => true,
        'whatsapp_notify'       => true,
        'upi_link'              => true,
    ],

    // -------------------------------------------------------------------------
    // Refund Settings
    // -------------------------------------------------------------------------
    'refund' => [
        'speed'         => 'normal',    // 'normal' | 'optimum'
        'auto_refund'   => false,
        'receipt_prefix' => 'PFRE-REF-',
    ],

    // -------------------------------------------------------------------------
    // Receipt / Order ID Prefixes (per payment type)
    // -------------------------------------------------------------------------
    'receipt_prefixes' => [
        'booking_advance'       => 'PFRE-BKG-',
        'emi_installment'       => 'PFRE-EMI-',
        'maintenance'           => 'PFRE-MNT-',
        'registration_fee'      => 'PFRE-REG-',
        'token_amount'          => 'PFRE-TKN-',
        'balance_payment'       => 'PFRE-BAL-',
        'misc'                  => 'PFRE-MSC-',
    ],

    // -------------------------------------------------------------------------
    // Retry & Timeout
    // -------------------------------------------------------------------------
    'timeout'           => 30,          // HTTP timeout in seconds
    'max_retries'       => 3,

    // -------------------------------------------------------------------------
    // Razorpay Route Mode (live / test)
    // -------------------------------------------------------------------------
    'mode'              => env('RAZORPAY_MODE', 'test'), // 'live' in production

    // -------------------------------------------------------------------------
    // Test Credentials (used only when mode = test)
    // -------------------------------------------------------------------------
    'test' => [
        'key_id'        => env('RAZORPAY_TEST_KEY_ID', ''),
        'key_secret'    => env('RAZORPAY_TEST_KEY_SECRET', ''),
    ],

];