<?php


namespace App\Src\UseCases\Domain\Context\Queries;


use App\Src\Context\Domain\InteractionRepository;

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
