<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\TechnicalException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

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
     * and returns a default one (English) if none found. The code may have a country part attached (en_US)
     */
    static public function getLocaleFromCode($preferredLanguage = ''): self
    {
        if (empty($preferredLanguage)) {
            $preferredLanguage = 'fr'; // In case called from the command line specifically
        }

        $languageParts = explode('_', $preferredLanguage);
        $preferredCode = strtolower($languageParts[0]);

        $localesConfig = LocalesConfig::all();

        if ($localesConfig->isEmpty()) {
            throw new TechnicalException("LocalesConfig is empty - did you forget to seed the DB?", 1);
        }

        // by default, we return the first locale found, but later if we find English we'll return English by default
        $defaultLocale = reset($localesConfig);

        foreach ($localesConfig as $localeConfig) {
            if ($preferredCode == $localeConfig->code) {
                return $localeConfig;
            }

            if ('en' == $localeConfig->code) {
                $defaultLocale = $localeConfig;
            }
        }

        return $defaultLocale;
    }

    static public function getPreferredLocale(): self
    {
        return self::getLocaleFromCode(Request::getPreferredLanguage());
    }
}
