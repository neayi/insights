<?php

declare(strict_types=1);

namespace App\Src\UseCases\Domain\Context\Queries;


use App\Src\UseCases\Domain\Ports\InteractionRepository;

class CountInteractionsOnPageQuery
{
    public function __construct(
        private InteractionRepository $interactionRepository
    ){}

    public function execute(int $pageId, string $wikiCode):array
    {
        return $this->interactionRepository->getCountInteractionsOnPage($pageId, $wikiCode);
    }
}
