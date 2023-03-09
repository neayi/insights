<?php
namespace Database\Seeders;

use App\Src\Context\Domain\AnonymousUser;
use App\Src\Context\Domain\Interaction;
use App\Src\Context\Domain\InteractionRepository;
use App\Src\Context\Domain\RegisteredUser;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

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
