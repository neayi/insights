<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckEmailVerified
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if(!$user->hasVerifiedEmail()){
            // Keep the Discourse SSO payload so that the verification email links back to the right forum
            if($request->has('sso')){
                session()->put('sso', $request->get('sso'));
                session()->put('sig', $request->get('sig'));
                session()->put('wikiCode', strtolower((string) $request->route('wikiCode', '')));
            }
            $request->session()->flash('from_forum', true);
            return redirect()->route('verification.notice');
        }
        return $next($request);
    }
}
