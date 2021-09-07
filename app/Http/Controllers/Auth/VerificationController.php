<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = 'profile';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * The user has been verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function verified(Request $request)
    {
        $callback = '';
        if($request->session()->has('wiki_callback')){
            $user = Auth::user();
            $user->wiki_token = $request->session()->get('wiki_token');
            $user->save();
            $callback = urldecode($request->session()->get('wiki_callback'));
            session()->remove('wiki_callback');
            session()->remove('wiki_token');
        }

        if($request->session()->has('sso')){
            $sso = $request->session()->get('sso');
            $sig = $request->session()->get('sig');
            $callback = url('discourse/sso?sso='.$sso.'&sig='.$sig);
        }

        return view('public.auth.verified', ['callback' => $callback]);
    }

    /**
     * Show the email verification notice.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show(Request $request)
    {
        if(session()->has('wiki_callback')){
            session()->put(
                [
                    'wiki_callback' => session()->get('wiki_callback'),
                    'wiki_token' => session()->get('wiki_token')
                ]
            );
        }

        if(session()->has('sso')){
            session()->put('sso', $request->input('sso'));
            session()->put('sig', $request->input('sig'));
        }

        return $request->user()->hasVerifiedEmail()
            ? redirect($this->redirectPath())
            : view('public.auth.verify');
    }
}
