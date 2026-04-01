<?php

return [

    /*
    |--------------------------------------------------------------------------
    | WhatsApp Business API Configuration
    |--------------------------------------------------------------------------
    | Meta (Facebook) WhatsApp Business Cloud API integration for the
    | PFRE Omni Platform — lead nurturing, payment reminders, agreement
    | sharing, site visit confirmations, and broadcast campaigns.
    */

    // -------------------------------------------------------------------------
    // Core API Settings
    // -------------------------------------------------------------------------
    'api_url'               => env('WHATSAPP_API_URL', 'https://graph.facebook.com/v18.0'),
    'phone_number_id'       => env('WHATSAPP_PHONE_NUMBER_ID', ''),
    'access_token'          => env('WHATSAPP_ACCESS_TOKEN', ''),
    'business_account_id'   => env('WHATSAPP_BUSINESS_ACCOUNT_ID', ''),

    // -------------------------------------------------------------------------
    // Webhook
    // -------------------------------------------------------------------------
    'webhook' => [
        'verify_token'  => env('WHATSAPP_WEBHOOK_VERIFY_TOKEN', ''),
        'endpoint'      => '/api/webhooks/whatsapp',
        'events'        => [
            'messages',
            'message_deliveries',
            'message_reads',
            'messaging_postbacks',
        ],
    ],

    // -------------------------------------------------------------------------
    // Sender Profile
    // -------------------------------------------------------------------------
    'sender' => [
        'display_name'  => env('PFRE_COMPANY_NAME', 'Property Finder Real Estate'),
        'phone'         => env('WHATSAPP_SENDER_PHONE', ''),
    ],

    // -------------------------------------------------------------------------
    // Approved Message Templates
    // -------------------------------------------------------------------------
    // Keys map to your Meta-approved template names.
    // Update 'name' values to exactly match your approved template names.
    'templates' => [

        // Lead & CRM
        'lead_welcome' => [
            'name'      => 'pfre_lead_welcome',
            'language'  => 'en',
            'components' => ['header', 'body', 'cta_button'],
        ],
        'lead_followup' => [
            'name'      => 'pfre_lead_followup',
            'language'  => 'en',
            'components' => ['body'],
        ],
        'site_visit_confirmation' => [
            'name'      => 'pfre_site_visit_confirm',
            'language'  => 'en',
            'components' => ['header', 'body', 'cta_button'],
        ],
        'site_visit_reminder' => [
            'name'      => 'pfre_site_visit_reminder',
            'language'  => 'en',
            'components' => ['body'],
        ],

        // Bookings & Sales
        'booking_confirmation' => [
            'name'      => 'pfre_booking_confirm',
            'language'  => 'en',
            'components' => ['header', 'body', 'footer'],
        ],
        'token_received' => [
            'name'      => 'pfre_token_receipt',
            'language'  => 'en',
            'components' => ['body', 'footer'],
        ],
        'agreement_ready' => [
            'name'      => 'pfre_agreement_ready',
            'language'  => 'en',
            'components' => ['header', 'body', 'cta_button'],
        ],
        'registration_schedule' => [
            'name'      => 'pfre_registration_schedule',
            'language'  => 'en',
            'components' => ['header', 'body'],
        ],

        // Payments
        'payment_due' => [
            'name'      => 'pfre_payment_due',
            'language'  => 'en',
            'components' => ['body', 'cta_button'],
        ],
        'payment_overdue' => [
            'name'      => 'pfre_payment_overdue',
            'language'  => 'en',
            'components' => ['body', 'footer'],
        ],
        'payment_receipt' => [
            'name'      => 'pfre_payment_receipt',
            'language'  => 'en',
            'components' => ['header', 'body', 'footer'],
        ],
        'emi_reminder' => [
            'name'      => 'pfre_emi_reminder',
            'language'  => 'en',
            'components' => ['body', 'cta_button'],
        ],

        // Possession & Handover
        'possession_notice' => [
            'name'      => 'pfre_possession_notice',
            'language'  => 'en',
            'components' => ['header', 'body'],
        ],
        'handover_scheduled' => [
            'name'      => 'pfre_handover_scheduled',
            'language'  => 'en',
            'components' => ['body', 'cta_button'],
        ],

        // Maintenance
        'maintenance_due' => [
            'name'      => 'pfre_maintenance_due',
            'language'  => 'en',
            'components' => ['body', 'cta_button'],
        ],
        'maintenance_receipt' => [
            'name'      => 'pfre_maintenance_receipt',
            'language'  => 'en',
            'components' => ['body', 'footer'],
        ],
        'complaint_registered' => [
            'name'      => 'pfre_complaint_registered',
            'language'  => 'en',
            'components' => ['body'],
        ],
        'complaint_resolved' => [
            'name'      => 'pfre_complaint_resolved',
            'language'  => 'en',
            'components' => ['body'],
        ],

        // HR / Payroll
        'payslip_ready' => [
            'name'      => 'pfre_payslip_ready',
            'language'  => 'en',
            'components' => ['body', 'cta_button'],
        ],
        'leave_approved' => [
            'name'      => 'pfre_leave_approved',
            'language'  => 'en',
            'components' => ['body'],
        ],
        'leave_rejected' => [
            'name'      => 'pfre_leave_rejected',
            'language'  => 'en',
            'components' => ['body'],
        ],

        // Broadcasts / Campaigns
        'new_project_launch' => [
            'name'      => 'pfre_project_launch',
            'language'  => 'en',
            'components' => ['header', 'body', 'cta_button'],
        ],
        'offer_promotion' => [
            'name'      => 'pfre_offer_promo',
            'language'  => 'en',
            'components' => ['header', 'body', 'cta_button'],
        ],
    ],

    // -------------------------------------------------------------------------
    // Interactive Message Types
    // -------------------------------------------------------------------------
    'interactive' => [
        'site_visit_cta' => [
            'type'          => 'cta_url',
            'button_text'   => 'Book Site Visit',
        ],
        'payment_link_cta' => [
            'type'          => 'cta_url',
            'button_text'   => 'Pay Now',
        ],
    ],

    // -------------------------------------------------------------------------
    // Media Settings
    // -------------------------------------------------------------------------
    'media' => [
        'brochure_url'      => env('APP_URL', '') . '/storage/brochures/',
        'floor_plan_url'    => env('APP_URL', '') . '/storage/floor_plans/',
        'max_image_size_mb' => 5,
        'max_doc_size_mb'   => 100,
        'allowed_doc_types' => ['pdf', 'docx', 'xlsx'],
    ],

    // -------------------------------------------------------------------------
    // Rate Limits & Retry
    // -------------------------------------------------------------------------
    'rate_limit' => [
        'messages_per_second'   => 80,      // Meta tier-based
        'messages_per_day'      => 100000,
    ],
    'retry' => [
        'enabled'       => true,
        'max_attempts'  => 3,
        'delay_seconds' => 300,             // 5 minutes between retries
    ],

    // -------------------------------------------------------------------------
    // Opt-out / DND
    // -------------------------------------------------------------------------
    'dnd' => [
        'keywords'  => ['STOP', 'UNSUBSCRIBE', 'OPT OUT', 'CANCEL'],
        'reply'     => 'You have been unsubscribed from PFRE notifications. Reply START to re-subscribe.',
    ],
    'optin' => [
        'keywords'  => ['START', 'SUBSCRIBE', 'YES'],
        'reply'     => 'Welcome back! You are now subscribed to PFRE notifications.',
    ],

    // -------------------------------------------------------------------------
    // Logging
    // -------------------------------------------------------------------------
    'logging' => [
        'enabled'       => env('WHATSAPP_LOG_ENABLED', true),
        'log_channel'   => 'whatsapp',
        'log_inbound'   => true,
        'log_outbound'  => true,
    ],

];