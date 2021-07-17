<?php


namespace App\Src\UseCases\Infra\Sql;


use App\Src\UseCases\Domain\Context\Dto\ContextDto;
use App\Src\UseCases\Domain\Context\Model\Context;
use App\Src\UseCases\Domain\Ports\ContextRepository;
use App\Src\UseCases\Infra\Sql\Model\CharacteristicsModel;
use App\Src\UseCases\Infra\Sql\Model\ContextModel;
use App\User;

class ContextRepositorySql implements ContextRepository
{
    public function getByUser(string $userId):?Context
    {
        $user = User::where('uuid', $userId)->first();
        return $user->context !== null ? $user->context->toDomain() : null;
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

        $contextModel = (new ContextModel())->fill($contextData->except('farmings')->toArray());
        $contextModel->save();
        $user->context_id = $contextModel->id;
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

        $numberDepartment = $context->department_number;
        $characteristicDepartment = CharacteristicsModel::query()->where('code', $numberDepartment)->first();
        if(isset($characteristicDepartment)){
            $characteristics->push($characteristicDepartment->toDto());
        }

        return new ContextDto(
            $user->firstname,
            $user->lastname,
            $context->postal_code,
            $characteristics->toArray(),
            $context->description,
            $context->sector ?? '',
            $context->structure ?? '',
            $user->uuid,
            false,
            $numberDepartment ?? '',
        );
    }

    public function update(Context $context, string $userId)
    {
        $user = User::where('uuid', $userId)->first();
        if($user === null){
            return null;
        }

        $contextModel = ContextModel::where('uuid', $context->id())->first();
        if($contextModel === null){
            return null;
        }
        $contextData = collect($context->toArray());
        $contextModel->fill($contextData->except('farmings')->toArray());

        $farmings = $contextData->get('farmings');
        $characteristics = [];
        foreach($farmings as $farming){
            $characteristicModel = CharacteristicsModel::where('uuid', (string)$farming)->first();
            if(isset($characteristicModel)) {
                $characteristics[] = $characteristicModel->id;
            }
        }
        $user->characteristics()->sync($characteristics);

        $contextModel->save();
    }


}
