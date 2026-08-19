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
    'github' => [
        'token' => env('GITHUB_TOKEN'),
    ],
    'binderbyte' => [
        'api_key' => env('BINDERBYTE_API_KEY'),
    ],
    'national_holiday_api' => [
        'url' => env('NATIONAL_HOLIDAY_API_URL', 'https://libur.deno.dev/api'),
    ],
    'attendance_sync' => [
        'default_url' => env('ATTENDANCE_SYNC_URL', 'https://indodacin.nusa.net.id/web/finger/secureapi.php?tipe=insertAttendance'),
        'agrotec_url' => env('ATTENDANCE_SYNC_AGROTEC_URL', 'https://indodacin.nusa.net.id/web/finger/secureapi.php?tipe=insertAttendanceAgrotec'),
    ],
    'face_recognition' => [
        'url' => env('FACE_RECOGNITION_API_URL', 'https://verify.indodacin.com/recognize'),
    ],
    'gemini' => [
        'api_keys' => array_values(array_filter(array_map(
            'trim',
            explode(',', env('GEMINI_API_KEYS', env('GEMINI_API_KEY', '')))
        ))),
        'model' => env('GEMINI_MODEL', 'gemini-2.0-flash'),
    ],
];
