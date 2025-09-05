<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SelectLocale
{
    public function handle($request, Closure $next)
    {
        $locale = \App\LocalesConfig::getLocaleFromCode($request->getPreferredLanguage());

        app()->setLocale($locale->code);
        $user = Auth::user();
        if (!empty($user)) {
            $locale = $user->default_locale;
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
