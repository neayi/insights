<?php


namespace Database\Seeders;


use App\Src\UseCases\Domain\Agricultural\Dto\GetFarmingType;
use App\Src\UseCases\Domain\Users\Profile\FillWikiUserProfile;
use App\Src\UseCases\Infra\Sql\Model\CharacteristicsModel;
use App\User;
use Illuminate\Database\Seeder;

class DocumentationSeeder extends Seeder
{
    public function run()
    {
        $userId = '379189d0-287f-4042-bf81-577deb7696f4';
        $user = User::query()->where('uuid', $userId)->first() ?? new User();
        $user->uuid = '379189d0-287f-4042-bf81-577deb7696f4';
        $user->firstname = "Eric";
        $user->lastname = "Dupond";
        $user->email = "eric.dupond@tripleperformance.com";
        $user->save();

        // php artisan characteristics:import

        $farmingsModel = CharacteristicsModel::query()->where('type', GetFarmingType::type)->get();
        $farmings = $farmingsModel->random(3)->pluck('uuid')->toArray();
        app(FillWikiUserProfile::class)->fill($userId, 'farmer', 'Eric', 'Dupond', "eric.dupond@tripleperformance.com", '83220', $farmings);


    }
}
