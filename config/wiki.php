<?php

return [
    'api_uri' => env('WIKI_API_URI'),
    'wiki_root_url' => str_replace('api.php', '', env('WIKI_API_URI'))
];
