<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Src\UseCases\Domain\Auth\Register;
use App\Src\UseCases\Domain\Auth\RegisterUserAfterErrorWithSocialNetwork;
use App\Src\UseCases\Domain\Auth\RegisterUserFromSocialNetwork;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm(Request $request)
    {
        $email = $firstname = $lastname = '';

        if($request->session()->has('user_to_register')){
            $user = $request->session()->get('user_to_register');
            $email = $user['email'];
            $firstname = $user['firstname'];
            $lastname = $user['lastname'];
        }

        if($request->has('wiki_callback')){
            session()->flash('wiki_callback', $request->input('wiki_callback'));
            session()->flash('wiki_token', $request->input('wiki_token'));
        }

        return view('public.auth.register', [
            'email' => $email,
            'firstname' => $firstname,
            'lastname' => $lastname
        ]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required',
                        'email',
                        'max:255',
                        'unique:users',
                        new \nickurt\StopForumSpam\Rules\IsSpamEmail(20)],
            'password' => 'required|min:8|max:255|confirmed'
        ], [
            'password.confirmed' => 'Veuillez confirmer votre mot de passe ci-dessous'
        ]);
    }

    protected function create(array $data)
    {
        $email = $data['email'] ?? '';
        $firstname = $data['firstname'] ?? '';
        $lastname = $data['lastname'] ?? '';
        $password = $data['password'] !== null ? $data['password'] : '';
        $passwordConfirmation = $data['password_confirmation'] !== null ? $data['password_confirmation'] : '';
        $userId = app(Register::class)->register($email, $firstname, $lastname, $password, $passwordConfirmation);

        $user = User::where('uuid', $userId)->first();
        $locale = \App\LocalesConfig::getPreferredLocale();
        $user->wiki = $locale->code;
        $user->save();
        return $user;
    }

    protected function registered(Request $request, $user)
    {
        $this->guard()->login($user, true);

        $user = Auth::user();

        if($user->context_id === null) {
            if($request->session()->has('wiki_token')) {
                $user->wiki_token = $request->session()->get('wiki_token');
                $user->save();
            }
            return redirect()->route('wizard.profile');
        }


        /**
         * should be deleted dead code ?
         */
        if($request->session()->has('wiki_callback')){
            $user->wiki_token = $request->session()->get('wiki_token');
            $user->save();
            $callback = urldecode($request->session()->get('wiki_callback'));
            return redirect($callback);
        }

        return redirect()->route('wizard.profile');
    }

    public function redirectToProvider(string $provider)
    {
        if(request()->session()->has('wiki_token')) {
            session()->reflash();
        }
        config(['services.'.$provider.'.redirect' => env(strtoupper($provider).'_CALLBACK')]);
        if($provider === 'twitter'){
            return Socialite::driver($provider)->redirect();
        }
        return Socialite::driver($provider)->redirectUrl(config('services.'.$provider.'.redirect'))->redirect();
    }

    public function handleProviderCallback(string $provider, Request $request, RegisterUserFromSocialNetwork $register)
    {
        try {
            $userId = $register->register($provider);
            $user = User::where('uuid', $userId)->first();
            $this->guard()->login($user);

            if($user->context_id !== null){
                $user->wiki_token = $request->session()->get('wiki_token');
                $user->save();
                $callback = urldecode($request->session()->get('wiki_callback'));
                return redirect($callback);
            }

            if(session()->has('sso')){
                $sso = session()->get('sso');
                $sig = session()->get('sig');
                $wikiCode = session()->get('wikiCode');
                $callback = base64_encode(url($wikiCode.'/neayi/discourse/sso?sso='.$sso.'&sig='.$sig));
                return redirect($callback);
            }

            return redirect()->route('wizard.profile');
        }catch (ValidationException $e) {
            $attributes = $e->validator->attributes();
            $attributes['provider'] = $provider;
            return redirect()->route('register-social-network')
                ->withInput($attributes)
                ->withErrors($e->validator);
        }
    }

    public function showErrorRegisterFormSocialNetwork()
    {
        return view('auth.register-social-network');
    }

    public function registerAfterError(Request $request, RegisterUserAfterErrorWithSocialNetwork $registerUserAfterErrorWithSocialNetwork)
    {
        list($email, $firstname, $lastname, $provider, $providerId, $pictureUrl) = $this->initData($request);
        $result = $registerUserAfterErrorWithSocialNetwork->register($firstname, $lastname, $email, $provider, $providerId, $pictureUrl);

        $userId = $result['user_id'];
        $user = User::where('uuid', $userId)->first();
        $locale = \App\LocalesConfig::getPreferredLocale();
        $user->wiki = $locale->code;
        $user->save();
        return redirect()->route('home');
    }

    private function initData(Request $request): array
    {
        $data = $request->all();
        $email = init($data['email'], '');
        $firstname = init($data['firstname'], '');
        $lastname = init($data['lastname'], '');
        $provider = init($data['provider'], null);
        $providerId = init($data['provider_id'], null);
        $pictureUrl = init($data['picture_url'], '');
        return [$email, $firstname, $lastname, $provider, $providerId, $pictureUrl];
    }
}
