<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],


    'kyivID' => [
        'client_id' => env('KYIV_ID_CLIENT'),
        'client_secret' => env('KYIV_ID_SECRET'),
        'redirect' => env('KYIV_ID_REDIRECT_URI'),
        'attempt' => env('KYIV_ID_ATTEMPT_URI'),
        'host' => env('KYIV_ID_HOST'),
        'host_api' => env('KYIV_ID_HOST_API'),
        'force_login' => env('KYIV_ID_FORCE_LOGIN_URI'),
        'logout' => env('KYIV_ID_LOGOUT_URI'),
        'create_order_endpoint' => env('KYIV_ID_CREATE_ORDER'),
    ],

];
