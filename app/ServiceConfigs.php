<?php

declare(strict_types=1);

namespace App;


use Illuminate\Support\Facades\Cache;

class ServiceConfigs
{
    public function get(string $lang): array
    {
        $localesConfigs = $this->getLocalesConfig();

        foreach ($localesConfigs as $localeConfig) {
            if($localeConfig->code === $lang){
                return $localeConfig;
            }
        }
    }

    private function getLocalesConfig() : array
    {
        $localesConfigs = Cache::get('localesConfigs');
        if (empty($localesConfigs)) {
            $localesConfigs = LocalesConfig::all()->toArray();
            Cache::put('localesConfigs', $localesConfigs, 60 * 24);
        }

        return $localesConfigs;
    }

    public function all(): array
    {
        $locales = [];
        foreach($this->getLocalesConfig() as $localeConfig){
            $locales[$localeConfig['code']] = $localeConfig;
        }

        return $locales;
    }
}
