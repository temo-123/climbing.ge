<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],




    'facebook' => [ 
        'client_id' => env ('FACEBOOK_CLIENT_ID'), 
        'client_secret' => env ('FACEBOOK_CLIENT_SECRET'), 
        // 'redirect' => env('APP_SSH' , 'https://') . env('USER_PAGE_URL', 'user.climbing.ge').'/api/login/facebook/callback',
        'redirect' => env('FACEBOOK_URL'),
    ],
    'google' => [
        'client_id' => env ('GOOGLE_CLIENT_ID'),
        'client_secret' => env ('GOOGLE_CLIENT_SECRET'),
        // 'redirect' => env('APP_SSH' , 'https://') . env('USER_PAGE_URL', 'user.climbing.ge').'/api/login/google/callback',
        'redirect' => env('GOOGLE_URL'),
    ],

    'tbc_pay' => [
        'base_url' => env('TBC_PAY_BASE_URL', 'https://api.tbcbank.ge/v1/tpay'),
        'shop' => [
            'client_id'     => env('TBC_PAY_SHOP_CLIENT_ID'),
            'client_secret' => env('TBC_PAY_SHOP_CLIENT_SECRET'),
        ],
        'donation' => [
            'client_id'     => env('TBC_PAY_DONATION_CLIENT_ID'),
            'client_secret' => env('TBC_PAY_DONATION_CLIENT_SECRET'),
        ],
    ],
];
