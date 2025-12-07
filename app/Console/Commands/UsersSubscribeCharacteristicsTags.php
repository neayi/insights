<?php

namespace App\Console\Commands;

use App\Src\UseCases\Domain\Forum\CharacteristicsForumSyncer;
use App\Src\UseCases\Domain\Context\Model\Characteristic;
use App\Src\UseCases\Infra\Sql\Model\InteractionModel;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UsersSubscribeCharacteristicsTags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'characteristics:init-users-subscriptions {--since-x-days=15}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribes users to the characteristics (forum) tags they have in their profile + follow matching page.';

    /**
     * Execute the console command.
     */
    public function handle(CharacteristicsForumSyncer $forumSyncer): void
    {
        $dateThreshold = Carbon::now()->sub(sprintf('%d days', $this->option('since-x-days')))->setTime(0, 0, 0);

        // Get eligible users (having 1+ characteristics; Discourse subscription checked downstream)
        // Eloquent seems not to be optimized to user INNER JOIN in order to filter, using SQL
        $usersInfosQuery = DB::table('users', 'u')
            ->select('u.id AS user_id')
            ->addSelect(
                'characteristics.code AS char_title',
                'characteristics.pretty_page_label AS char_label',
                'characteristics.wiki AS locale',
            )
            ->addSelect('pages.page_id AS characteristic_page_id')
            ->join('user_characteristics', 'user_characteristics.user_id', '=', 'u.id')
            ->join('characteristics', 'characteristics.id', '=', 'user_characteristics.characteristic_id')
            ->join('pages', 'pages.page_id', '=', 'characteristics.page_id', 'left')
            ->whereIn('characteristics.type', [Characteristic::FARMING_TYPE, Characteristic::CROPPING_SYSTEM])
            ->where('user_characteristics.created_at', '>=', $dateThreshold->format('Y-m-d H:i:s'))
        ;

        $users = $usersInfosQuery->get();

        foreach ($users->all() as $userRow) {
            // Ensure the characteristic has a linked page
            if (null !== $userRow->characteristic_page_id) {
                $this->ensureUserFollowsCharacteristicPage(
                    $userRow->user_id,
                    $userRow->characteristic_page_id,
                    $userRow->locale
                );
            }

            $forumSyncer->subscribeCharacteristicTagNotifications(
                $userRow->user_id,
                $userRow->locale,
                $userRow->char_label ?? $userRow->char_title
            );
        }
    }

    private function ensureUserFollowsCharacteristicPage(int $userId, int $pageId, string $wiki): void
    {
        InteractionModel::upsert(
            ['user_id' => $userId, 'page_id' => $pageId, 'follow' => true, 'wiki' => $wiki],
            ['user_id', 'page_id', 'wiki'],
            ['follow']
        );
    }
}
