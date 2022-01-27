<?php


namespace App\Src\Insights\Insights\Application\UseCase\Interactions;

use App\Src\Insights\Insights\Domain\Interactions\AnonymousUser;
use App\Src\Insights\Insights\Domain\Interactions\RegisteredUser;
use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;

class TransferInteractionFromAnonymousUserToRegisteredUser
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
