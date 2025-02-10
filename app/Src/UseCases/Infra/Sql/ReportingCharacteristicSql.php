<?php


namespace App\Src\UseCases\Infra\Sql;


use App\Src\UseCases\Infra\Sql\Model\CharacteristicsModel;
use App\Src\UseCases\Infra\Sql\Model\InteractionModel;

class ReportingCharacteristicSql
{
    public function getStatsByDepartment(int $pageId, string $type = 'follow')
    {
        $interactions = InteractionModel::query()
            ->selectRaw('count(*) AS count, contexts.department_number as department_number')
            ->join('users', 'users.id', 'interactions.user_id')
            ->join('contexts', 'users.context_id', 'contexts.id')
            ->where(function ($query) use ($type){
                $query->when($type === 'follow', function ($query) {
                    $query->where('follow', true);
                    $query->orWhere('done', true);
                })
                ->when($type === 'do', function ($query) {
                    $query->where('done', true);
                });
            })
            ->where('interactions.page_id', $pageId)
            ->where('users.email', 'NOT LIKE', '%@neayi.com')
            ->whereNotNull('interactions.user_id')
            ->groupBy('department_number')
            ->get();

        $interactionsToReturn = [];
        foreach($interactions as $interaction){
            $characteristicsModel = CharacteristicsModel::query()->where('code', $interaction->department_number)->first();
            if(!isset($characteristicsModel)){
                continue;
            }
            if (isset($characteristicsModel->opt['number'])) {
                $characteristicsModel->icon = 'Departement-' . $characteristicsModel->opt['number'];
            }
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
            ->where(function ($query) use ($type){
                $query->when($type === 'follow', function ($query) {
                    $query->where('follow', true);
                    $query->orWhere('done', true);
                })
                ->when($type === 'do', function ($query) {
                    $query->where('done', true);
                });
            })
            ->where('interactions.page_id', $pageId)
            ->whereNotNull('interactions.user_id')
            ->where('characteristics.type', $characteristicType)
            ->where('users.email', 'NOT LIKE', '%@neayi.com')
            ->groupBy('user_characteristics.characteristic_id')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        $characteristicsToReturn = [];
        foreach($characteristicsCount as $characteristicCount){
            $characteristic = CharacteristicsModel::query()->find($characteristicCount->characteristic_id);
            $characteristic->count = $characteristicCount->count;
            $c = $characteristic->toArray();
            $c['pretty_page_label'] = str_replace('Catégorie:', '', $c['pretty_page_label']);
            $c['icon'] = route('api.icon.serve', ['id' => $c['uuid']]);
            $characteristicsToReturn[] = $c;
        }
        return $characteristicsToReturn;
    }
}
