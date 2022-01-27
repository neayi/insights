<?php


namespace App\Src\Insights\Insights\Application\Read\Reporting;


use App\Src\UseCases\Domain\Ports\InteractionRepository;

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
