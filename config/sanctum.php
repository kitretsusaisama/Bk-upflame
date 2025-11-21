<?php

use App\Http\Middleware\EncryptCookies;

return [

    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', implode(',', array_filter([
        'localhost',
        'localhost:3000',
        '127.0.0.1',
        '127.0.0.1:8000',
        '::1',
        env('APP_URL') ? parse_url(env('APP_URL'), PHP_URL_HOST) : null,
        env('FRONTEND_URL') ? parse_url(env('FRONTEND_URL'), PHP_URL_HOST) : null,
    ])))),

    'guard' => ['web'],

    'expiration' => env('SANCTUM_EXPIRATION'),

    'token_prefix' => env('SANCTUM_TOKEN_PREFIX', ''),

    'tokenable_morph_key_type' => 'string',

    'middleware' => [
        'verify_csrf_token' => App\Http\Middleware\VerifyCsrfToken::class,
        'encrypt_cookies' => EncryptCookies::class,
    ],

];

