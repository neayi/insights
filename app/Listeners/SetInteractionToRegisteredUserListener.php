<?php


namespace App\Listeners;


use App\Src\Context\Application\TransferInteractionsToRegisteredUser;

class SetInteractionToRegisteredUserListener
{
    public function handle($event)
    {
        app(TransferInteractionsToRegisteredUser::class)->execute();
    }
}
