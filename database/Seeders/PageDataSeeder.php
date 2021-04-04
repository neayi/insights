<?php
namespace Database\Seeders;

use App\Src\UseCases\Domain\Agricultural\Model\AnonymousUser;
use App\Src\UseCases\Domain\Agricultural\Model\Interaction;
use App\Src\UseCases\Domain\Agricultural\Model\Page;
use App\Src\UseCases\Domain\Agricultural\Model\RegisteredUser;
use App\Src\UseCases\Domain\Ports\InteractionRepository;
use App\Src\UseCases\Domain\Ports\PageRepository;
use App\User;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class PageDataSeeder extends Seeder
{
    public function run(Faker $faker)
    {
        $page = new Page(1);
        app(PageRepository::class)->save($page);

        $interaction = new Interaction(1, true, true, false);
        app(InteractionRepository::class)->save(new AnonymousUser('a'), $interaction);

        $interaction = new Interaction(1, true, true, true);
        app(InteractionRepository::class)->save(new AnonymousUser('b'), $interaction);

        $user = new User();
        $user->firstname = 'g';
        $user->lastname = 'g';
        $user->uuid = 'abcde';
        $user->email = 'abc@gmail.com';
        $user->save();

        $interaction = new Interaction(1, true, true, true);
        app(InteractionRepository::class)->save(new RegisteredUser('abcde'), $interaction);

        $interaction = new Interaction(2, false, true, true);
        app(InteractionRepository::class)->save(new AnonymousUser('abcde'), $interaction);
    }
}
