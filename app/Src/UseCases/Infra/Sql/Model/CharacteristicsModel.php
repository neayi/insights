<?php


namespace App\Src\UseCases\Infra\Sql\Model;


use App\User;
use Illuminate\Database\Eloquent\Model;

class CharacteristicsModel extends Model
{
    protected $table = 'characteristics';

    protected $fillable = [
        'uuid',
        'main',
        'priority',
        'icon',
        'page_label',
        'page_id',
        'type',
        'code',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->using(UserCharacteristicsModel::class);
    }
}
