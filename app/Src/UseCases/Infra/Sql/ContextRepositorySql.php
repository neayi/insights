<?php


namespace App\Src\UseCases\Infra\Sql;


use App\Src\UseCases\Domain\Agricultural\Model\Context;
use App\Src\UseCases\Domain\Ports\ContextRepository;
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
        return new Context($context->uuid, $context->postal_code, json_decode($context->farmings, true));
    }

    public function add(Context $exploitation, string $userId)
    {
        $contextData = $exploitation->toArray();
        $contextId = DB::table('contexts')->insertGetId($contextData);

        $user = User::where('uuid', $userId)->first();
        $user->context_id = $contextId;
        $user->save();
    }
}
