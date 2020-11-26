<?php


namespace App\Src\UseCases\Domain\Agricultural\Dto;


use App\Src\UseCases\Domain\Ports\ListRepository;

class GetFarmingType
{
    private $listRepository;
    const type = 'type_farming';

    public function __construct(ListRepository $listRepository)
    {
        $this->listRepository = $listRepository;
    }

    public function get()
    {
        $mains = $this->listRepository->getByType(self::type, true);
        $others = $this->listRepository->getByType(self::type, false);
        return [
            'main' => $mains,
            'others' => $others,
        ];
    }
}
