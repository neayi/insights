<?php


namespace App\Src\UseCases\Infra\Sql;


use App\Src\UseCases\Domain\Ports\CharacteristicsRepository;
use App\Src\UseCases\Infra\Sql\Model\CharacteristicsModel;

class CharacteristicsRepositorySql implements CharacteristicsRepository
{
    public function getByType(string $type, bool $isMain): array
    {
        $list = CharacteristicsModel::query()
            ->where('type', $type)
            ->where('main', $isMain)
            ->where('visible', true)
            ->orderBy('priority')
            ->get();
        return $list->toArray();
    }

    public function getAllByType(string $type): array
    {
        $list = CharacteristicsModel::query()
            ->where('type', $type)
            ->orderBy('priority')
            ->get();
        return $list->toArray();
    }


    /**
     * only used for ti test
     * @param array $cs
     */
    public function add(array $cs)
    {
        foreach ($cs as $c){
            $cModel = new CharacteristicsModel();
            $cModel->fill($c);
            $cModel->save();
        }
    }
}
