<?php


namespace App\Src\UseCases\Infra\Sql\Model;


use App\Src\UseCases\Domain\Agricultural\Dto\CharacteristicDto;
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
        'pretty_page_label',
        'page_id',
        'type',
        'code',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->using(UserCharacteristicsModel::class);
    }

    public function toDto()
    {
        $icon = null;
        if(isset($this->icon)){
            $icon = route('api.icon.serve', ['id' => $this->uuid]);
        }
        return new CharacteristicDto(
            $this->uuid,
            $this->page_label,
            $this->type,
            $icon,
            $this->pretty_page_label
        );
    }
}
