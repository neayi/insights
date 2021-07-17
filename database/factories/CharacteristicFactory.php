<?php

namespace Database\Factories;

use App\Src\UseCases\Domain\Context\Model\Characteristic;
use App\Src\UseCases\Infra\Sql\Model\CharacteristicsModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class CharacteristicFactory extends Factory
{
    protected $model = CharacteristicsModel::class;

    public function definition()
    {
        return [
            'uuid' => $uuid = uniqid(),
            'main' => true,
            'priority' => 0,
            'icon' => 'public/characteristics/'.$uuid.'.png',
            'page_label' => $this->faker->word(),
            'pretty_page_label' => $this->faker->word(),
            'page_id' => 1,
            'type' => Characteristic::CROPPING_SYSTEM,
            'code' => uniqid(),
        ];
    }
}
