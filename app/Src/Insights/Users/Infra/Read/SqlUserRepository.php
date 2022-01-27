<?php

namespace App\Src\Insights\Users\Infra\Read;

use App\Src\UseCases\Domain\Shared\Model\Dto;
use App\Src\UseCases\Domain\Users\Identity;
use App\Src\UseCases\Domain\Users\State;
use App\Src\UseCases\Domain\Users\UserDto;
use App\User;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class SqlUserRepository
{
    public function getById(string $userId):?Dto
    {
        $user = User::query()->where('uuid', $userId)->first();
        if(!isset($user)){
            return null;
        }
        return $user->toDto();
    }

    public function search(string $organizationId, int $page, int $perPage = 10): array
    {
        $first = DB::table('users', 'u')
            ->selectRaw('u.last_login_at, u.path_picture, u.uuid, u.id as uid, u.firstname as ufirstname, u.lastname as ulastname, u.email as uemail, invitations.id as iid, invitations.firstname as ifirstname, invitations.lastname as ilastname, invitations.email as iemail')
            ->leftJoin('invitations', 'invitations.email', 'u.email')
            ->where('u.organization_id', $organizationId);

        $records = DB::table('users', 'u')
            ->selectRaw('u.last_login_at, u.path_picture, u.uuid, u.id as uid, u.firstname as ufirstname, u.lastname as ulastname, u.email as uemail, invitations.id as iid, invitations.firstname as ifirstname, invitations.lastname as ilastname, invitations.email as iemail')
            ->rightJoin('invitations', 'invitations.email', 'u.email')
            ->where('invitations.organization_id', $organizationId)
            ->union($first)
            ->get()
        ;

        $count = $records->count();

        $records = DB::table('users', 'u')
            ->selectRaw('u.last_login_at, u.path_picture, u.uuid, u.id as uid, u.firstname as ufirstname, u.lastname as ulastname, u.email as uemail, invitations.id as iid, invitations.firstname as ifirstname, invitations.lastname as ilastname, invitations.email as iemail')
            ->rightJoin('invitations', 'invitations.email', 'u.email')
            ->where('invitations.organization_id', $organizationId)
            ->union($first)
            ->offset(($page-1)*$perPage)
            ->limit($perPage)
            ->get();
        if(empty($records)){
            return [];
        }

        $users = [];
        foreach($records as $record){
            if($record->uuid === null){
                $identity = new Identity(Uuid::uuid4(), $record->iemail, $record->ifirstname, $record->ilastname, null);
                $state = new State($organizationId, null, false, []);
                $users[] = new UserDto($identity, $state);
                continue;
            }
            $roles = \App\User::find($record->uid)->roles()->pluck('name')->toArray();
            $identity = new Identity($record->uuid, $record->uemail, $record->ufirstname, $record->ulastname, $record->path_picture);
            $state = new State($organizationId, $record->last_login_at, true, $roles);
            $users[] = new UserDto($identity, $state);
        }
        return [
            'list' => $users,
            'total' => $count
        ];
    }

}
