<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Src\UseCases\Domain\Auth\LogUserFromSocialNetwork;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = 'profile';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm(Request $request)
    {
        if($request->has('wiki_callback')){
            session()->flash('wiki_callback', $request->input('wiki_callback'));
            session()->flash('wiki_token', $request->input('wiki_token'));
        }
        return view('public.auth.login');
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new Response('', 204)
            : redirect('/');
    }

    protected function loggedOut(Request $request)
    {
        $request->session()->reflash();
    }

    protected function authenticated(Request $request, $user)
    {
        if (empty($user->wiki)) {
            $locale = \App\LocalesConfig::getPreferredLocale();
            $user->wiki = $locale->code;
            $user->save();
        }

        if($user->context_id === null){
            return redirect()->route('wizard.profile');
        }

        if($request->session()->has('sso')){
            if(!$user->hasVerifiedEmail()){
                $request->session()->flash('from_forum', true);
                return redirect()->route('email.verify');
            }
            $sso = $request->session()->get('sso');
            $sig = $request->session()->get('sig');
            $wikiCode = $request->session()->get('wikiCode');
            return redirect($wikiCode.'/neayi/discourse/sso?sso='.$sso.'&sig='.$sig);
        }

        if($request->session()->has('wiki_callback')){
            $user->wiki_token = $request->session()->get('wiki_token');
            $user->save();
            $callback = urldecode($request->session()->get('wiki_callback'));
            if(!$user->hasVerifiedEmail()){
                return redirect()->route('verification.notice');
            }
            return redirect($callback);
        }

        return redirect()->route('show.profile');
    }

    public function redirectToProvider(string $provider)
    {
        if($provider === 'twitter'){
            config(['services.'.$provider.'.redirect' => env(strtoupper($provider).'_CALLBACK_LOGIN')]);
            return Socialite::driver($provider)->redirect();
        }

        config(['services.'.$provider.'.redirect' => env(strtoupper($provider).'_CALLBACK_LOGIN')]);
        return Socialite::driver($provider)->redirectUrl(config('services.'.$provider.'.redirect'))->redirect();
    }

    public function handleProviderCallback(string $provider, LogUserFromSocialNetwork $logUserFromSocialNetwork)
    {
        config(['services.'.$provider.'.redirect' => env(strtoupper($provider).'_CALLBACK_LOGIN')]);
        $logUserFromSocialNetwork->log($provider);
        return $this->authenticated(request(), Auth::user());
    }
}
