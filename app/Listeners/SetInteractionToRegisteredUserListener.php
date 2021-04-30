<?php


namespace App\Listeners;


use App\Src\UseCases\Domain\System\SetInteractionToRegisteredUser;

class SetInteractionToRegisteredUserListener
{
    public function handle($event)
    {
        app(SetInteractionToRegisteredUser::class)->execute();
    }
}
