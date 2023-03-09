<?php


namespace App\Src\Context\Application\Queries;


use App\Src\Context\Domain\Characteristic;
use App\Src\Context\Infrastructure\Repository\ReportingCharacteristicSql;

class GetStatsByDepartment
{
    private $reportingCharacteristicsRepository;

    public function __construct(ReportingCharacteristicSql $reportingCharacteristicSql)
    {
        $this->reportingCharacteristicsRepository = $reportingCharacteristicSql;
    }

    public function execute(int $pageId, string $type = 'follow'):array
    {
        $stats = $this->reportingCharacteristicsRepository->getStatsByDepartment($pageId, $type);
        $farming = $this->reportingCharacteristicsRepository->getCharacteristicsByUserPage($pageId, $type, Characteristic::FARMING_TYPE);
        $cropping = $this->reportingCharacteristicsRepository->getCharacteristicsByUserPage($pageId, $type, Characteristic::CROPPING_SYSTEM);

        return [
            'department' => $stats,
            'characteristics' => [
                Characteristic::FARMING_TYPE => $farming,
                Characteristic::CROPPING_SYSTEM => $cropping
            ]
        ];
    }
}
