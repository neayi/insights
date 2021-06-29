<?php


namespace App\Src\UseCases\Domain\Context\Queries;


use App\Src\UseCases\Domain\Ports\InteractionRepository;
use Illuminate\Contracts\Pagination\Paginator;

class GetFollowersOfPage
{
    private $interactionRepository;

    public function __construct(InteractionRepository $interactionRepository)
    {
        $this->interactionRepository = $interactionRepository;
    }

    public function execute(
        int $pageId,
        string $type = 'follow',
        ?string $cp = null,
        string $characteristicIdFarmingType = null,
        string $characteristicIdCroppingSystem = null
    ):Paginator
    {
        return $this->interactionRepository->getFollowersPage(
            $pageId,
            $type,
            $cp,
            $characteristicIdFarmingType,
            $characteristicIdCroppingSystem
        );
    }
}
