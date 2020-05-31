<?php


namespace Tests\Unit\Organization\Services;


use App\Mail\UserJoinsOrganizationToUser;
use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\Services\SendMailToUserWhenHeJoinsOrganization;
use App\Src\UseCases\Domain\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class SendMailToUserWhenHeJoinsOrganizationTest extends TestCase
{
    private $organizationRepository;
    private $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->organizationRepository = app(OrganizationRepository::class);
        $this->userRepository = app(UserRepository::class);

        if(config('app.env') === 'testing-ti'){
            Artisan::call('migrate:fresh');
        }

        Mail::fake();
    }

    public function testShouldSendMail()
    {
        $userEmail = 'anemail@gmail.com';
        $user = new User($uid = Uuid::uuid4()->toString(), $userEmail);
        $this->userRepository->add($user);

        app(SendMailToUserWhenHeJoinsOrganization::class)->send($uid);

        Mail::assertSent(UserJoinsOrganizationToUser::class, function ($mail) use ($userEmail){
            return $mail->hasTo($userEmail);
        });
    }
}
