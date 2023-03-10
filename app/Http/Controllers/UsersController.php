<?php


namespace App\Http\Controllers;


use App\Http\Common\Form\UserForm;
use App\Src\Users\DeleteUser;
use App\Src\Users\EditUser;
use App\Src\Users\ListUsers;
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

    public function editProcess(string $userId, Request $request, EditUser $editUser, UserForm $form)
    {
        list($firstname, $lastname, $email, $picture) = $form->process();
        $editUser->edit($userId, $email, $firstname, $lastname, $picture);
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
}
