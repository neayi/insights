<?php


namespace App\Http\Middleware;


use Illuminate\Support\Facades\Auth;

class IsWizardProfileAvailable
{
    public function handle($request, \Closure $next)
    {
        $user = Auth::user();
        if($user->context_id === null){
            return $next($request);
        }
        return redirect('/profile');
    }
}
