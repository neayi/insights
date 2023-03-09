<?php


namespace App\Src\Context\Infrastructure\Model;


use App\Src\Context\Domain\Characteristic;
use App\Src\UseCases\Domain\Context\Dto\CharacteristicDto;
use App\Src\UseCases\Infra\Sql\Model\UserCharacteristicsModel;
use App\User;
use Database\Factories\CharacteristicFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharacteristicsModel extends Model
{
    use HasFactory;

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
        'visible',
    ];

    protected $casts = [
        'opt' => 'array'
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
            $this->pretty_page_label,
            $this->opt ?? []
        );
    }

    public function picturePath():string
    {
        return storage_path('app/public/characteristics/' . $this->uuid . '.png');
    }

    public function toDomain()
    {
        return new Characteristic($this->uuid, $this->type, $this->code, $this->attributes['visible']);
    }

    protected static function newFactory()
    {
        return CharacteristicFactory::new();
    }
}
