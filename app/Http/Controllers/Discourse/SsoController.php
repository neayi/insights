<?php

declare(strict_types=1);

namespace App\Http\Controllers\Discourse;

use App\LocalesConfig;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Http\Request;
use \Spinen\Discourse\Controllers\SsoController as BaseSsoController;

class SsoController extends BaseSsoController
{
    protected function loadConfigs(Config $config): void
    {
        list(,$wikiCode,) = explode('/', request()->getRequestUri());
        $localesConfig = LocalesConfig::query()->where('code', $wikiCode)->first();
        $configs = $config->get('services.discourse');

        if (empty($localesConfig)) {
            $localesConfig = LocalesConfig::query()->where('code', 'fr')->first();
        }

        $configs['url'] = $localesConfig->forum_url;
        $configs['secret'] = $localesConfig->forum_api_secret;
        $configs['api']['key'] = $localesConfig->forum_api_key;
        $configs['api']['url'] = $localesConfig->forum_api_url;

        $this->config = collect($configs);
        $this->config->put('user', collect($this->config->get('user')));
    }

    public function login(Request $request)
    {
        return parent::login($request);
    }
}
