<?php


namespace App\Listeners;



use App\Src\Insights\Insights\Application\UseCase\Interactions\TransferInteractionFromAnonymousUserToRegisteredUser;

class SetInteractionToRegisteredUserListener
{
    public function handle($event)
    {
        app(TransferInteractionFromAnonymousUserToRegisteredUser::class)->execute();
    }
}
