<?php


namespace App\Http\Controllers\BackOffice;


use App\Http\Common\Form\UserForm;
use App\Http\Controllers\Controller;
use App\Src\Insights\Insights\Application\Read\Organizations\GetOrganization;
use App\Src\Insights\Insights\Application\UseCase\Organizations\GrantUserAsAdminOrganization;
use App\Src\Insights\Insights\Application\UseCase\Organizations\Invitation\DeleteUserFromOrganization;
use App\Src\Insights\Insights\Application\UseCase\Organizations\RevokeUserAsAdminOrganization;
use App\Src\Insights\Users\Application\Read\GetUserStats;
use App\Src\Insights\Users\Application\Read\ListUsers;
use App\Src\Insights\Users\Application\UseCase\DeleteUser;
use App\Src\Insights\Users\Application\UseCase\EditUser;
use App\Src\UseCases\Domain\Users\GetUser;
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
        $total = init($users['total'], 0);
        $list = [];
        foreach ($users['list'] as $user){
            $user = $user->toArray();
            $list[] = [
                '',
                $user['firstname'].' '.$user['lastname'],
                $user['email'],
                $user['state'] == false ? __('users.table.invitation_send') : __('users.table.state_active'),
                isset($user['last_login_at']) ?  __('users.table.last_login_occ').(new \DateTime())->setTimestamp(strtotime($user['last_login_at']))->format('Y-m-d H:i:s') : __('common.never'),
                $user['uuid'],
                isset($user['url_picture']) && $user['url_picture'] !== "" ? $user['url_picture'] : url('').'/'.config('adminlte.logo_img'),
                route('user.edit.form', ['id' => $user['uuid']]),
            ];
        }

        return format($total, $list);
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

    public function editProcess(string $userId, Request $request, EditUser $editUser, UserForm $form)
    {
        list($firstname, $lastname, $email, $picture) = $form->process();
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
