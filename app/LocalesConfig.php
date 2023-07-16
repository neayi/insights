<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocalesConfig extends Model
{
    protected $table = 'locales_config';

    protected $fillable = [
        'code',
        'wiki_url',
        'wiki_api_url',
        'forum_url',
        'forum_api_url',
        'forum_api_secret',
        'forum_api_key',
        'lang_name',
    ];
}
