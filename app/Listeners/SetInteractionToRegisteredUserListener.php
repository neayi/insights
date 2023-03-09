<?php


namespace App\Listeners;


use App\Src\UseCases\Domain\System\TransferInteractionsToRegisteredUser;

class SetInteractionToRegisteredUserListener
{
    public function handle($event)
    {
        app(TransferInteractionsToRegisteredUser::class)->execute();
    }
}
