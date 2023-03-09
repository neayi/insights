<?php


namespace App\Src\Context\Application\Queries;


use App\Src\Context\Domain\InteractionRepository;
use Illuminate\Contracts\Pagination\Paginator;

class GetPageFollowers
{
    private $interactionRepository;

    public function __construct(InteractionRepository $interactionRepository)
    {
        $this->interactionRepository = $interactionRepository;
    }

    public function execute(
        int $pageId,
        string $type = 'follow',
        ?string $dept = null,
        string $characteristicIdFarmingType = null,
        string $characteristicIdCroppingSystem = null
    ):Paginator
    {
        return $this->interactionRepository->getFollowersPage(
            $pageId,
            $type,
            $dept,
            $characteristicIdFarmingType,
            $characteristicIdCroppingSystem
        );
    }
}
