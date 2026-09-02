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

    // RS.ge (Georgian Revenue Service) electronic waybill ("ზედნადები") SOAP API.
    // Operation names/auth (su/sp) confirmed against RS.ge's live WSDL
    // (services.rs.ge/WayBillService/WayBillService.asmx?WSDL), September 2026. The exact
    // inner <waybill> field names for save_waybill are NOT in that WSDL (declared as
    // untyped xs:any) — RsGeWaybillService reconstructs them from a third-party open-source
    // client's confirmed get_waybill *response* schema, since RS.ge's save/get shapes are
    // symmetric by convention. Treat as unverified until tested against a real account.
    //
    // No separate RS.ge test/sandbox host was found to exist — 'mode' => 'test' here is a
    // LOCAL dry run (logs the request, never calls RS.ge) rather than a real RS.ge sandbox.
    // Also note: RS.ge requires this server's outbound IP to be allowlisted on their side
    // before any call succeeds, regardless of credentials being correct.
    'rs_ge' => [
        'service_url'   => env('RS_GE_SERVICE_URL', 'http://services.rs.ge/WayBillService/WayBillService.asmx'),
        'su'            => env('RS_GE_SU'),
        'sp'            => env('RS_GE_SP'),
        'seller_tin'    => env('RS_GE_SELLER_TIN'),
        'seller_name'   => env('RS_GE_SELLER_NAME'),
        'start_address' => env('RS_GE_START_ADDRESS'),
        // 'test' = dry run only (default, safe); 'live' = real calls to RS.ge.
        'mode'          => env('RS_GE_MODE', 'test'),
    ],
];
