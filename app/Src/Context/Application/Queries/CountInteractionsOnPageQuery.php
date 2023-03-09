<?php


namespace App\Src\Context\Application\Queries;


use App\Src\Context\Domain\InteractionRepository;

class CountInteractionsOnPageQuery
{
    private $interactionRepository;

    public function __construct(InteractionRepository $interactionRepository)
    {
        $this->interactionRepository = $interactionRepository;
    }

    public function execute(int $pageId):array
    {
        return $this->interactionRepository->getCountInteractionsOnPage($pageId);
    }
}
