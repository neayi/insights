<?php

namespace App\Providers;

use App\Events\InteractionOnPage;
use App\Events\UserDeleted;
use App\Events\UserLeaveOrganization;
use App\Listeners\AddEmailToMailChimp;
use App\Listeners\AnonymizeUserResponse;
use App\Listeners\SendCustomEmailVerificationNotification;
use App\Listeners\SetInteractionToRegisteredUserListener;
use App\Listeners\SetPageDryStateListener;
use App\Listeners\SetUserToSyncOnDiscourse;
use App\Observers\PageObserver;
use App\Src\UseCases\Infra\Sql\Model\PageModel;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendCustomEmailVerificationNotification::class,
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
        ],
        Verified::class => [
            SetUserToSyncOnDiscourse::class,
            AddEmailToMailChimp::class
        ]
    ];

    public function boot()
    {
        parent::boot();

        PageModel::observe(PageObserver::class);
    }
}
