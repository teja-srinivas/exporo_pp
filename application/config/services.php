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

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Models\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'sendgrid' => [
        'api_key' => env('SENDGRID_API_KEY'),
        'templateIds' => [
            'registration' => 'd-7af9c120317d46ddb816029e4f99b155',
            'approved' => 'd-c213a499a1a94daeaecb37c2599887f3',
            'declined' => 'd-db5a6ee091c947df8635616a894b70ed',
            'commissionCreated' => 'd-32e6df395c1d43c0b2af4849405c2235',
            'resetPassword' => 'd-355e05b34e8a4b348fd7ab4269cf649d',
        ],
    ],

    'docraptor' => [
        'api_key' => env('DOCRAPTOR_APP_KEY'),
        'test' => env('DOCRAPTOR_DEBUG', true),
    ],

    'gtm' => [
        'key' => env('GOOGLE_TAG_ID'),
    ],
];
