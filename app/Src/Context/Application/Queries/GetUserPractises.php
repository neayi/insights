<?php


namespace App\Src\Context\Application\Queries;


use App\Src\Context\Domain\InteractionRepository;

class GetUserPractises
{
    private $interactionsRepository;

    public function __construct(InteractionRepository $interactionRepository)
    {
        $this->interactionsRepository = $interactionRepository;
    }

    public function get(string $userId):array
    {
        $practisesToReturn = [];
        $practises = $this->interactionsRepository->getDoneByUser($userId);
        foreach ($practises as $practise){
            $year = $practise->doneAt() !== null ? $practise->doneAt()->format('Y') : 'Non datée';
            $practisesToReturn[$year][] = $practise->toArray();
        }

        krsort($practisesToReturn, SORT_NUMERIC);

        return $practisesToReturn;
    }
}
