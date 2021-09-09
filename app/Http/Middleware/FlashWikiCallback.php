<?php

namespace App\Http\Middleware;

use Closure;

class FlashWikiCallback
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(session()->has('wiki_callback')){
            session()->keep(['wiki_callback', 'wiki_token']);
        }

        if($request->has('sso')){
            session()->put('sso', $request->get('sso'));
            session()->put('sig', $request->get('sig'));
        }
        return $next($request);
    }
}
