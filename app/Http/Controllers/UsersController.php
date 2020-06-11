<?php


namespace App\Http\Controllers;


use App\Src\UseCases\Domain\Users\ListUsers;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function showListUsers(string $organizationId)
    {
        return view('users/list',['organization_id' => $organizationId]);
    }

    public function listUsers(string $organizationId, Request $request, ListUsers $listUsers)
    {
        $page = $request->input('start')/10 + 1;

        $users = $listUsers->list($organizationId, $page, 10);
        $total = isset($users['total']) ? $users['total'] : 0;
        $list = [];
        foreach ($users['list'] as $user){
            $user = $user->toArray();
            $list[] = [
                $user['firsname'].' '.$user['lastname'],
                $user['email'],
                $user['state'],
                '',
                $user['uuid'],
            ];
        }

        return [
            'draw' => $request->get('draw'),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $list,
        ];
    }
}
