<?php

namespace App\Console\Commands;

use App\Src\Users\EditUserStats;
use App\Src\Users\UserRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportUsersStatsFromWiki extends Command
{
    protected $signature = 'users:import-wiki-stats';

    protected $description = 'Import the wiki stats for all users';

    private $dbWiki;
    private $userRepository;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(UserRepository $userRepository)
    {
        $this->dbWiki = DB::connection('mysql_wiki');
        $this->userRepository = $userRepository;

        $this->dbWiki->table('user')
            ->orderBy('user.user_id', 'desc')
            ->chunk(200, function ($records){
                foreach ($records as $record) {

                    $user = $this->userRepository->getByEmail($record->user_email);
                    if(!isset($user)){
                        continue;
                    }

                    $questions = $this->getQuestionsNumber($record->user_id);
                    $answers = $this->getAnswersNumber($record->user_id);
                    $votes = $this->getVotesNumber($record->user_id);
                    $editsOnWiki = $this->getEditsOnWikiNumber($record->user_id);


                    $numberContributions = $questions + $answers + $votes + $editsOnWiki;
                    $fields = [
                        'number_contributions' => $numberContributions,
                        'number_questions' => $questions,
                        'number_answers' => $answers,
                        'number_votes' => $votes,
                        'number_validations' => 0,
                        'number_wiki_edit' => $editsOnWiki,
                        'number_contributions_last_30_days' => 0
                    ];

                    app(EditUserStats::class)->edit($user->id(), $fields);
                }
            });
    }

    private function getQuestionsNumber(int $userId):int
    {
        $questions = $this->dbWiki->table("cs_watchlist", 'cw')
            ->join('cs_comment_data', 'cs_comment_data.cst_page_id', 'cw.cst_wl_page_id')
            ->whereNull('cs_comment_data.cst_parent_page_id')
            ->where('cw.cst_wl_user_id', $userId)
            ->selectRaw('count(*) as number_questions')
            ->first();
        return $questions->number_questions;
    }

    private function getAnswersNumber(int $userId)
    {
        $answers = $this->dbWiki->table("user", 'u')
            ->join('actor', 'actor.actor_user', 'u.user_id')
            ->join('revision_actor_temp', 'revision_actor_temp.revactor_actor', 'actor.actor_id')
            ->join('cs_comment_data', 'cs_comment_data.cst_page_id', 'revision_actor_temp.revactor_page')
            ->whereNotNull('cs_comment_data.cst_parent_page_id')
            ->where('u.user_id', $userId)
            ->selectRaw('count(*) as number_answers')
            ->first();
        return $answers->number_answers;
    }

    private function getVotesNumber(int $userId)
    {
        $votes = $this->dbWiki->table("cs_votes", 'cv')
            ->where('cv.cst_v_user_id', $userId)
            ->selectRaw('count(*) as number_votes')
            ->first();
        return $votes->number_votes;
    }

    private function getEditsOnWikiNumber(int $userId)
    {
        $editsOnWiki = $this->dbWiki->table("recentchanges", 'rc')
            ->join('actor', 'actor.actor_id', 'rc.rc_actor')
            ->join('user', 'user.user_id', 'actor.actor_user')
            ->where('user.user_id', $userId)
            ->where('rc.rc_source', 'mw.edit')
            ->selectRaw('count(*) as number_edit')
            ->first();
        return $editsOnWiki->number_edit;
    }
}
