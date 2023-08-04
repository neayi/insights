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
        $countryLang = $request->getPreferredLanguage();
        list($lang, ) = explode('_', $countryLang);
        app()->setLocale($lang);
        $user = Auth::user();
        if (!empty($user)) {
            $locale = $user->wiki;
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
