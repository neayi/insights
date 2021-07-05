<?php


namespace App\Src\UseCases\Infra\Sql\Model;


use App\Src\UseCases\Domain\Context\Dto\ContextDto;
use App\Src\UseCases\Domain\Context\Model\PostalCode;
use App\User;
use Illuminate\Database\Eloquent\Model;

class ContextModel extends Model
{
    protected $table = 'contexts';

    protected $fillable = ['description', 'postal_code', 'structure', 'sector'];


    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'context_id');
    }

    public function toDto():ContextDto
    {
        $characteristics = $this->user->characteristics()->get()->transform(function(CharacteristicsModel $item){
            return $item->toDto();
        });

        $numberDepartment = (new PostalCode($this->postal_code))->department();
        $characteristicDepartment = CharacteristicsModel::query()->where('code', $numberDepartment)->first();

        if(isset($characteristicDepartment)){
            $characteristics->push($characteristicDepartment->toDto());
        }

        return new ContextDto(
            $this->user->firstname,
            $this->user->lastname,
            $this->postal_code,
            $characteristics->toArray(),
            $this->description,
            $this->sector ?? '',
            $this->structure ?? ''
        );
    }
}
