<?php


namespace App\Http\Middleware;


use Illuminate\Support\Facades\Session;
use Closure;

class SetWikiSessionId
{
    public function handle($request, Closure $next)
    {
        if($request->input('wiki_session_id')){
            Session::put('wiki_session_id', $request->input('wiki_session_id'));
        }
        return $next($request);
    }
}
