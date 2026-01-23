<?php
/**
 * PayPal Setting & API Credentials
 * Updated for PayPal API v2 (Orders API)
 */

return [
    'mode'    => env('PAYPAL_MODE', 'sandbox'), // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
    'sandbox' => [
        'client_id'     => env('PAYPAL_SANDBOX_CLIENT_ID', ''),
        'client_secret' => env('PAYPAL_SANDBOX_CLIENT_SECRET', ''),
        'app_id'        => 'APP-80W284485P519543T', // Used for testing Adaptive Payments API in sandbox mode
        // Legacy credentials (for backward compatibility)
        'username'      => env('PAYPAL_SANDBOX_API_USERNAME', ''),
        'password'      => env('PAYPAL_SANDBOX_API_PASSWORD', ''),
        'secret'        => env('PAYPAL_SANDBOX_API_SECRET', ''),
        'certificate'   => env('PAYPAL_SANDBOX_API_CERTIFICATE', ''),
    ],
    'live' => [
        'client_id'     => env('PAYPAL_LIVE_CLIENT_ID', ''),
        'client_secret' => env('PAYPAL_LIVE_CLIENT_SECRET', ''),
        'app_id'        => env('PAYPAL_LIVE_APP_ID', ''),
        // Legacy credentials (for backward compatibility)
        'username'      => env('PAYPAL_LIVE_API_USERNAME', ''),
        'password'      => env('PAYPAL_LIVE_API_PASSWORD', ''),
        'secret'        => env('PAYPAL_LIVE_API_SECRET', ''),
        'certificate'   => env('PAYPAL_LIVE_API_CERTIFICATE', ''),
    ],

    'payment_action' => 'Sale', // Can only be 'Sale', 'Authorization' or 'Order'
    'currency'       => env('PAYPAL_CURRENCY', 'USD'),
    'billing_type'   => 'MerchantInitiatedBilling',
    'notify_url'     => '', // Change this accordingly for your application.
    'locale'         => '', // force gateway language  i.e. it_IT, es_ES, en_US
    'validate_ssl'   => true, // Validate SSL when creating api client.
];
