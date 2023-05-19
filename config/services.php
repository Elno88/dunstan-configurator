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
    'focus' => [
        'live'      => env('FOCUS_API_LIVE', false),
        'jwt_live'  => env('FOCUS_API_JWT_LIVE', ''),
        'jwt_test'  => env('FOCUS_API_JWT_TEST', ''),
        'username_live'  => env('FOCUS_API_USERNAME_LIVE', ''),
        'username_test'  => env('FOCUS_API_USERNAME_TEST', ''),
        'password_live'  => env('FOCUS_API_PASSWORD_LIVE', ''),
        'password_test'  => env('FOCUS_API_PASSWORD_TEST', ''),
        'anstalld_live'  => env('FOCUS_API_ANSTALLD_LIVE', ''), // förmedlare
        'anstalld_test'  => env('FOCUS_API_ANSTALLD_TEST', ''), // förmedlare
        'bankid_status_force_retries' => 0
    ],
    'woocommerce' => [
        'create_user'   => false,
        'live'          => env('WOOCOMMERCE_API_LIVE', false),
        'jwt_live'      => env('WOOCOMMERCE_API_JWT_LIVE', ''),
        'jwt_test'      => env('WOOCOMMERCE_API_JWT_TEST', ''),
    ],
    'dunstan' => [
        'email_test' => env('DUNSTAN_EMAIL_TEST', 'mikael.nilsson@convertor.se'),
        'email_live' => env('DUNSTAN_EMAIL_LIVE', 'support@dunstan.se'),
    ],
    'insurley' => [
        'url'                   => env('INSURLEY_URL', 'https://blocks.insurely.com/'),
        'live'                  => env('INSURLEY_LIVE', false),
        'client_id_live'        => env('INSURLEY_CLIENT_ID_LIVE', null),
        'client_id_gard_live'   => env('INSURLEY_CLIENT_ID_GARD_LIVE', null),
        'client_id_test'        => env('INSURLEY_CLIENT_ID_TEST', null),
        'email_export'          => [
            'enabled'   => env('INSURLEY_EMAIL_EXPORT_ENABLED', false),
            'addresses' => env('INSURLEY_EMAIL_EXPORT_ADDRESSES', null),
            'days'      => env('INSURLEY_EMAIL_EXPORT_DAYS', null),
        ]
    ],
    'google_maps' => [
        'live'        => env('GOOGLE_MAPS_LIVE', false),
        'secret_live' => env('GOOGLE_MAPS_SECRET_LIVE', ''),
        'secret_test' => env('GOOGLE_MAPS_SECRET_TEST', ''),
    ],
    'mailchimp' => [
        'live'        => env('MAILCHIMP_LIVE', false),
        'dc_live' => env('MAILCHIMP_DC_LIVE', 'us5'),
        'dc_test' => env('MAILCHIMP_DC_TEST', 'us5'),
        'key_live' => env('MAILCHIMP_KEY_LIVE', ''),
        'key_test' => env('MAILCHIMP_KEY_TEST', ''),
        'list_live' => env('MAILCHIMP_LIST_LIVE', ''),
        'list_test' => env('MAILCHIMP_LIST_TEST', ''),
    ],
    'papilite' => [
        'live'        => env('PAPILITE_LIVE', false),
        'key_live' => env('PAPILITE_KEY_LIVE', ''),
        'key_test' => env('PAPILITE_KEY_TEST', '')
    ]
];
