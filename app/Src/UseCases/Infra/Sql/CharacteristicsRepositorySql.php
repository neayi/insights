<?php


namespace App\Src\UseCases\Infra\Sql;


use App\Src\UseCases\Domain\Context\Model\Characteristic;
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

    public function getAllByType(string $type, string $wikiCode): array
    {
        $list = CharacteristicsModel::query()
            ->where('type', $type)
            ->orderBy('priority')
            ->where('visible', true)
            ->where('wiki', $wikiCode)
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
        $memento = $c->memento();
        $characteristicModel = new CharacteristicsModel();
        $characteristicModel->page_label = $memento->title();
        $characteristicModel->pretty_page_label = $memento->title();
        $characteristicModel->main = false;
        $characteristicModel->priority = 100000;
        $characteristicModel->uuid = $memento->id();
        $characteristicModel->code = $memento->title();
        $characteristicModel->type = $memento->type();
        $characteristicModel->visible = $memento->visible();
        $characteristicModel->icon = $memento->icon();
        $characteristicModel->page_id = $memento->pageId();
        $characteristicModel->wiki = $memento->wiki();
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
