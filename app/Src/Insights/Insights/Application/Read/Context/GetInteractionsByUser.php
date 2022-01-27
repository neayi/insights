<?php


namespace App\Src\Insights\Insights\Application\Read\Context;

use App\Src\UseCases\Domain\Ports\InteractionRepository;

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
