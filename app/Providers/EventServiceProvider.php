<?php

namespace App\Providers;

use App\Events\UserDeleted;
use App\Listeners\AnonymizeUserResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UserDeleted::class => [
            AnonymizeUserResponse::class
        ]
    ];

    public function boot()
    {
        parent::boot();
    }
}
