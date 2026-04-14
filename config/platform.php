<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Company Profile
    |--------------------------------------------------------------------------
    */
    'company' => [
        'name' => env('APP_NAME', 'Property Finder Real Estate'),
        'entity_type' => 'Proprietorship', // As per your firm's setup
        'location' => 'Mumbai, Maharashtra',
        'timezone' => 'Asia/Kolkata',
        'establishment_date' => '2018-08-01',
    ],

    /*
    |--------------------------------------------------------------------------
    | Finance & Tax Settings (GST 18%)
    |--------------------------------------------------------------------------
    */
    'finance' => [
        'currency' => 'INR',
        'currency_symbol' => '₹',
        'gst_rate' => 18, // Total GST percentage
        'cgst_rate' => 9,
        'sgst_rate' => 9,
        'hsn_code_services' => '9972', // Real Estate Services HSN
    ],

    /*
    |--------------------------------------------------------------------------
    | Real Estate Compliance (MahaRERA)
    |--------------------------------------------------------------------------
    */
    'compliance' => [
        'state' => 'Maharashtra',
        'authority' => 'MahaRERA',
        'default_agreement_type' => 'Leave and License',
        'stamp_duty_required' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Lead & Contact Management
    |--------------------------------------------------------------------------
    | Settings for your custom CRM requirements
    */
    'crm' => [
        'allow_multiple_contacts' => true, // Support for multiple phone/emails
        'categories' => [
            'Doctor',
            'Lawyer',
            'Real Estate Agent',
            'Investor',
            'End User',
        ],
        'lead_stages' => [
            'New',
            'Contacted',
            'Site Visit',
            'Negotiation',
            'Closed',
            'Lost',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Feature Flags
    |--------------------------------------------------------------------------
    | Toggle modules of your modular ERP
    */
    'features' => [
        'inventory_management' => true,
        'financial_services' => true,
        'whatsapp_integration' => env('FEATURE_WHATSAPP', false),
        'document_generator' => true, // For Partnership Deeds/Tenancy Agreements
    ],

];