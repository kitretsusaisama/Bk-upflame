<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OTP Length
    |--------------------------------------------------------------------------
    |
    | The length of the OTP code to be generated.
    |
    */
    'length' => env('OTP_LENGTH', 6),

    /*
    |--------------------------------------------------------------------------
    | OTP Expiry Time
    |--------------------------------------------------------------------------
    |
    | The time in seconds after which the OTP will expire.
    |
    */
    'expiry' => env('OTP_EXPIRY', 300), // 5 minutes

    /*
    |--------------------------------------------------------------------------
    | OTP Channels
    |--------------------------------------------------------------------------
    |
    | The channels through which OTP can be sent.
    |
    */
    'channels' => [
        'email',
        'sms',
        'whatsapp',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default OTP Channel
    |--------------------------------------------------------------------------
    |
    | The default channel to use for sending OTP.
    |
    */
    'default_channel' => env('OTP_DEFAULT_CHANNEL', 'email'),

    /*
    |--------------------------------------------------------------------------
    | Rate Limits
    |--------------------------------------------------------------------------
    |
    | Configuration for rate limiting OTP requests.
    |
    */
    'rate_limits' => [
        'requests_per_hour' => env('OTP_REQUESTS_PER_HOUR', 5),
        'lockout_duration' => env('OTP_LOCKOUT_DURATION', 3600), // 1 hour
        'max_attempts' => env('OTP_MAX_ATTEMPTS', 3),
    ],

    /*
    |--------------------------------------------------------------------------
    | OTP Generation Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for OTP generation.
    |
    */
    'generation' => [
        'type' => env('OTP_GENERATION_TYPE', 'numeric'), // numeric, alphanumeric
        'include_special_chars' => env('OTP_INCLUDE_SPECIAL_CHARS', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | OTP Validation Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for OTP validation.
    |
    */
    'validation' => [
        'case_sensitive' => env('OTP_CASE_SENSITIVE', false),
        'allow_expired' => env('OTP_ALLOW_EXPIRED', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | OTP Templates
    |--------------------------------------------------------------------------
    |
    | Templates for OTP messages.
    |
    */
    'templates' => [
        'email' => [
            'subject' => 'Your OTP Code',
            'body' => 'Your OTP code is: :code. This code will expire in :expiry minutes.',
        ],
        'sms' => [
            'body' => 'Your OTP code is: :code. Expires in :expiry minutes.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | OTP Security Settings
    |--------------------------------------------------------------------------
    |
    | Security-related configuration for OTP.
    |
    */
    'security' => [
        'encryption_enabled' => env('OTP_ENCRYPTION_ENABLED', true),
        'hash_algorithm' => env('OTP_HASH_ALGORITHM', 'sha256'),
        'store_hash_only' => env('OTP_STORE_HASH_ONLY', true),
    ],
];