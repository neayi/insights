<?php


namespace App\Src\UseCases\Infra\Sql;


use App\Src\UseCases\Infra\Sql\Model\CharacteristicsModel;
use App\Src\UseCases\Infra\Sql\Model\InteractionModel;

class ReportingCharacteristicSql
{
    public function getStatsByDepartment(int $pageId, string $type = 'follow')
    {
        $interactions = InteractionModel::query()
            ->selectRaw('
                count(*) as count,
                IF(SUBSTR(contexts.postal_code, 1, 2) >= 97, SUBSTR(contexts.postal_code, 1, 3), SUBSTR(contexts.postal_code, 1, 2)) as department
            ')
            ->join('users', 'users.id', 'interactions.user_id')
            ->join('contexts', 'users.context_id', 'contexts.id')
            ->when($type === 'follow', function ($query) {
                $query->where('follow', true);
                $query->orWhere('done', true);
            })
            ->when($type === 'do', function ($query) {
                $query->where('done', true);
            })
            ->where('interactions.page_id', $pageId)
            ->whereNotNull('interactions.user_id')
            ->groupBy('department')
            ->get();

        $interactionsToReturn = [];
        foreach($interactions as $interaction){
            $characteristicsModel = CharacteristicsModel::query()->where('code', $interaction->department)->first();
            $interaction->departmentData = $characteristicsModel;
            $interactionsToReturn[] = $interaction->toArray();
        }
        return $interactionsToReturn;
    }

    public function getCharacteristicsByUserPage(int $pageId, string $type = 'follow', string $characteristicType = null)
    {
        $characteristicsCount = InteractionModel::query()
            ->selectRaw('
                count(*) as count,
                user_characteristics.characteristic_id
            ')
            ->join('users', 'users.id', 'interactions.user_id')
            ->join('contexts', 'users.context_id', 'contexts.id')
            ->join('user_characteristics', 'user_characteristics.user_id', 'users.id')
            ->join('characteristics', 'characteristics.id', 'user_characteristics.characteristic_id')
            ->when($type === 'follow', function ($query) {
                $query->where('follow', true);
                $query->orWhere('done', true);
            })
            ->when($type === 'do', function ($query) {
                $query->where('done', true);
            })
            ->where('interactions.page_id', $pageId)
            ->whereNotNull('interactions.user_id')
            ->where('characteristics.type', $characteristicType)
            ->groupBy('user_characteristics.characteristic_id')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        $characteristicsToReturn = [];
        foreach($characteristicsCount as $characteristicCount){
            $characteristic = CharacteristicsModel::query()->find($characteristicCount->characteristic_id);
            $characteristic->count = $characteristicCount->count;
            $characteristicsToReturn[] = $characteristic->toArray();
        }
        return $characteristicsToReturn;
    }
}
