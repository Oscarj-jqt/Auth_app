<?php

/**
 * Paramètres de configuration (client id, secret, Twilio, etc.)
 */

return [
    // GitHub OAuth
    'github_client_id' => getenv('GITHUB_CLIENT_ID') ?: 'monid',
    'github_client_secret' => getenv('GITHUB_CLIENT_SECRET') ?: 'monsecret',
    'github_redirect_uri' => getenv('GITHUB_REDIRECT_URI') ?: 'https://localhost/public/oauth/public/callback.php',
    'github_scope' => 'read:user',

    // JSON "DB"
    'users_file' => __DIR__ . '/../data/users.json',

    // JWT
    'jwt_secret' => getenv('JWT_SECRET') ?: 'replace_with_a_strong_secret',
    'jwt_issuer' => 'your-app',
    'jwt_ttl' => 3600, // seconds

    // Twilio / Mail placeholders
    'twilio' => [
        'sid' => getenv('TWILIO_SID') ?: '',
        'token' => getenv('TWILIO_TOKEN') ?: '',
        'from' => getenv('TWILIO_FROM') ?: '',
    ],
    'mail' => [
        'from' => 'jacquetoscar0@gmail.com',
        'password' => getenv('MAIL_PASSWORD') ?: ''
    ]
];