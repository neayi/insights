<?php


namespace App\Src\UseCases\Infra\Sql;


use App\Src\UseCases\Domain\Agricultural\Dto\PractiseVo;
use App\Src\UseCases\Domain\Agricultural\Model\CanInteract;
use App\Src\UseCases\Domain\Agricultural\Model\Interaction;
use App\Src\UseCases\Domain\Agricultural\Model\Page;
use App\Src\UseCases\Domain\Ports\InteractionRepository;
use App\Src\UseCases\Infra\Sql\Model\InteractionModel;
use App\Src\UseCases\Infra\Sql\Model\PageModel;
use App\User;

class InteractionPageRepositorySql implements InteractionRepository
{
    public function save(CanInteract $canInteract, Interaction $interaction)
    {
        $user = User::query()->where('uuid', $canInteract->identifier())->first();

        $interactionModel = InteractionModel::query()
            ->where($canInteract->key(), isset($user->id) ? $user->id : $canInteract->identifier())
            ->where('page_id', $interaction->pageId())
            ->first();

        if($interactionModel === null) {
            $interactionModel = new InteractionModel();
            $interactionModel->{$canInteract->key()} = isset($user->id) ? $user->id : $canInteract->identifier();
        }

        $interactionModel->fill($interaction->toArray());
        $interactionModel->start_done_at = isset($interaction->toArray()['value']['start_at']) ? $interaction->toArray()['value']['start_at'] : null;
        $interactionModel->save();
    }

    public function getByInteractUser(CanInteract $canInteract, int $pageId): ?Interaction
    {
        if($canInteract->key() == 'user_id') {
            $user = User::query()->where('uuid', $canInteract->identifier())->first();
        }

        $interactionModel = InteractionModel::query()
            ->where($canInteract->key(), isset($user->id) ? $user->id : $canInteract->identifier())
            ->where('page_id', $pageId)
            ->first();

        if(!isset($interactionModel)){
            return null;
        }

        $value = $interactionModel->value ?? [];
        return new Interaction($pageId, $interactionModel->follow, $interactionModel->applause, $interactionModel->done, $value);
    }

    public function transfer(CanInteract $anonymous, CanInteract $registered)
    {
        $user = User::query()->where('uuid', $registered->identifier())->first();

        InteractionModel::query()->where($anonymous->key(), $anonymous->identifier())
            ->update([
                $anonymous->key() => null,
                $registered->key() => $user->id
            ]);
    }

    public function getCountInteractionsOnPage(int $pageId):array
    {
        $follow = InteractionModel::query()->where('page_id', $pageId)->where('follow', true)->count();
        $done = InteractionModel::query()->where('page_id', $pageId)->where('done', true)->count();
        $applause = InteractionModel::query()->where('page_id', $pageId)->where('applause', true)->count();

        return ['follow' => $follow, 'done' => $done, 'applause' => $applause];
    }

    public function getInteractionsByUser(string $userId): array
    {
        $user = User::query()->where('uuid', $userId)->first();
        $interactionsModel = InteractionModel::query()
            ->where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach($interactionsModel as $interaction) {
            $page = PageModel::query()->where('page_id', $interaction->page_id)->first();
            $applause = InteractionModel::query()->where('page_id', $interaction->page_id)->where('applause', true)->count();
            if ($interaction->follow) {
                $interactions['follow'][] = array_merge($page->toArray(), ['applause' => $applause]);
            }
            if ($interaction->applause) {
                $interactions['applause'][] = array_merge($page->toArray(), ['applause' => $applause]);
            }
        }
        return $interactions ?? [];
    }


    public function getDoneByUser(string $userId): array
    {
        $user = User::query()->where('uuid', $userId)->first();
        $records = InteractionModel::query()
            ->with('page')
            ->where('user_id', $user->id)
            ->where('done', true)
            ->get();
        foreach ($records as $record){
            $practises[] = new PractiseVo(
                $record->page_id,
                $record->page->title ?? '',
                $record->start_done_at ?? new \DateTime()
            );
        }
        return $practises ?? [];
    }


}
