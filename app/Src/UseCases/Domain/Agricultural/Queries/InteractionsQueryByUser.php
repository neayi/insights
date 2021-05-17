<?php


namespace App\Src\UseCases\Domain\Agricultural\Queries;


use App\Src\UseCases\Domain\Agricultural\Model\AnonymousUser;
use App\Src\UseCases\Domain\Agricultural\Model\CanInteract;
use App\Src\UseCases\Domain\Agricultural\Model\Interaction;
use App\Src\UseCases\Domain\Agricultural\Model\RegisteredUser;
use App\Src\UseCases\Domain\Ports\InteractionRepository;
use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;

class InteractionsQueryByUser
{
    private $interactionRepository;

    public function __construct(
        InteractionRepository $interactionRepository
    )
    {
        $this->interactionRepository = $interactionRepository;
    }

    public function get(string $userId)
    {
        return $this->interactionRepository->getInteractionsByUser($userId);
    }
}
