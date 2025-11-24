<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Supported SSO Providers
    |--------------------------------------------------------------------------
    |
    | List of SSO providers that are supported by the application.
    |
    */
    'supported_providers' => [
        'oauth2',
        'saml',
        'oidc',
        'azure_ad',
        'google',
        'github',
        'custom',
    ],

    /*
    |--------------------------------------------------------------------------
    | OAuth Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for OAuth-based SSO providers.
    |
    */
    'oauth' => [
        'default_scopes' => ['openid', 'profile', 'email'],
        'redirect_uri' => env('SSO_OAUTH_REDIRECT_URI', '/sso/callback'),
        'state_validation' => env('SSO_OAUTH_STATE_VALIDATION', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | SAML Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for SAML-based SSO providers.
    |
    */
    'saml' => [
        'strict' => env('SSO_SAML_STRICT', true),
        'debug' => env('SSO_SAML_DEBUG', false),
        'sp' => [
            'entity_id' => env('SSO_SAML_SP_ENTITY_ID', 'laravel-sso-sp'),
            'assertion_consumer_service' => [
                'url' => env('SSO_SAML_SP_ACS_URL', '/sso/saml/callback'),
            ],
            'single_logout_service' => [
                'url' => env('SSO_SAML_SP_SLS_URL', '/sso/saml/logout'),
            ],
        ],
        'idp' => [
            'entity_id' => env('SSO_SAML_IDP_ENTITY_ID', ''),
            'single_sign_on_service' => [
                'url' => env('SSO_SAML_IDP_SSO_URL', ''),
            ],
            'single_logout_service' => [
                'url' => env('SSO_SAML_IDP_SLS_URL', ''),
            ],
            'x509cert' => env('SSO_SAML_IDP_CERT', ''),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Group Mapping Rules
    |--------------------------------------------------------------------------
    |
    | Rules for mapping external groups to internal roles.
    |
    */
    'group_mapping' => [
        'enabled' => env('SSO_GROUP_MAPPING_ENABLED', true),
        'default_role' => env('SSO_DEFAULT_ROLE', 'user'),
        'rules' => [
            // Example mapping rules
            // 'admin-group' => 'admin',
            // 'manager-group' => 'manager',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | SSO Session Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for SSO sessions.
    |
    */
    'session' => [
        'lifetime' => env('SSO_SESSION_LIFETIME', 7200), // 2 hours
        'refresh_interval' => env('SSO_SESSION_REFRESH_INTERVAL', 3600), // 1 hour
    ],

    /*
    |--------------------------------------------------------------------------
    | SSO Security Settings
    |--------------------------------------------------------------------------
    |
    | Security-related configuration for SSO.
    |
    */
    'security' => [
        'token_encryption' => env('SSO_TOKEN_ENCRYPTION', true),
        'signature_verification' => env('SSO_SIGNATURE_VERIFICATION', true),
        'replay_attack_protection' => env('SSO_REPLAY_ATTACK_PROTECTION', true),
    ],
];