<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Midtrans payment gateway integration.
    | Set MIDTRANS_IS_PRODUCTION=true in .env when going live.
    |
    */

    'server_key'    => env('MIDTRANS_SERVER_KEY', ''),

    'client_key'    => env('MIDTRANS_CLIENT_KEY', ''),

    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    'snap_url' => env('MIDTRANS_IS_PRODUCTION', false)
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js',

    'api_url' => env('MIDTRANS_IS_PRODUCTION', false)
        ? 'https://app.midtrans.com'
        : 'https://app.sandbox.midtrans.com',

];
