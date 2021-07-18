<?php


namespace App\Src\UseCases\Domain\Context\Queries;


use App\Src\UseCases\Domain\Context\Model\AnonymousUser;
use App\Src\UseCases\Domain\Context\Model\CanInteract;
use App\Src\UseCases\Domain\Context\Model\Interaction;
use App\Src\UseCases\Domain\Context\Model\RegisteredUser;
use App\Src\UseCases\Domain\Ports\InteractionRepository;
use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;

class GetInteractionsByUser
{
    private $interactionRepository;

    public function __construct(InteractionRepository $interactionRepository)
    {
        $this->interactionRepository = $interactionRepository;
    }

    public function get(string $userId)
    {
        return $this->interactionRepository->getInteractionsByUser($userId);
    }
}
