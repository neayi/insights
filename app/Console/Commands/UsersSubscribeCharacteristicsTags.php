<?php

namespace App\Console\Commands;

use App\Src\UseCases\Domain\Forum\CharacteristicsForumSyncer;
use App\Src\UseCases\Domain\Context\Model\Characteristic;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

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
    protected $description = 'Subscribes every users to the characteristics (forum) tags they have in their profile.';

    /**
     * Execute the console command.
     */
    public function handle(CharacteristicsForumSyncer $forumSyncer): void
    {
        $dateThreshold = Carbon::now()->sub(sprintf('%d days', $this->option('since-x-days')))->setTime(0, 0, 0);

        // Get eligible users (subscribed to Discourse + having 1+ characteristics)
        // Eloquent seems not to be optimized to user INNER JOIN in order to filter, using SQL
        $usersInfosQuery = DB::table('users', 'u')
            ->select('u.default_locale')
            ->addSelect('discourse_profiles.username AS discourse_username', 'discourse_profiles.locale AS discourse_locale')
            ->addSelect('characteristics.code AS char_title', 'characteristics.pretty_page_label AS char_label')
            ->join('user_characteristics', 'user_characteristics.user_id', '=', 'u.id')
            ->join('characteristics', 'characteristics.id', '=', 'user_characteristics.characteristic_id')
            ->join('discourse_profiles', 'discourse_profiles.user_id', '=', 'u.id')
            ->whereNotNull('discourse_profiles.ext_id')
            ->where('discourse_profiles.username', '!=', '')
            ->whereIn('characteristics.type', [Characteristic::FARMING_TYPE, Characteristic::CROPPING_SYSTEM])
            ->where('user_characteristics.created_at', '>=', $dateThreshold->format('Y-m-d H:i:s'))
        ;

        $users = $usersInfosQuery->get();

        foreach ($users->all() as $user) {
            $forumSyncer->subscribeCharacteristicTagNotifications(
                $user->discourse_username,
                $user->discourse_locale,
                $user->char_label ?? $user->char_title
            );
        }
    }
}
