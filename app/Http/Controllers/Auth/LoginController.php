<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Src\UseCases\Domain\Invitation\AttachUserToAnOrganization;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        session()->reflash();
        return view('auth.login');
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
        if($request->session()->has('should_attach_to_organization') && $request->session()->get('should_attach_to_organization') !== null){
            $token = $request->session()->get('should_attach_to_organization_token');
            $link = route('organization.invite.show').'?&token='.$token;
            return $request->wantsJson()
                ? new Response('', 204)
                : redirect($link);
        }
    }
}
