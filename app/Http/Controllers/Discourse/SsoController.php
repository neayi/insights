<?php

declare(strict_types=1);

namespace App\Http\Controllers\Discourse;

use App\LocalesConfig;
use Cviebrock\DiscoursePHP\SSOHelper;
use Illuminate\Contracts\Auth\Authenticatable as User;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Process the Discourse SSO request.
 *
 * Formerly based on spinen/laravel-discourse-sso (unmaintained, no Laravel 12 support),
 * now inlined here on top of cviebrock/discourse-php.
 */
class SsoController extends Controller
{
    protected Collection $config;

    protected SSOHelper $sso;

    protected User $user;

    public function __construct(Config $config, SSOHelper $sso)
    {
        $this->loadConfigs($config);

        $this->sso = $sso->setSecret($this->config->get('secret'));
    }

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

    /**
     * Process the SSO login request from Discourse
     */
    public function login(Request $request)
    {
        $this->user = $request->user();
        $access = $this->config->get('user')->get('access');

        if (!is_null($access) && !$this->parseUserValue($access)) {
            abort(403);
        }

        if (!($this->sso->validatePayload($payload = $request->get('sso'), $request->get('sig')))) {
            abort(403);
        }

        $query = $this->sso->getSignInString(
            $this->sso->getNonce($payload),
            $this->parseUserValue($this->config->get('user')->get('external_id')),
            $this->parseUserValue($this->config->get('user')->get('email')),
            $this->buildExtraParameters()
        );

        return redirect(Str::finish($this->config->get('url'), '/').'session/sso_login?'.$query);
    }

    /**
     * Build out the extra parameters to send to Discourse
     */
    protected function buildExtraParameters(): array
    {
        return $this->config->get('user')
                            ->except(['access', 'email', 'external_id'])
                            ->reject([$this, 'nullProperty'])
                            ->map([$this, 'parseUserValue'])
                            ->reject([$this, 'nullProperty']) // Filter out null values again after parsing
                            ->map([$this, 'castBooleansToString'])
                            ->toArray();
    }

    /**
     * The Discourse SSO API does not accept 0 or 1 for false or true, so send "false" or "true".
     */
    public function castBooleansToString(string|bool $property): string
    {
        if (!is_bool($property)) {
            return $property;
        }

        return $property ? 'true' : 'false';
    }

    public function nullProperty(?string $property): bool
    {
        return is_null($property);
    }

    /**
     * If a string is passed in, get it from the user object, otherwise return what was given
     */
    public function parseUserValue($property)
    {
        if (!is_string($property)) {
            return $property;
        }

        return $this->user->{$property};
    }
}
