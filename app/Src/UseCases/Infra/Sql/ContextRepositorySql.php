<?php


namespace App\Src\UseCases\Infra\Sql;


use App\Src\UseCases\Domain\Agricultural\Dto\ContextDto;
use App\Src\UseCases\Domain\Agricultural\Model\Context;
use App\Src\UseCases\Domain\Ports\ContextRepository;
use App\Src\UseCases\Infra\Sql\Model\CharacteristicsModel;
use App\Src\UseCases\Infra\Sql\Model\ContextModel;
use App\User;
use Illuminate\Support\Facades\DB;

class ContextRepositorySql implements ContextRepository
{
    public function getByUser(string $userId):?Context
    {
        $user = User::where('uuid', $userId)->first();
        $context = DB::table('contexts')->where('id', $user->context_id)->first();
        if($context == null){
            return null;
        }
        return new Context($context->uuid, $context->postal_code, $user->characteristics()->pluck('uuid')->toArray());
    }

    public function add(Context $context, string $userId)
    {
        $contextData = collect($context->toArray());
        $user = User::where('uuid', $userId)->first();

        $farmings = $contextData->get('farmings');
        foreach($farmings as $farming){
            $characteristic = CharacteristicsModel::where('uuid', (string)$farming)->first();
            $user->characteristics()->save($characteristic);
        }

        $contextId = DB::table('contexts')->insertGetId($contextData->except('farmings')->toArray());

        $user->context_id = $contextId;
        $user->save();
    }

    public function getByUserDto(string $userId): ?ContextDto
    {
        $user = User::where('uuid', $userId)->first();
        if($user == null){
            return null;
        }
        $context = ContextModel::find($user->context_id);
        if($context == null){
            return null;
        }
        $characteristics = $user->characteristics()->get()->transform(function(CharacteristicsModel $item){
            return $item->toDto();
        });
        return new ContextDto($user->firstname, $user->lastname, $context->postal_code, $characteristics->toArray());
    }
}
