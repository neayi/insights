<?php


namespace App\Src\UseCases\Infra\Sql\Model;


use App\Src\Context\Domain\Context;
use App\Src\UseCases\Domain\Context\Dto\ContextDto;
use App\User;
use Illuminate\Database\Eloquent\Model;

class ContextModel extends Model
{
    protected $table = 'contexts';

    protected $fillable = ['description', 'postal_code', 'structure', 'sector', 'department_number', 'coordinates', 'uuid'];

    protected $casts = [
        'coordinates' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'context_id');
    }

    public function toDto():ContextDto
    {
        $characteristics = $this->user->characteristics()->get()->transform(function(CharacteristicsModel $item){
            return $item->toDto();
        });
        $characteristicDepartment = CharacteristicsModel::query()->where('code', $this->department_number)->first();

        if(isset($characteristicDepartment)){
            $characteristics->push($characteristicDepartment->toDto());
        }

        return new ContextDto(
            $this->user->firstname,
            $this->user->lastname,
            $this->postal_code ?? '',
            $characteristics->toArray(),
            $this->description,
            $this->sector ?? '',
            $this->structure ?? '',
            $this->user->uuid,
            $this->department_number ?? ''
        );
    }

    public function toDomain()
    {
        return new Context(
            $this->uuid,
            $this->postal_code,
            $this->user->characteristics()->pluck('uuid')->toArray(),
            $this->description,
            $this->sector,
            $this->structure,
            $this->department_number,
            $this->coordinates
        );
    }
}
