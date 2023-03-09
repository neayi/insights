<?php


namespace App\Src\Users\Infrastructure\Model;


use Illuminate\Database\Eloquent\Relations\Pivot;

class UserCharacteristicsModel extends Pivot
{
    protected $table = 'user_characteristics';

    protected $cast = [
        'value' => 'array'
    ];
}
