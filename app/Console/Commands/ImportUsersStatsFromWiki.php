<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportUsersStatsFromWiki extends Command
{
    protected $signature = 'users:import-wiki-stats';

    protected $description = 'Import the wiki stats for all users';

    private $dbWiki;

    public function __construct()
    {
        parent::__construct();
        $this->dbWiki = DB::connection('mysql_wiki');
    }

    public function handle()
    {
        $records = $this->dbWiki->table('user')->select(['*'])->get();

        $questions = $this->dbWiki->table("cs_watchlist", 'cw')
            ->join('cs_comment_data', 'cs_comment_data.cst_page_id', 'cw.cst_wl_page_id')
            ->whereNull('cs_comment_data.cst_parent_page_id')
            ->where('cw.cst_wl_user_id', 4)
            ->selectRaw('count(*) as number_questions')
            ->first();

        $answers =  $this->dbWiki->table("user", 'u')
            ->join('actor', 'actor.actor_user', 'u.user_id')
            ->join('revision_actor_temp', 'revision_actor_temp.revactor_actor', 'actor.actor_id')
            ->join('cs_comment_data', 'cs_comment_data.cst_page_id', 'revision_actor_temp.revactor_page')
            ->whereNotNull('cs_comment_data.cst_parent_page_id')
            ->where('u.user_id', 4)
            ->selectRaw('count(*) as number_answers')
            ->first();

        $votes = $this->dbWiki->table("cs_votes", 'cv')
            ->where('cv.cst_v_user_id', 4)
            ->selectRaw('count(*) as number_votes')
            ->first();

        $editsOnWiki = $this->dbWiki->table("recentchanges", 'rc')
            ->join('actor', 'actor.actor_id', 'rc.rc_actor')
            ->join('user', 'user.user_id', 'actor.actor_user')
            ->where('user.user_id', 4)
            ->where('rc.rc_source', 'mw.edit')
            ->selectRaw('count(*) as number_edit')
            ->first();

        dd($questions, $answers, $votes, $editsOnWiki);
    }
}
