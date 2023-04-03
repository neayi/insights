<?php

return [
    'wiki_url' => env('WIKI_URL'),
    'forum_url' => str_replace('wiki', 'forum', env('WIKI_URL')),
    'default_avatar' => 'images/user-solid.png',
    'mailchimp_api_key' => env('MAILCHIMP_API_KEY'),
    'sendinblue_api_key' => env('SENDINBLUE_API_KEY'),
];
