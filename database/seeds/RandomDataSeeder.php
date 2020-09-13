<?php

use App\Src\UseCases\Domain\Address;
use App\Src\UseCases\Domain\Organization;
use App\Src\UseCases\Domain\User;
use App\Src\UseCases\Domain\Users\EditUserStats;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class RandomDataSeeder extends Seeder
{
    public function run(Faker $faker)
    {
        for($i=0; $i < 11; $i++){
            $organizationId = Uuid::uuid4();
            $address = new Address($faker->city, $faker->address, $faker->address, $faker->postcode);
            $path = $faker->image("/tmp");
            $organization = new Organization($organizationId, $faker->company, $path, $address);
            $organization->create('jpg');

            for($j=0; $j < 5; $j++) {
                $userId = Uuid::uuid4();
                $path = $faker->image("/tmp");

                $user = new User($userId, $e = $faker->email,$f = $faker->firstName, $l = $faker->name, $organizationId, '');
                $user->create(Hash::make('secret'));

                $user->update($e, $f, $l, $path);
                if($j == 0){
                    $user->grantAsAdmin();
                }

                $stats = [
                    'number_contributions' => rand(0,100),
                    'number_questions' => rand(0,100),
                    'number_answers' => rand(0,100),
                    'number_votes' => rand(0,100),
                    'number_validations' => rand(0,100),
                    'number_wiki_edit' => rand(0,100),
                    'number_contributions_last_30_days' => rand(0,100),
                ];
                app(EditUserStats::class)->edit($userId, $stats);
            }
        }
    }
}
