<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if($request->has('wiki_callback') && Auth::user() !== null){
            return $this->redirectToWiki($request);
        }

        if (Auth::guard($guard)->check()) {
            $wikiUrl = Auth::user()->wikiUrl();
            return redirect($wikiUrl);
        }

        return $next($request);
    }

    private function redirectToWiki($request)
    {
        $user = Auth::user();
        $user->wiki_token = $request->input('wiki_token');
        $user->save();
        $callback = urldecode($request->input('wiki_callback'));
        return redirect($callback);
    }
}
