<?php


namespace App\Src\Context\Infrastructure\Repository;


use App\Src\Context\Domain\Characteristic;
use App\Src\Context\Domain\CharacteristicsRepository;
use App\Src\Context\Infrastructure\Model\CharacteristicsModel;

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
            ->where('visible', true)
            ->get();
        return $list->toArray();
    }

    public function getByPageId(int $pageId):?Characteristic
    {
        $c = CharacteristicsModel::query()
            ->where('page_id', $pageId)
            ->first();
        if(!isset($c)){
            return null;
        }
        return $c->toDomain();
    }

    public function save(Characteristic $c)
    {
        $memento = $c->toArray();
        $characteristicModel = new CharacteristicsModel();
        $characteristicModel->page_label = $memento['title'];
        $characteristicModel->pretty_page_label = $memento['title'];
        $characteristicModel->code = $memento['title'];
        $characteristicModel->main = false;
        $characteristicModel->priority = 100000;

        $characteristicModel->fill($c->toArray());
        $characteristicModel->save();
    }

    public function getBy(array $conditions): ?Characteristic
    {
        $characteristicModel = CharacteristicsModel::query()
            ->when(isset($conditions['type']), function ($query) use($conditions){
                $query->where('type', $conditions['type']);
            })
            ->when(isset($conditions['title']), function ($query) use($conditions){
                $query->where('code', $conditions['title']);
            })
            ->first();
        if(!isset($characteristicModel)){
            return null;
        }
        return $characteristicModel->toDomain();
    }
}