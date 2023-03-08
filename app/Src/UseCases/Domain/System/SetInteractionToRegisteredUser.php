<?php


namespace App\Src\UseCases\Domain\System;


use App\Src\UseCases\Domain\Context\Model\AnonymousUser;
use App\Src\UseCases\Domain\Context\Model\RegisteredUser;
use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;

class SetInteractionToRegisteredUser
{
    private $authGateway;

    public function __construct(AuthGateway $authGateway)
    {
        $this->authGateway = $authGateway;
    }

    public function execute():?string
    {
        $currentUser = $this->authGateway->current();
        if(!isset($currentUser)){
            return 'nothing_to_do';
        }
        $anonymousUser = new AnonymousUser($this->authGateway->wikiSessionId());
        $anonymousUser->transfer(new RegisteredUser($currentUser->id()));
        return null;
    }
}
