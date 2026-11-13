<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
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

    // Analizador de estados de cuenta (.QRP): intérprete de Python que ejecuta python/qrp_parser.py.
    // Ruta completa a python.exe o comando (p. ej. "py -3"). Vacío: "py -3" en Windows, "python3" en Linux.
    'qrp' => [
        'python' => env('QRP_PYTHON'),
    ],

    'api' => [
        'base_url' => rtrim(env('API_URL', 'http://127.0.0.1:5000'), '/'),
        // Token compartido con el backend Flask (cabecera X-API-Token). Debe coincidir con API_TOKEN del backend.
        'token' => env('API_TOKEN'),
    ],

    // 👇 Agrega esto
    'google' => [
        'credentials'     => env('GOOGLE_CREDENTIALS'),
        'sheet_id'        => env('GOOGLE_SHEET_ID'),
        'school_sheet_id' => env('GOOGLE_SCHOOL_SHEET_ID'),
    ],

    'moodle' => [
        'base_url'       => rtrim(env('MOODLE_URL', 'https://moodle.btinteramericano.com'), '/'),
        'token'          => env('MOODLE_TOKEN', '80d35103d66bd559a56a3bffd5893f3b'),
        'role_online_id' => (int)env('MOODLE_ROLE_ONLINE_ID', 9),
    ],

];