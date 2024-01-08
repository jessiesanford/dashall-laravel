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
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('pk_test_GnWoYt2RHjnjIOWSmYdE8Wjz'),
        'secret' => env('sk_test_LSTGKZJRLMy5UEO6DaWbf7Ge'),
    ],

//    'stripe' => [
//        'model' => App\User::class,
//        'key' => env('pk_live_y3TWKdVCTUXH4VXTJ7rXvO7d'),
//        'secret' => env('sk_live_CQR5QJhGPg3Xg57OgPFrqO8s'),
//    ],

];
