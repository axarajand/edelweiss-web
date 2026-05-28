<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_NOTIFICATIONS_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Edelweiss ML Service
    |--------------------------------------------------------------------------
    | URL FastAPI service untuk YOLOv11 + MLP.
    */
    'ml_api' => [
        'url' => env('ML_API_URL', 'http://127.0.0.1:8001'),
    ],

];
