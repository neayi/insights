<?php


namespace App\Http\Controllers;


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

    public function processEditProfile(Request $request, EditUser $editUser)
    {
        $userId = Auth::user()->uuid;
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
        $request->session()->flash('notif_msg', __('users.message.profile.updated'));
        return redirect()->back();
    }
}
