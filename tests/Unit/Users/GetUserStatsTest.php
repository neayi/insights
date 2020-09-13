<?php


namespace Tests\Unit\Users;


use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;
use App\Src\UseCases\Domain\Users\EditUserStats;
use App\Src\UseCases\Domain\Users\GetUserStats;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class GetUserStatsTest extends TestCase
{
    private $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = app(UserRepository::class);

        if(config('app.env') === 'testing-ti'){
            Artisan::call('migrate:fresh');
        }
        Event::fake();
    }

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
