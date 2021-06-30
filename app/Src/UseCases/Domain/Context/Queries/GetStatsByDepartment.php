<?php


namespace App\Src\UseCases\Domain\Context\Queries;


use App\Src\UseCases\Domain\Context\Model\Characteristic;
use App\Src\UseCases\Infra\Sql\ReportingCharacteristicSql;

class GetStatsByDepartment
{
    private $reportingCharacteristicsRepository;

    public function __construct(ReportingCharacteristicSql $reportingCharacteristicSql)
    {
        $this->reportingCharacteristicsRepository = $reportingCharacteristicSql;
    }

    public function execute(
        int $pageId,
        string $type = 'follow'
    )
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
