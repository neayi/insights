<?php
namespace Database\Seeders;

use App\Src\UseCases\Domain\Context\Model\AnonymousUser;
use App\Src\UseCases\Domain\Context\Model\Interaction;
use App\Src\UseCases\Domain\Context\Model\Page;
use App\Src\UseCases\Domain\Context\Model\RegisteredUser;
use App\Src\UseCases\Domain\Ports\InteractionRepository;
use App\Src\UseCases\Domain\Ports\PageRepository;
use App\User;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class PageDataSeeder extends Seeder
{
    public function run(Faker $faker)
    {
        $interaction = new Interaction(1, true, true, false);
        app(InteractionRepository::class)->save(new AnonymousUser('a'), $interaction);

        $interaction = new Interaction(1, true, true, true);
        app(InteractionRepository::class)->save(new AnonymousUser('b'), $interaction);

        $interaction = new Interaction(1, true, true, true);
        app(InteractionRepository::class)->save(new RegisteredUser('49d09926-4d38-4144-bdae-24ed4eb6c692'), $interaction);

        $interaction = new Interaction(10683, true, true, true);
        app(InteractionRepository::class)->save(new RegisteredUser('49d09926-4d38-4144-bdae-24ed4eb6c692'), $interaction);

        $interaction = new Interaction(2, false, true, true);
        app(InteractionRepository::class)->save(new AnonymousUser('abcde'), $interaction);
    }
}
