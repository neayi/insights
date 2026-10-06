<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class PurgeUnverifiedUsers extends Command
{
    protected $signature = 'users:purge-unverified {--days= : Minimum account age in days (default: neayi.purge_unverified_users_after_days)} {--dry-run : Only count the accounts that would be deleted}';

    protected $description = 'Deletes accounts (mostly spam) that never verified their email, never used a social login and never interacted with the wiki or the forum.';

    public function handle(): int
    {
        $days = (int) ($this->option('days') ?? config('neayi.purge_unverified_users_after_days'));
        if ($days < 1) {
            $this->error('--days must be at least 1');
            return self::FAILURE;
        }

        $query = $this->purgeableUsers(Carbon::now()->subDays($days));
        $count = (clone $query)->count();

        if ($this->option('dry-run')) {
            $this->info(sprintf('%d unverified accounts older than %d days would be deleted.', $count, $days));
            return self::SUCCESS;
        }

        $deleted = 0;
        (clone $query)->select('u.id', 'u.context_id')->chunkById(500, function ($users) use (&$deleted) {
            $userIds = $users->pluck('id')->all();
            $contextIds = $users->pluck('context_id')->filter()->all();

            DB::transaction(function () use ($userIds, $contextIds) {
                DB::table('user_characteristics')->whereIn('user_id', $userIds)->delete();
                DB::table('contexts')->whereIn('id', $contextIds)->delete();
                DB::table('model_has_roles')->where('model_type', \App\User::class)->whereIn('model_id', $userIds)->delete();
                DB::table('model_has_permissions')->where('model_type', \App\User::class)->whereIn('model_id', $userIds)->delete();
                DB::table('personal_access_tokens')->where('tokenable_type', \App\User::class)->whereIn('tokenable_id', $userIds)->delete();
                DB::table('users')->whereIn('id', $userIds)->delete();
            });

            $deleted += count($userIds);
        }, 'u.id', 'id');

        $this->info(sprintf('Deleted %d unverified accounts older than %d days.', $deleted, $days));

        return self::SUCCESS;
    }

    private function purgeableUsers(Carbon $createdBefore): Builder
    {
        return DB::table('users', 'u')
            ->whereNull('u.email_verified_at')
            ->where('u.created_at', '<', $createdBefore)
            ->where(function (Builder $q) {
                $q->whereNull('u.providers')->orWhereRaw('JSON_LENGTH(u.providers) = 0');
            })
            ->whereNotExists(fn (Builder $q) => $q->from('interactions', 'i')->whereColumn('i.user_id', 'u.id'))
            ->whereNotExists(fn (Builder $q) => $q->from('discourse_profiles', 'd')->whereColumn('d.user_id', 'u.id'))
            ->whereNotExists(fn (Builder $q) => $q->from('model_has_roles', 'm')
                ->join('roles', 'roles.id', '=', 'm.role_id')
                ->where('roles.name', 'admin')
                ->where('m.model_type', \App\User::class)
                ->whereColumn('m.model_id', 'u.id'));
    }
}
