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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'telegram' => [
        'bot_token' => env('TELEGRAM_BOT_TOKEN'),
        'chat_id'   => env('TELEGRAM_CHAT_ID'),
    ],

    'smsmkt' => [
        'api_key'     => env('SMSMKT_API_KEY'),
        'secret_key'  => env('SMSMKT_SECRET_KEY'),
        'project_key' => env('SMSMKT_PROJECT_KEY'),
        'sender'      => env('SMSMKT_SENDER', 'KGM'),
        // ส่ง SMS ข้อความอิสระ (ลิงก์ติดตามออเดอร์) — ใช้เครดิต Broadcast
        'enabled'      => env('SMSMKT_ENABLED', true),
        'project_id'   => env('SMSMKT_PROJECT_ID'),
        // ข้อความไทยใช้ 2 เครดิต/ครั้ง (67 ตัว/เครดิต) ส่วนอังกฤษใช้ 1 (153 ตัว/เครดิต)
        'thai_message' => env('SMSMKT_THAI_MESSAGE', false),
    ],

];
