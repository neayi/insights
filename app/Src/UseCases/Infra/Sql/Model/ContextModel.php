<?php


namespace App\Src\UseCases\Infra\Sql\Model;


use App\Src\UseCases\Domain\Context\Dto\ContextDto;
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
