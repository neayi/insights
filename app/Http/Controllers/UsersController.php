<?php


namespace App\Http\Controllers;


use App\Src\UseCases\Domain\DeleteUserFromOrganization;
use App\Src\UseCases\Domain\Users\DeleteUser;
use App\Src\UseCases\Domain\Users\EditUser;
use App\Src\UseCases\Domain\Users\GetUser;
use App\Src\UseCases\Domain\Users\GetUserStats;
use App\Src\UseCases\Domain\Users\ListUsers;
use App\Src\UseCases\Organizations\GetOrganization;
use App\Src\UseCases\Organizations\GrantUserAsAdminOrganization;
use App\Src\UseCases\Organizations\RevokeUserAsAdminOrganization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                $user['state'] == false ? __('table.invitation_send') : __('users.table.state_active'),
                isset($user['last_login_at']) ?  __('users.table.last_login_occ').(new \DateTime())->setTimestamp(strtotime($user['last_login_at']))->format('Y-m-d H:i:s') : __('common.never'),
                $user['uuid'],
                isset($user['url_picture']) && $user['url_picture'] !== "" ? $user['url_picture'] : url('').'/'.config('adminlte.logo_img'),
                route('user.edit.form', ['id' => $user['uuid']]),
            ];
        }

        return [
            'draw' => $request->get('draw'),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $list,
        ];
    }

    public function editShowForm(string $userId, GetUser $getUser, GetOrganization $getOrganization, GetUserStats $getUserStats)
    {
        $user = $getUser->get($userId);
        if($user->organizationId() !== null) {
            $organization = $getOrganization->get($user->organizationId());
        }
        $stats = $getUserStats->get($userId);
        return view('users/edit_form', [
            'user' => $user->toArray(),
            'stats' => $stats->toArray(),
            'organization' => isset($organization) ? $organization->toArray() : null,
            'action' => route('user.edit', ['id' => $userId])
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
        $request->session()->flash('notif_msg', __('users.message.user.updated'));
        return redirect()->back();
    }

    public function grantAsAdmin(string $userId, string $organizationId, Request $request, GrantUserAsAdminOrganization $grantUserAsAdminOrganization)
    {
        $grantUserAsAdminOrganization->grant($userId, $organizationId);
        $request->session()->flash('notif_msg', __('users.message.user.updated'));
        return redirect()->back();
    }

    public function revokeAsAdmin(string $userId, string $organizationId, Request $request, RevokeUserAsAdminOrganization $grantUserAsAdminOrganization)
    {
        $grantUserAsAdminOrganization->revoke($userId, $organizationId);
        $request->session()->flash('notif_msg', __('users.message.user.updated'));
        return redirect()->back();
    }

    public function delete(string $userId, Request $request, DeleteUser $deleteUser)
    {
        $redirect = 'back';
        if($userId === Auth::id()){
            $redirect = 'login';
        }
        $deleteUser->delete($userId);
        if($redirect === 'login') {
            return redirect()->route('login');
        }
        $request->session()->flash('notif_msg', __('users.message.user.deleted'));
        return redirect()->route('home');
    }

    public function leaveOrganization(string $userId, Request $request, DeleteUserFromOrganization $deleteUserFromOrganization)
    {
        $deleteUserFromOrganization->delete($userId);
        $request->session()->flash('notif_msg', __('users.message.user.updated'));
        return redirect()->back();
    }
}
