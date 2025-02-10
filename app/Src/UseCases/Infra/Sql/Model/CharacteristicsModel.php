<?php


declare(strict_types=1);

namespace App\Src\UseCases\Infra\Sql\Model;


use App\Src\UseCases\Domain\Context\Dto\CharacteristicDto;
use App\Src\UseCases\Domain\Context\Model\Characteristic;
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
        'wiki',
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
        return new CharacteristicDto(
            $this->uuid,
            $this->page_label,
            $this->type,
            $this->icon,
            $this->pretty_page_label,
            $this->opt ?? [],
            $this->wiki,
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
