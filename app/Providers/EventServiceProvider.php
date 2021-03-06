<?php

namespace App\Providers;

use App\Events\InteractionOnPage;
use App\Events\UserDeleted;
use App\Events\UserLeaveOrganization;
use App\Listeners\AnonymizeUserResponse;
use App\Listeners\SetInteractionToRegisteredUserListener;
use App\Listeners\SetPageDryStateListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            SetInteractionToRegisteredUserListener::class
        ],
        InteractionOnPage::class => [
            SetPageDryStateListener::class,
            SetInteractionToRegisteredUserListener::class
        ],
        UserDeleted::class => [
            AnonymizeUserResponse::class
        ],
        UserLeaveOrganization::class => [
            AnonymizeUserResponse::class
        ]
    ];

    public function boot()
    {
        parent::boot();
    }
}
