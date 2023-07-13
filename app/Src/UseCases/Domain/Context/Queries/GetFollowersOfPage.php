<?php

declare(strict_types=1);

namespace App\Src\UseCases\Domain\Context\Queries;


use App\Src\UseCases\Domain\Ports\InteractionRepository;
use Illuminate\Contracts\Pagination\Paginator;

class GetFollowersOfPage
{
    public function __construct(
        private InteractionRepository $interactionRepository
    ){}

    public function execute(
        int $pageId,
        string $type = 'follow',
        ?string $dept = null,
        string $characteristicIdFarmingType = null,
        string $characteristicIdCroppingSystem = null,
        string $wikiCode = null
    ):Paginator
    {
        return $this->interactionRepository->getFollowersPage(
            $pageId,
            $type,
            $dept,
            $characteristicIdFarmingType,
            $characteristicIdCroppingSystem,
            $wikiCode
        );
    }
}
