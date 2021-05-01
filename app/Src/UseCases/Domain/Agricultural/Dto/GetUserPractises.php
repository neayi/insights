<?php


namespace App\Src\UseCases\Domain\Agricultural\Dto;


use App\Src\UseCases\Domain\Ports\InteractionRepository;

class GetUserPractises
{
    private $interactionsRepository;

    public function __construct(InteractionRepository $interactionRepository)
    {
        $this->interactionsRepository = $interactionRepository;
    }

    public function get(string $userId)
    {
        $practisesToReturn = [];
        $practises = $this->interactionsRepository->getDoneByUser($userId);
        foreach ($practises as $practise){
            //$year =
        }
    }
}
