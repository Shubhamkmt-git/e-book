<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'easebuzz' => [
        'key' => env('EASEBUZZ_KEY'),
        'salt' => env('EASEBUZZ_SALT'),
        'webhook_secret' => env('EASEBUZZ_WEBHOOK_SECRET'),
        'environment' => env('EASEBUZZ_ENV', 'test'),
        'test_url' => env('EASEBUZZ_TEST_URL', 'https://testpay.easebuzz.in/payment/initiateLink'),
        'production_url' => env('EASEBUZZ_PRODUCTION_URL', 'https://pay.easebuzz.in/payment/initiateLink'),
    ],

    'razorpay' => [
        'key' => env('RAZORPAY_KEY'),
        'secret' => env('RAZORPAY_SECRET'),
        'webhook_secret' => env('RAZORPAY_WEBHOOK_SECRET'),
    ],

    'tinymce' => [
        'key' => env('TINYMCE_API_KEY', 'no-api-key'),
    ],

];
