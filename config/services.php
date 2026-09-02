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

    'seo' => [
        'google_site_verification' => [
            'site'   => env('GOOGLE_SITE_VERIFICATION'),
            'shop'   => env('GOOGLE_SITE_VERIFICATION_SHOP'),
            'summit' => env('GOOGLE_SITE_VERIFICATION_SUMMIT'),
            'films'  => env('GOOGLE_SITE_VERIFICATION_FILMS'),
            'blog'   => env('GOOGLE_SITE_VERIFICATION_BLOG'),
        ],
        'bing_site_verification' => [
            'site'   => env('BING_SITE_VERIFICATION'),
            'shop'   => env('BING_SITE_VERIFICATION_SHOP'),
            'summit' => env('BING_SITE_VERIFICATION_SUMMIT'),
            'films'  => env('BING_SITE_VERIFICATION_FILMS'),
            'blog'   => env('BING_SITE_VERIFICATION_BLOG'),
        ],
    ],

    'analytics' => [
        'site'   => env('MIX_CLIMBING_GUIDBOOK_ANALITICS_ID'),
        'shop'   => env('MIX_SHOP_ANALITICS_ID'),
        'summit' => env('MIX_SUMMIT_ANALITICS_ID'),
        'films'  => env('MIX_FILMS_ANALITICS_ID'),
        'blog'   => env('MIX_BLOG_ANALITICS_ID'),
        'user'   => env('MIX_USER_ANALITICS_ID'),
        'error'  => env('MIX_ERROR_PAGE_ANALITICS_ID'),
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

    // Verified against QuickShipper's live OpenAPI spec (https://delivery.quickshipper.app/swagger/v1/swagger.json),
    // September 2026. OAuth2 client_credentials grant against auth.quickshipper.app, Bearer token on /v1/* calls.
    'quickshipper' => [
        'base_url'      => env('QUICKSHIPPER_BASE_URL', 'https://delivery.quickshipper.app'),
        'token_url'     => env('QUICKSHIPPER_TOKEN_URL', 'https://auth.quickshipper.app/connect/token'),
        'client_id'     => env('QUICKSHIPPER_CLIENT_ID'),
        'client_secret' => env('QUICKSHIPPER_CLIENT_SECRET'),
        // Optional: force a specific courier/provider id (GET /v1/Provider/list for the list).
        // Leave unset to let QuickShipper auto-assign (autoAssign: true).
        'provider_id'   => env('QUICKSHIPPER_PROVIDER_ID'),
        // Pickup point — this shop's own warehouse/office, sent as pickUpInfo on every order.
        'pickup' => [
            'name'      => env('QUICKSHIPPER_PICKUP_NAME'),
            'phone'     => env('QUICKSHIPPER_PICKUP_PHONE'),
            'address'   => env('QUICKSHIPPER_PICKUP_ADDRESS'),
            'city'      => env('QUICKSHIPPER_PICKUP_CITY', 'Tbilisi'),
            'country'   => env('QUICKSHIPPER_PICKUP_COUNTRY', 'Georgia'),
            'latitude'  => env('QUICKSHIPPER_PICKUP_LATITUDE', 0),
            'longitude' => env('QUICKSHIPPER_PICKUP_LONGITUDE', 0),
        ],
    ],
];
