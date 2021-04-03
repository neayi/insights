<?php


namespace App\Src\UseCases\Domain\System;


use App\Src\UseCases\Domain\Agricultural\Model\AnonymousUser;
use App\Src\UseCases\Domain\Agricultural\Model\RegisteredUser;
use App\Src\UseCases\Domain\Ports\InteractionRepository;
use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;

class SetInteractionToRegisteredUser
{
    private $interactionRepository;
    private $authGateway;

    public function __construct(
        InteractionRepository $interactionRepository,
        AuthGateway $authGateway
    )
    {
        $this->interactionRepository = $interactionRepository;
        $this->authGateway = $authGateway;
    }

    public function execute():?string
    {
        $currentUser = $this->authGateway->current();
        if(!isset($currentUser)){
            return 'nothing_to_do';
        }
        $anonymousUser = new AnonymousUser($this->authGateway->wikiSessionId());
        $this->interactionRepository->transfer($anonymousUser, new RegisteredUser($currentUser->id()));
        return null;
    }
}
