<?php


namespace App\Src\UseCases\Infra\Sql\Model;


use Illuminate\Database\Eloquent\Relations\Pivot;

class UserCharacteristicsModel extends Pivot
{
    protected $table = 'user_characteristics';

    protected $cast = [
        'value' => 'array'
    ];
}
