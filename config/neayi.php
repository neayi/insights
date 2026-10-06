<?php

return [
    'default_avatar' => 'images/user-solid.png',
    'brevo_api_key' => env('BREVO_API_KEY', env('SENDINBLUE_API_KEY')),
    'mailerlite_api_key' => env('MAILERLITE_API_KEY'),

    // Cloudflare Turnstile captcha on the registration form (disabled when the keys are empty)
    'turnstile' => [
        'site_key' => env('TURNSTILE_SITE_KEY'),
        'secret_key' => env('TURNSTILE_SECRET_KEY'),
    ],

    // Unverified, unused accounts older than this are deleted by users:purge-unverified
    'purge_unverified_users_after_days' => env('PURGE_UNVERIFIED_USERS_AFTER_DAYS', 7),
];

