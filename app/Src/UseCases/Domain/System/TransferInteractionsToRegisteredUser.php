<?php


namespace App\Src\UseCases\Domain\System;


use App\Src\Context\Domain\AnonymousUser;
use App\Src\Context\Domain\RegisteredUser;
use App\Src\Shared\Gateway\AuthGateway;

class TransferInteractionsToRegisteredUser
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
