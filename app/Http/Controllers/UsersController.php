<?php


namespace App\Http\Controllers;


use App\Http\Common\Form\UserForm;
use App\Src\UseCases\Domain\Users\DeleteUser;
use App\Src\UseCases\Domain\Users\EditUser;
use App\Src\UseCases\Domain\Users\GetUser;
use App\Src\UseCases\Domain\Users\GetUserStats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function editShowForm(string $userId, GetUser $getUser, GetUserStats $getUserStats)
    {
        $user = $getUser->get($userId);
        $stats = $getUserStats->get($userId);
        return view('users/edit_form', [
            'user' => $user->toArray(),
            'stats' => $stats->toArray(),
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
