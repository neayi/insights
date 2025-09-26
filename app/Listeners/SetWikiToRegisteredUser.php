<?php

declare(strict_types=1);

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use \App\LocalesConfig;

class SetWikiToRegisteredUser
{
    public function handle(Registered $event)
    {
        $user = $event->user;
        if (empty($user->default_locale)) {
            $locale = LocalesConfig::getPreferredLocale();
            $user->default_locale = $locale->code;
            $user->save();
        }
    }
}
