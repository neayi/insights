<?php

namespace Database\Factories;

use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'firstname' => $this->faker->name(),
            'lastname' => $this->faker->name(),
            'uuid' => Uuid::uuid4(),
            'email' => $this->faker->email()
        ];
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
