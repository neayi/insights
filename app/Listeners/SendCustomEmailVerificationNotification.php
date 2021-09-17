<?php


namespace App\Listeners;


use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class SendCustomEmailVerificationNotification
{
    public function handle(Registered $event)
    {
        if (($event->user->providers === null || empty($event->user->providers)) && $event->user instanceof MustVerifyEmail && ! $event->user->hasVerifiedEmail()) {
            $event->user->sendEmailVerificationNotification();
        }
    }
}
