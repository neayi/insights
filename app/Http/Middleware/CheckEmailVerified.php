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
            $request->session()->flash('from_forum', true);
            return redirect()->route('verification.notice');
        }
        return $next($request);
    }
}
