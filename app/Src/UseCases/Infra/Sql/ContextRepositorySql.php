<?php

declare(strict_types=1);

namespace App\Src\UseCases\Infra\Sql;


use App\Src\UseCases\Domain\Context\Dto\ContextDto;
use App\Src\UseCases\Domain\Context\Model\Context;
use App\Src\UseCases\Domain\Ports\ContextRepository;
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

        $characteristics = $contextData->get('characteristics');
        $user->addCharacteristics($characteristics);

        $contextModel = (new ContextModel())->fill($contextData->except('characteristics')->toArray());
        $contextModel->save();
        $user->context_id = $contextModel->id;
        $user->save();
    }

    public function getByUserDto(string $userId): ?ContextDto
    {
        $user = User::where('uuid', $userId)->first();
        if($user === null){
            return null;
        }
        return $user->context !== null ? $user->context->toDto($user->uuid) : null;
    }

    public function update(Context $context, string $userId)
    {
        $user = User::where('uuid', $userId)->first();
        if($user === null){
            return null;
        }

        $contextModel = $user->context;
        if($contextModel === null){
            return null;
        }
        $contextData = collect($context->toArray());
        $contextModel->fill($contextData->except('characteristics')->toArray());
        $contextModel->save();

        $characteristics = $contextData->get('characteristics');
        $user->syncCharacteristics($characteristics);
    }
}
