<?php

declare(strict_types=1);

namespace App\Src\UseCases\Infra\Sql\Model;

use App\Src\UseCases\Domain\Context\Dto\ContextDto;
use App\Src\UseCases\Domain\Context\Model\Context;
use App\User;
use Illuminate\Database\Eloquent\Model;

class ContextModel extends Model
{
    protected $table = 'contexts';

    protected $fillable = [
        'description',
        'postal_code',
        'structure',
        'sector',
        'department_number',
        'latitude',
        'longitude',
        'uuid',
        'country'
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
        $characteristicDepartment = CharacteristicsModel::query()
            ->where('code', $this->department_number)
            ->first()
        ;

        if (isset($characteristicDepartment['opt']['number'])) {
            $characteristicDepartment->icon = 'Departement-' . $characteristicDepartment['opt']['number'];
        }

        if(isset($characteristicDepartment)){
            $characteristics->push($characteristicDepartment->toDto());
        }

        return new ContextDto(
            $this->user->firstname,
            $this->user->lastname,
            $this->country ?? null,
            $this->postal_code ?? null,
            $characteristics->toArray(),
            $this->description ?? null,
            $this->sector ?? null,
            $this->structure ?? null,
            $this->user->uuid,
            $this->department_number ?? null
        );
    }

    public function toDomain()
    {
        return new Context(
            $this->uuid,
            $this->user->characteristics()->pluck('uuid')->toArray(),
            $this->description,
            $this->sector,
            $this->structure,
            $this->country,
            $this->postal_code,
            $this->latitude !== null ? (float) $this->latitude : null,
            $this->longitude !== null ? (float) $this->longitude : null,
        );
    }
}
