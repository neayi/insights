<?php


namespace App\Mail\Auth;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $user;
    private $callback;

    public function __construct($user, string $callback = null)
    {
        $this->user = $user;
        $this->callback = $callback;
    }

    public function build()
    {
        $url = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(config('auth.verification.expire')),
            [
                'id' => $this->user->getKey(),
                'hash' => sha1($this->user->getEmailForVerification()),
                'callback' => $this->callback
            ]
        );

        return $this->view('mails.auth.verify-email', [
            'url' => $url,
        ])->subject('Tripleperformance : Vérifiez votre email');
    }
}
