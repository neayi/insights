<?php


namespace App\Src\UseCases\Infra\Sql;


use App\Src\UseCases\Domain\Context\Model\Characteristic;
use App\Src\UseCases\Domain\Ports\CharacteristicsRepository;
use App\Src\UseCases\Infra\Sql\Model\CharacteristicsModel;
use Ramsey\Uuid\Uuid;

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

    public function save(Characteristic $c)
    {
        $memento = $c->memento();
        $characteristicModel = new CharacteristicsModel();
        $characteristicModel->page_label = $memento->title();
        $characteristicModel->pretty_page_label = $memento->title();
        $characteristicModel->main = false;
        $characteristicModel->priority = 0;
        $characteristicModel->uuid = Uuid::uuid4();
        $characteristicModel->code = $memento->title();
        $characteristicModel->type = $memento->type();
        $characteristicModel->visible = $memento->visible();
        $characteristicModel->save();
    }

    public function getBy(array $conditions): ?Characteristic
    {
        $characteristicModel = CharacteristicsModel::query()
            ->when(isset($conditions['type']), function ($query) use($conditions){
                $query->where('type', $conditions['type']);
            })->when(isset($conditions['title']), function ($query) use($conditions){
                $query->where('code', $conditions['title']);
            })
            ->first();
        if(!isset($characteristicModel)){
            return null;
        }
        return $characteristicModel->toDomain();
    }


}
