<?php


namespace App\Http\Controllers;


use App\Src\UseCases\Domain\Users\EditUser;
use App\Src\UseCases\Domain\Users\GetUser;
use App\Src\UseCases\Domain\Users\ListUsers;
use App\Src\UseCases\Organizations\GetOrganization;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function showListUsers(string $organizationId)
    {
        return view('users/list', ['organization_id' => $organizationId]);
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
                '',
                ucfirst($user['firstname']).' '.ucfirst($user['lastname']),
                $user['email'],
                '', //$user['state'],
                '',
                $user['uuid'],
                $user['url_picture'],
                route('user.edit.form', ['id' => $user['uuid']])
            ];
        }

        return [
            'draw' => $request->get('draw'),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $list,
        ];
    }

    public function editShowForm(string $userId, GetUser $getUser, GetOrganization $getOrganization)
    {
        $user = $getUser->get($userId);
        $organization = $getOrganization->get($user->organizationId());
        return view('users/edit_form', [
            'user' => $user->toArray(),
            'organization' => isset($organization) ? $organization->toArray() : null
        ]);
    }

    public function editProcess(string $userId, Request $request, EditUser $editUser)
    {
        $firstname = $request->input('firstname') !== null ? $request->input('firstname') : '';
        $lastname = $request->input('lastname') !== null ? $request->input('lastname') : '';
        $email = $request->input('email') !== null ? $request->input('email') : '';
        $picture = [];
        if($request->has('logo')){
            $picture['path_picture'] = $request->file('logo')->path();
            $picture['original_name'] = $request->file('logo')->getClientOriginalName();
            $picture['mine_type'] = $request->file('logo')->getMimeType();
        }
        $editUser->edit($userId, $email, $firstname, $lastname, $picture);
        $request->session()->flash('notif_msg', 'Mise à jour de l\'utilisateur réussie');
        return redirect()->back();
    }
}
