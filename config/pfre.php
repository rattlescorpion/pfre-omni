<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Property Finder Omni Platform — Core Configuration
    |--------------------------------------------------------------------------
    | Central config for all 306 modules across 26 clusters.
    | Sensitive values are read from .env; static constants live here.
    */

    // -------------------------------------------------------------------------
    // Company / Tenant Identity
    // -------------------------------------------------------------------------
    'company' => [
        'name'          => env('PFRE_COMPANY_NAME', 'Property Finder Real Estate'),
        'short_name'    => env('PFRE_COMPANY_SHORT', 'PFRE'),
        'gstin'         => env('PFRE_GSTIN', ''),
        'pan'           => env('PFRE_PAN', ''),
        'cin'           => env('PFRE_CIN', ''),
        'rera_no'       => env('PFRE_RERA_NO', ''),
        'address'       => [
            'line1'     => env('PFRE_ADDR_LINE1', ''),
            'line2'     => env('PFRE_ADDR_LINE2', ''),
            'city'      => env('PFRE_ADDR_CITY', 'Mumbai'),
            'locality'  => env('PFRE_ADDR_LOCALITY', 'Malad West'),
            'district'  => env('PFRE_ADDR_DISTRICT', 'Mumbai Suburban'),
            'state'     => env('PFRE_ADDR_STATE', 'Maharashtra'),
            'pincode'   => env('PFRE_ADDR_PINCODE', '400064'),
            'country'   => 'India',
        ],
        'phone'         => env('PFRE_COMPANY_PHONE', ''),
        'email'         => env('PFRE_COMPANY_EMAIL', ''),
        'website'       => env('PFRE_COMPANY_WEBSITE', ''),
        'logo_path'     => env('PFRE_LOGO_PATH', 'images/logo.png'),
        'logo_dark_path'=> env('PFRE_LOGO_DARK_PATH', 'images/logo-dark.png'),
        'favicon_path'  => env('PFRE_FAVICON_PATH', 'images/favicon.ico'),
    ],

    // -------------------------------------------------------------------------
    // GST Configuration
    // -------------------------------------------------------------------------
    'gst' => [
        'enabled'           => env('GST_ENABLED', true),
        'state_code'        => '27',        // Maharashtra GSTState Code
        'default_hsn_code'  => '997212',    // Real estate services HSN
        'rates' => [
            'affordable_housing'    => 1.0,  // 1% for affordable housing
            'under_construction'    => 5.0,  // 5% without ITC
            'commercial'            => 12.0, // 12% commercial property
            'ready_to_move'         => 0.0,  // 0% for completed + OC received
            'plotted_development'   => 0.0,  // Typically exempt
            'service_charges'       => 18.0, // Management/service fees
            'brokerage'             => 18.0, // Brokerage / agency fees
        ],
        'irp' => [
            'api_url'       => env('IRP_API_URL', 'https://einvoice1.gst.gov.in'),
            'username'      => env('IRP_USERNAME', ''),
            'password'      => env('IRP_PASSWORD', ''),
            'client_id'     => env('GSTN_CLIENT_ID', ''),
            'client_secret' => env('GSTN_CLIENT_SECRET', ''),
        ],
        'e_invoice_threshold' => 1000000, // ₹10 lakh — mandate above this
    ],

    // -------------------------------------------------------------------------
    // RERA (Maharashtra — MahaRERA)
    // -------------------------------------------------------------------------
    'rera' => [
        'enabled'       => env('RERA_ENABLED', true),
        'state'         => 'Maharashtra',
        'authority'     => 'MahaRERA',
        'portal_url'    => env('RERA_MH_PORTAL', 'https://maharera.mahaonline.gov.in'),
        'api_url'       => env('RERA_MH_API_URL', 'https://maharera.mahaonline.gov.in/api'),
        'api_key'       => env('RERA_API_KEY', ''),
        'agent_rera_no' => env('RERA_AGENT_NO', ''),
        'renewal_alert_days' => 90, // Alert X days before expiry
    ],

    // -------------------------------------------------------------------------
    // Maharashtra Stamp Duty
    // -------------------------------------------------------------------------
    'stamp_duty' => [
        // Rates as of FY 2024-25 (Mumbai)
        'mumbai' => [
            'male'              => 5.0,
            'female'            => 4.0,   // 1% concession for women buyers
            'joint'             => 4.5,   // Male + Female joint
        ],
        'rest_of_maharashtra' => [
            'male'              => 7.0,
            'female'            => 6.0,
            'joint'             => 6.5,
        ],
        'registration_fee_pct'  => 1.0,     // 1% of property value
        'registration_fee_cap'  => 30000,   // Max ₹30,000
        'metro_cess_pct'        => 1.0,     // Metro cess (Mumbai)
        'lbt_pct'               => 0.0,     // LBT abolished
        'gst_on_stamp'          => false,
    ],

    // -------------------------------------------------------------------------
    // BMC / Property Tax (MCGM)
    // -------------------------------------------------------------------------
    'property_tax' => [
        'authority'         => 'MCGM',
        'portal_url'        => 'https://www.mcgm.gov.in',
        'capital_value_base_year' => 2012,
        'tax_rate_residential'  => 0.316, // % of capital value per annum
        'tax_rate_commercial'   => 0.420,
        'tax_rate_industrial'   => 0.420,
        'due_dates'         => ['first_half' => '06-30', 'second_half' => '12-31'],
        'penalty_rate'      => 2.0, // 2% per month on overdue
    ],

    // -------------------------------------------------------------------------
    // Payroll / Statutory Compliance
    // -------------------------------------------------------------------------
    'payroll' => [
        'pf' => [
            'enabled'           => env('PF_ENABLED', true),
            'employee_rate'     => 12.0,    // 12% of Basic+DA
            'employer_rate'     => 12.0,    // 12% (EPS 8.33% + EPF 3.67%)
            'eps_rate'          => 8.33,
            'edli_rate'         => 0.5,
            'admin_charges'     => 0.5,
            'wage_ceiling'      => 15000,   // PF applicable on ₹15,000
            'uan_portal'        => 'https://unifiedportal-mem.epfindia.gov.in',
        ],
        'esic' => [
            'enabled'           => env('ESIC_ENABLED', true),
            'employee_rate'     => 0.75,    // 0.75%
            'employer_rate'     => 3.25,    // 3.25%
            'wage_ceiling'      => 21000,   // ESIC applicable on ₹21,000
            'portal'            => 'https://www.esic.in',
        ],
        'professional_tax' => [
            'enabled'           => env('PT_ENABLED', true),
            'state'             => 'Maharashtra',
            // Monthly PT slabs (Maharashtra) — amount in INR
            'slabs' => [
                ['from' => 0,      'to' => 7500,  'amount' => 0],
                ['from' => 7501,   'to' => 10000, 'amount' => 175],
                ['from' => 10001,  'to' => PHP_INT_MAX, 'amount' => 200], // 300 in Feb
            ],
            'annual_max'        => 2500,
        ],
        'tds' => [
            'enabled'           => env('TDS_ENABLED', true),
            // Section 194C — Contractor
            '194C_individual'   => 1.0,
            '194C_company'      => 2.0,
            // Section 194I — Rent
            '194I_land_building'=> 10.0,
            // Section 194IA — Immovable property purchase >=50L
            '194IA'             => 1.0,
            '194IA_threshold'   => 5000000,
            // Section 194J — Professional fees
            '194J'              => 10.0,
        ],
        'pay_cycle'         => 'monthly',
        'pay_date'          => 1,           // 1st of each month
        'working_days'      => 26,
        'overtime_multiplier' => 1.5,
    ],

    // -------------------------------------------------------------------------
    // Financial Year & Currency
    // -------------------------------------------------------------------------
    'finance' => [
        'fy_start_month'    => 4,           // April
        'fy_start_day'      => 1,
        'currency'          => 'INR',
        'currency_symbol'   => '₹',
        'currency_locale'   => 'en_IN',
        'decimal_places'    => 2,
        'lakh_formatting'   => true,        // Display in lakh/crore
    ],

    // -------------------------------------------------------------------------
    // Platform Modules (26 Clusters)
    // -------------------------------------------------------------------------
    'clusters' => [
        'crm'               => ['enabled' => true,  'prefix' => 'crm'],
        'lead_management'   => ['enabled' => true,  'prefix' => 'leads'],
        'property'          => ['enabled' => true,  'prefix' => 'property'],
        'project'           => ['enabled' => true,  'prefix' => 'project'],
        'sales'             => ['enabled' => true,  'prefix' => 'sales'],
        'bookings'          => ['enabled' => true,  'prefix' => 'bookings'],
        'agreements'        => ['enabled' => true,  'prefix' => 'agreements'],
        'finance'           => ['enabled' => true,  'prefix' => 'finance'],
        'accounts'          => ['enabled' => true,  'prefix' => 'accounts'],
        'gst'               => ['enabled' => true,  'prefix' => 'gst'],
        'rera'              => ['enabled' => true,  'prefix' => 'rera'],
        'hrms'              => ['enabled' => true,  'prefix' => 'hr'],
        'payroll'           => ['enabled' => true,  'prefix' => 'payroll'],
        'attendance'        => ['enabled' => true,  'prefix' => 'attendance'],
        'leave'             => ['enabled' => true,  'prefix' => 'leave'],
        'procurement'       => ['enabled' => true,  'prefix' => 'procurement'],
        'inventory'         => ['enabled' => true,  'prefix' => 'inventory'],
        'construction'      => ['enabled' => true,  'prefix' => 'construction'],
        'facilities'        => ['enabled' => true,  'prefix' => 'facilities'],
        'documents'         => ['enabled' => true,  'prefix' => 'docs'],
        'reports'           => ['enabled' => true,  'prefix' => 'reports'],
        'analytics'         => ['enabled' => true,  'prefix' => 'analytics'],
        'notifications'     => ['enabled' => true,  'prefix' => 'notifications'],
        'integrations'      => ['enabled' => true,  'prefix' => 'integrations'],
        'admin'             => ['enabled' => true,  'prefix' => 'admin'],
        'settings'          => ['enabled' => true,  'prefix' => 'settings'],
    ],

    // -------------------------------------------------------------------------
    // Document Storage Paths (relative to storage/app/)
    // -------------------------------------------------------------------------
    'storage' => [
        'documents'     => 'documents',
        'rera'          => 'rera',
        'agreements'    => 'agreements',
        'kyc'           => 'kyc',
        'photos'        => 'photos',
        'videos'        => 'videos',
        'floor_plans'   => 'floor_plans',
        'reports'       => 'reports',
        'invoices'      => 'invoices',
        'payslips'      => 'payslips',
        'contracts'     => 'contracts',
        'temp'          => 'temp',
    ],

    // -------------------------------------------------------------------------
    // KYC & Aadhaar eSign
    // -------------------------------------------------------------------------
    'kyc' => [
        'aadhaar_esign' => [
            'enabled'       => env('AADHAAR_ESIGN_ENABLED', false),
            'api_url'       => env('AADHAAR_ESIGN_API_URL', ''),
            'asp_id'        => env('AADHAAR_ESIGN_ASP_ID', ''),
            'asp_secret'    => env('AADHAAR_ESIGN_ASP_SECRET', ''),
        ],
        'cibil' => [
            'enabled'       => env('CIBIL_ENABLED', false),
            'member_id'     => env('CIBIL_MEMBER_ID', ''),
            'password'      => env('CIBIL_PASSWORD', ''),
            'api_url'       => env('CIBIL_API_URL', ''),
        ],
        'pan_verification' => [
            'enabled'       => env('PAN_VERIFY_ENABLED', false),
            'api_url'       => env('PAN_VERIFY_API_URL', ''),
            'api_key'       => env('PAN_VERIFY_API_KEY', ''),
        ],
        'accepted_doc_types' => [
            'identity'  => ['aadhaar', 'pan', 'passport', 'voter_id', 'driving_license'],
            'address'   => ['aadhaar', 'passport', 'utility_bill', 'bank_statement'],
            'income'    => ['itr', 'form_16', 'salary_slip', 'bank_statement'],
        ],
    ],

    // -------------------------------------------------------------------------
    // Notifications (Channels & Templates)
    // -------------------------------------------------------------------------
    'notifications' => [
        'channels' => [
            'whatsapp'  => env('NOTIFY_WHATSAPP', true),
            'sms'       => env('NOTIFY_SMS', true),
            'email'     => env('NOTIFY_EMAIL', true),
            'push'      => env('NOTIFY_PUSH', false),
            'in_app'    => env('NOTIFY_IN_APP', true),
        ],
        'sms' => [
            'provider'      => env('SMS_PROVIDER', 'msg91'),
            'auth_key'      => env('MSG91_AUTH_KEY', ''),
            'sender_id'     => env('MSG91_SENDER_ID', 'PFROMNI'),
            'template_id'   => env('MSG91_TEMPLATE_ID', ''),
        ],
        'dnd_hours' => ['start' => '22:00', 'end' => '08:00'],
    ],

    // -------------------------------------------------------------------------
    // Google APIs
    // -------------------------------------------------------------------------
    'google' => [
        'maps_api_key'      => env('GOOGLE_MAPS_API_KEY', ''),
        'geocoding_key'     => env('GOOGLE_MAPS_GEOCODING_KEY', ''),
        'places_key'        => env('GOOGLE_PLACES_API_KEY', ''),
        'default_center'    => ['lat' => 19.1663, 'lng' => 72.8526], // Malad West
        'default_zoom'      => 13,
    ],

    // -------------------------------------------------------------------------
    // OTA Channel Manager
    // -------------------------------------------------------------------------
    'ota' => [
        'provider'      => env('OTA_PROVIDER', ''),
        'api_key'       => env('OTA_API_KEY', ''),
        'api_secret'    => env('OTA_API_SECRET', ''),
        'api_url'       => env('OTA_API_URL', ''),
        'sync_interval' => 15, // minutes
    ],

    // -------------------------------------------------------------------------
    // Lead Scoring Weights
    // -------------------------------------------------------------------------
    'lead_scoring' => [
        'budget_match'      => 30,
        'location_match'    => 20,
        'timeline'          => 15,
        'engagement'        => 15,
        'source_quality'    => 10,
        'referral'          => 10,
        'hot_threshold'     => 70,
        'warm_threshold'    => 40,
    ],

    // -------------------------------------------------------------------------
    // Cron Job Settings
    // -------------------------------------------------------------------------
    'cron' => [
        'lead_followup_hour'        => 9,   // 9 AM daily
        'emi_reminder_day'          => 1,   // 1st of month
        'rera_renewal_check_day'    => 1,
        'gst_return_alert_day'      => 18,  // 18th of month
        'payroll_run_day'           => 25,
        'attendance_sync_interval'  => 15,  // minutes
        'whatsapp_retry_delay'      => 5,   // minutes
        'report_generation_hour'    => 6,   // 6 AM
    ],

    // -------------------------------------------------------------------------
    // Pagination & UI Defaults
    // -------------------------------------------------------------------------
    'ui' => [
        'per_page'          => 25,
        'per_page_options'  => [10, 25, 50, 100],
        'date_format'       => 'd/m/Y',
        'datetime_format'   => 'd/m/Y H:i',
        'time_format'       => 'H:i',
        'timezone'          => 'Asia/Kolkata',
    ],

    // -------------------------------------------------------------------------
    // Security & Session
    // -------------------------------------------------------------------------
    'security' => [
        'session_timeout'   => 480,         // 8 hours in minutes
        'max_login_attempts'=> 5,
        'lockout_duration'  => 15,          // minutes
        'password_expiry'   => 90,          // days
        'two_factor'        => env('TWO_FACTOR_ENABLED', false),
        'ip_whitelist'      => array_filter(explode(',', env('IP_WHITELIST', ''))),
        'audit_log'         => true,
    ],

];