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

    /**
     * Returns the locale config for the wiki that best matches the code passed in parameters,
     * and returns a default one (Enligh) if none found. The code may have a country part attached (en_US)
     */
    static public function getLocaleFromCode(string $preferedLanguage)
    {
        $languageParts = explode('_', $preferedLanguage);
        $preferedCode = strtolower($languageParts[0]);

        $localesConfig = LocalesConfig::all();

        $defaultLocale = reset($localesConfig);

        foreach ($localesConfig as $localeConfig) {
            if ($preferedCode == $localeConfig->code)
                return $localeConfig;

            if ('en' == $localeConfig->code)
                $defaultLocale = $localeConfig;
        }

        return $defaultLocale;
    }
}
