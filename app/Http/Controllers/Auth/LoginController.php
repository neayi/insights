<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Src\UseCases\Domain\Auth\LogUserFromSocialNetwork;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm(Request $request)
    {
        session()->reflash();
        if($request->has('wiki_callback')){
            session()->flash('wiki_callback', $request->input('wiki_callback'));
            session()->flash('wiki_token', $request->input('wiki_token'));
        }
        return view('public.auth.login');
    }

    public function logout(Request $request)
    {
        if($request->session()->has('should_attach_to_organization')) {
            $shouldAttach = $request->session()->get('should_attach_to_organization');
            $shouldAttachToken = $request->session()->get('should_attach_to_organization_token');
            $linkToRedirect = $request->session()->get('should_attach_to_organization_redirect');
            $userToRegister = $request->session()->get('user_to_register');
        }
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if(isset($shouldAttach)){
            $request->session()->flash('should_attach_to_organization', $shouldAttach);
            $request->session()->flash('should_attach_to_organization_token', $shouldAttachToken);
            $request->session()->flash('should_attach_to_organization_redirect', $linkToRedirect);
            $request->session()->flash('user_to_register', $userToRegister);
        }
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
        if($request->session()->has('should_attach_to_organization')){
            $linkToRedirect = $request->session()->get('should_attach_to_organization_redirect');
            return $request->wantsJson()
                ? new Response('', 204)
                : redirect($linkToRedirect);
        }
    }

    protected function authenticated(Request $request, $user)
    {
        if($request->session()->has('wiki_callback')){
            $user = Auth::user();
            $user->wiki_token = $request->session()->get('wiki_token');
            $user->save();
            $callback = urldecode($request->session()->get('wiki_callback'));
            //return redirect($callback);
        }
        if($request->session()->has('should_attach_to_organization') && $request->session()->get('should_attach_to_organization') !== null){
            $token = $request->session()->get('should_attach_to_organization_token');
            $link = route('organization.invite.show').'?&token='.$token;
            return $request->wantsJson()
                ? new Response('', 204)
                : redirect($link);
        }
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
        if(session()->has('wiki_callback')){
            return $this->authenticated(request(), null);
        }
        return redirect()->route('home');
    }
}
