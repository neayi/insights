<?php

declare(strict_types=1);

namespace App\Src\UseCases\Infra\Sql;


use App\Src\UseCases\Domain\Context\Dto\FollowerDto;
use App\Src\UseCases\Domain\Context\Dto\PractiseVo;
use App\Src\UseCases\Domain\Context\Model\CanInteract;
use App\Src\UseCases\Domain\Context\Model\Interaction;
use App\Src\UseCases\Domain\Ports\InteractionRepository;
use App\Src\UseCases\Infra\Sql\Model\CharacteristicsModel;
use App\Src\UseCases\Infra\Sql\Model\InteractionModel;
use App\Src\UseCases\Infra\Sql\Model\PageModel;
use App\User;
use Illuminate\Contracts\Pagination\Paginator;

class InteractionPageRepositorySql implements InteractionRepository
{
    public function save(CanInteract $canInteract, Interaction $interaction)
    {
        $user = User::query()->where('uuid', $canInteract->identifier())->first();

        $interactionModel = InteractionModel::query()
            ->where($canInteract->key(), $user->id ?? $canInteract->identifier())
            ->where('page_id', $interaction->pageId())
            ->first();

        if ($interactionModel === null) {
            $interactionModel = new InteractionModel();
            $interactionModel->{$canInteract->key()} = $user->id ?? $canInteract->identifier();
        }

        $interactionModel->fill($interaction->toArray());
        $interactionModel->start_done_at = $interaction->toArray()['value']['start_at'] ?? null;
        $interactionModel->save();
    }

    public function getByInteractUser(CanInteract $canInteract, int $pageId, string $wikiCode): ?Interaction
    {
        if($canInteract->key() == 'user_id') {
            $user = User::query()->where('uuid', $canInteract->identifier())->first();
        }

        $interactionModel = InteractionModel::query()
            ->where($canInteract->key(), $user->id ?? $canInteract->identifier())
            ->where('page_id', $pageId)
            ->where('wiki', $wikiCode)
            ->first();

        if(!isset($interactionModel)){
            return null;
        }

        $value = $interactionModel->value ?? [];
        return new Interaction(
            $pageId,
            $interactionModel->follow,
            $interactionModel->applause,
            $interactionModel->done,
            $value,
            $interactionModel->wiki,
        );
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

    public function getCountInteractionsOnPage(int $pageId, string $wikiCode):array
    {
        $applause = InteractionModel::query()
            ->where('page_id', $pageId)
            ->where('applause', true)
            ->where('wiki', $wikiCode)
            ->count();

        // For follows and done, don't include Neayi people - we tend to spoil the results with our faces
        $follow = InteractionModel::query()
            ->where('page_id', $pageId)
            ->where('follow', true)
            ->join('users', 'users.id', 'interactions.user_id')
            ->where('users.email', 'NOT LIKE', '%@neayi.com')
            ->where('wiki', $wikiCode)
            ->count();

        $done = InteractionModel::query()
            ->where('page_id', $pageId)
            ->where('done', true)
            ->join('users', 'users.id', 'interactions.user_id')
            ->where('users.email', 'NOT LIKE', '%@neayi.com')
            ->where('wiki', $wikiCode)
            ->count();

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
            if(!isset($page)){
                continue;
            }
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
                $record->start_done_at ?? null
            );
        }
        return $practises ?? [];
    }


    /**
     * @param int $pageId
     * @param string $type
     * @param string|null $cp
     * @param string|null $characteristicId
     * @param string|null $characteristicIdCroppingSystem
     * @return Paginator
     *
    */
    public function getFollowersPage(int $pageId, string $type = 'follow', ?string $departmentNumber = null, ?string $characteristicId = null, ?string $characteristicIdCroppingSystem = null, ?string $wikiCode = null): Paginator
    {
        return  InteractionModel::query()
            ->with('user.context')
            ->where(function ($query) use ($type){
                $query->when($type === 'follow', function ($query) {
                    $query->where('follow', true);
                    $query->orWhere('done', true);
                })
                ->when($type === 'do', function ($query) {
                    $query->where('done', true);
                });
            })
            ->when($characteristicId !== null, function ($query) use($characteristicId) {
                $characteristic = CharacteristicsModel::query()->where('uuid', $characteristicId)->first();
                if(!isset($characteristic)){
                    return;
                }
                $query->whereRaw(
                    'exists(SELECT * FROM user_characteristics where characteristic_id = ? AND user_characteristics.user_id = interactions.user_id)',
                    $characteristic->id
                );
            })
            ->when($characteristicIdCroppingSystem !== null, function ($query) use($characteristicIdCroppingSystem) {
                $characteristic = CharacteristicsModel::query()->where('uuid', $characteristicIdCroppingSystem)->first();
                if(!isset($characteristic)){
                    return;
                }
                $query->whereRaw(
                    'exists(SELECT * FROM user_characteristics where characteristic_id = ? AND user_characteristics.user_id = interactions.user_id)',
                    $characteristic->id
                );
            })
            ->when($departmentNumber !== null, function ($query) use($departmentNumber) {
                $query
                    ->join('users', 'users.id', 'interactions.user_id')
                    ->join('contexts', 'users.context_id', 'contexts.id')
                    ->where('contexts.department_number', $departmentNumber);
            })
            ->where('page_id', $pageId)
            ->where('wiki', $wikiCode)
            ->whereNotNull('interactions.user_id')
            ->orderBy('interactions.updated_at', 'desc')
            ->paginate()
            ->through(function ($item){
                $context = null;
                if ($item->user->context !== null) {
                    $context = $item->user->context->toDto();
                }
                return new FollowerDto($item->user->toDto(), $context, $item->toDto());
            })
        ;
    }
}
