<?php

namespace App\Console\Commands;

use App\Src\UseCases\Domain\Context\Model\Characteristic;
use Http\Message\Authentication\Chain;
use Illuminate\Console\Command;

class UsersSubscribeCharacteristicsTags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'characteristics:init-users-subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribes every users to the characteristics (forum) tags they have in their profile.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // Get eligible users (subscribed to Discourse + having 1+ characteristics)
        // Eloquent seems not to be optimized to user INNER JOIN in order to filter, using SQL

        $userInfosSql = <<<SQL
            SELECT u.id, u.firstname, u.lastname, u.email, u.uuid, u.discourse_id, u.discourse_username
            FROM users AS u
                INNER JOIN user_characteristics AS uc ON uc.user_id = u.id
                INNER JOIN characteristics AS c ON c.id = uc.characteristic_id AND c.type IN :characteristicTypes
            WHERE u.discourse_id IS NOT NULL
        SQL;

        $usersInfos = \DB::table('users', 'u')
            ->select('u.id', 'u.firstname', 'u.lastname', 'u.email', 'u.uuid', 'u.discourse_id', 'u.discourse_username')
            ->join('user_characteristics', 'user_characteristics.user_id', '=', 'u.id')
            ->join('characteristics', 'characteristics.id', '=', 'user_characteristics.characteristic_id')
            ->whereNotNull('u.discourse_id')
            ->whereIn('characteristics.type', [Characteristic::FARMING_TYPE, Characteristic::CROPPING_SYSTEM])
        ;

        dd($usersInfos->toSql(), $usersInfos->getBindings());

        $users = $usersQuery->get();

        dd(count($users), $users[0]);
    }
}
