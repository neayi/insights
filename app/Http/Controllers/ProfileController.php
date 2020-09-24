<?php


namespace App\Http\Controllers;


use App\Http\Common\Form\UserForm;
use App\Src\UseCases\Domain\Users\EditUser;
use App\Src\UseCases\Domain\Users\GetUser;
use App\Src\UseCases\Domain\Users\GetUserStats;
use App\Src\UseCases\Organizations\GetOrganization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function showEditProfile(GetUser $getUser, GetOrganization $getOrganization, GetUserStats $getUserStats)
    {
        $user = $getUser->get(Auth::user()->uuid);
        if($user->organizationId() !== null) {
            $organization = $getOrganization->get($user->organizationId());
        }
        $stats = $getUserStats->get(Auth::user()->uuid);
        return view('users/edit_form', [
            'user' => $user->toArray(),
            'stats' => $stats->toArray(),
            'organization' => isset($organization) ? $organization->toArray() : null,
            'action' => route('user.edit.profile')
        ]);
    }

    public function processEditProfile(Request $request, EditUser $editUser, UserForm $form)
    {
        $userId = Auth::user()->uuid;
        list($firstname, $lastname, $email, $picture) = $form->process();
        $editUser->edit($userId, $email, $firstname, $lastname, $picture);
        $request->session()->flash('notif_msg', __('users.message.profile.updated'));
        return redirect()->back();
    }
}
