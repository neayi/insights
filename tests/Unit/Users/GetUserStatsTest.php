<?php


namespace Tests\Unit\Users;


use App\Src\Insights\Users\Application\Read\GetUserStats;
use App\Src\UseCases\Domain\User;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class GetUserStatsTest extends TestCase
{
    public function testShouldGetUserStats()
    {
        $user = new User($id = Uuid::uuid4(), 'g@gmail.com', 'first', 'last', null);
        $this->userRepository->add($user);

        $statsExpected = [
            'number_contributions' => 0,
            'number_questions' => 0,
            'number_answers' => 0,
            'number_votes' => 0,
            'number_validations' => 0,
            'number_wiki_edit' => 0,
            'number_contributions_last_30_days' => 0,
        ];
        app(GetUserStats::class)->get($id);

        $statsSaved = $this->userRepository->getStats($id);
        self::assertEquals($statsExpected, $statsSaved->toArray());
    }
}
