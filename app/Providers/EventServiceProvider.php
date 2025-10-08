<?php

namespace App\Providers;

use App\Events\InteractionOnPage;
use App\Listeners\AddEmailToNewsletter;
use App\Listeners\SendCustomEmailVerificationNotification;
use App\Listeners\SetInteractionToRegisteredUserListener;
use App\Listeners\SetUserToSyncOnDiscourse;
use App\Listeners\SetWikiToRegisteredUser;
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
            SetInteractionToRegisteredUserListener::class,
            SetWikiToRegisteredUser::class
        ],
        InteractionOnPage::class => [
            SetInteractionToRegisteredUserListener::class
        ],
        Verified::class => [
            SetUserToSyncOnDiscourse::class,
            AddEmailToNewsletter::class
        ]
    ];

    public function boot()
    {
        parent::boot();

        PageModel::observe(PageObserver::class);
    }
}
