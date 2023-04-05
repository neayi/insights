<?php

declare(strict_types=1);

namespace App\Http\Controllers\Discourse;

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Http\Request;

class SsoController extends \Spinen\Discourse\Controllers\SsoController
{
    protected function loadConfigs(Config $config): void
    {
        list(,$countryCode,) = explode('/', request()->getRequestUri());
        $configs = $config->get('services.discourse');
        $configs['url'] = env('DISCOURSE_URL_'.strtoupper($countryCode));

        $this->config = collect($configs);
        $this->config->put('user', collect($this->config->get('user')));
    }

    public function login(Request $request)
    {
        return parent::login($request);
    }
}
