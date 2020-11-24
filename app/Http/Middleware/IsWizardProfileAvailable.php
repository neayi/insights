<?php


namespace App\Http\Middleware;


use Illuminate\Support\Facades\Auth;

class IsWizardProfileAvailable
{
    public function handle($request, \Closure $next)
    {
        $user = Auth::user();
        if($user->exploitation_id === null){
            return $next($request);
        }
        if(session()->has('wiki_callback')){
            $user->wiki_token = session()->get('wiki_token');
            $user->save();
            $callback = urldecode(session()->get('wiki_callback'));
            return redirect($callback);
        }
        return redirect(config('neayi.wiki_url'));
    }
}
