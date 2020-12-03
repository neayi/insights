<?php


namespace App\Mail\Auth;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetLink extends Mailable
{
    use Queueable, SerializesModels;

    private $user;
    private $token;

    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function build()
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $this->user->getEmailForPasswordReset(),
        ], false));

        return $this->view('mails.auth.password-reset-link', [
            'url' => $url,
            'validity' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')
        ])->subject('Triple performance : Réinitialisez votre mot de passe');
    }
}
