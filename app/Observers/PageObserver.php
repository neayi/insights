<?php

namespace App\Observers;


use App\Src\UseCases\Infra\Sql\Model\CharacteristicsModel;
use App\Src\UseCases\Infra\Sql\Model\PageModel;
use App\Src\UseCases\Infra\Sql\Model\UserCharacteristicsModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PageObserver
{
    public function created(PageModel $pageModel)
    {
        //
    }

    public function updated(PageModel $pageModel)
    {
        //
    }

    public function deleted(PageModel $pageModel)
    {
        Log::info('Deleting all data from the page internal id: ' . $pageModel->id);
        DB::transaction(function () use($pageModel){
            try {
                $characteristic = CharacteristicsModel::query()->where('page_id', $pageModel->page_id)->first();
                if (isset($characteristic)) {
                    $characteristic->delete();
                    if (is_file($characteristic->picturePath())) {
                        unlink($characteristic->picturePath());
                    }

                    UserCharacteristicsModel::query()->where('characteristic_id', $characteristic->id)->delete();
                }
            } catch (\Throwable $e) {
                Log::emergency('Error deleting all data from the page internal id: ' . $pageModel->id);
                throw $e;
            }
        });
    }

    public function restored(PageModel $pageModel)
    {
        //
    }

    public function forceDeleted(PageModel $pageModel)
    {
        //
    }
}
