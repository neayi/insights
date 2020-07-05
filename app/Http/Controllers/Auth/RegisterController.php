<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Src\UseCases\Domain\Auth\Register;
use App\Src\UseCases\Domain\Auth\RegisterUserFromSocialNetwork;
use App\Src\UseCases\Domain\Invitation\AttachUserToAnOrganization;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        session()->reflash();
        return view('auth.register', [
            'email' => $email,
            'firstname' => $firstname,
            'lastname' => $lastname
        ]);
    }

    protected function validator(array $data)
    {
        if(session()->has('should_attach_to_organization')) {
            session()->reflash();
        }
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $email = isset($data['email']) ? $data['email'] : '';
        $firstname = $data['firstname'] !== null ? $data['firstname'] : '';
        $lastname = $data['lastname'] !== null ? $data['lastname'] : '';
        $password = $data['password'] !== null ? $data['password'] : '';
        $passwordConfirmation = $data['password_confirmation'] !== null ? $data['password_confirmation'] : '';
        $userId = app(Register::class)->register($email, $firstname, $lastname, $password, $passwordConfirmation);
        return User::where('uuid', $userId)->first();
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        if($request->session()->has('should_attach_to_organization')){
            app(AttachUserToAnOrganization::class)->attach($user->uuid, $request->session()->get('should_attach_to_organization'));
        }
    }

    public function redirectToProvider(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(string $provider, Request $request, RegisterUserFromSocialNetwork $register)
    {
        try {
            $userId = $register->register($provider);
            $user = User::where('uuid', $userId)->first();
            $this->guard()->login($user);
            return $request->wantsJson()
                ? new Response('', 201)
                : redirect($this->redirectPath());
        }catch (ValidationException $e) {
            return redirect()->route('register')
                ->withInput($e->validator->attributes())
                ->withErrors($e->validator);
        }

    }
}
