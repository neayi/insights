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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_CALLBACK'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_CALLBACK'),
    ],

    'twitter' => [
        'client_id' => env('TWITTER_CLIENT_ID'),
        'client_secret' => env('TWITTER_CLIENT_SECRET'),
        'redirect' => env('TWITTER_CALLBACK'),
    ],

    'discourse' => [
        // Middleware for the SSO login route to use
        'middleware' => ['web', 'auth', 'check.email.verified'],

        // The route's URI that acts as the entry point for Discourse to start the SSO process.
        // Used by Discourse to route incoming logins.
        'route' => 'neayi/discourse/sso',

        // Secret string used to encrypt/decrypt SSO information,
        // be sure that it is 10 chars or longer
        'secret' => env('DISCOURSE_SECRET'),

        // Disable Discourse from sending welcome message
        'suppress_welcome_message' => 'false',

        // Where the Discourse forum lives
        // will be overridden with the country code if host = insights.en.dev... url => discourse_url_en...
        'url' => env('DISCOURSE_URL_FR'),

        // Api-specific items
        // For logging out of Discourse directly, generate an API key as an "All user key" and put the key here.
        // @see https://meta.discourse.org/t/how-to-create-an-api-key-on-the-admin-panel/87383
        'api' => [
            'key' => env('DISCOURSE_API_KEY'),
            'url' => env('DISCOURSE_API')
        ],

        // User-specific items
        // NOTE: The 'email' & 'external_id' are the only 2 required fields
        'user' => [
            // Check to see if the user has forum access & should be logged in via SSO
            'access' => null,

            // Discourse Groups to make sure that the user is part of in a comma-separated string
            // NOTE: Groups cannot have spaces in their names & must already exist in Discourse
            'add_groups' => null,

            // Boolean for making the user a Discourse admin. Leave null to ignore
            'admin' => null,

            // Full path to user's avatar image
            'avatar_url' => null,

            // The avatar is cached, so this triggers an update
            'avatar_force_update' => null,

            // Content of the user's bio
            'bio' => 'bio',

            // Verified email address (see "require_activation" if not verified)
            'email' => 'email',

            // Unique string for the user that will never change
            'external_id' => 'id',

            // Boolean for making user a Discourse moderator. Leave null to ignore
            'moderator' => null,

            // Full name on Discourse if the user is new or
            // if SiteSetting.sso_overrides_name is set
            'name' => 'fullname',

            // Discourse Groups to make sure that the user is *NOT* part of in a comma-separated string.
            // NOTE: Groups cannot have spaces in their names & must already exist in Discourse
            // There is not a way to specify the exact list of groups that a user is in, so
            // you may want to send the inverse of the 'add_groups'
            'remove_groups' => null,

            // If the email has not been verified, set this to true
            'require_activation' => false,

            // username on Discourse if the user is new or
            // if SiteSetting.sso_overrides_username is set
            'username' => 'discourse_username',
        ],
    ],
];
