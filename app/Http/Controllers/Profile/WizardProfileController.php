<?php


namespace App\Http\Controllers\Profile;


use App\Http\Controllers\Controller;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\Users\Dto\GetUserRole;
use App\Src\UseCases\Domain\Users\Profile\FillWikiUserProfile;
use App\Src\UseCases\Infra\Gateway\Auth\AuthGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WizardProfileController extends Controller
{
    public function showWizard()
    {
        $user = app(AuthGateway::class)->current()->toArray();
        $roles = app(GetUserRole::class)->get()->toArray();
        return view('users.wizard-profile.wizard', [
            'userRoles' => $roles,
            'firstname' => $user['firstname'],
            'lastname' => $user['lastname']
        ]);
    }

    public function processWizard(Request $request, FillWikiUserProfile $fillWikiUserProfile)
    {
        $role = $request->input('role');
        $firstname = $request->input('firstname') !== null ? $request->input('firstname') : '';
        $lastname = $request->input('lastname') !== null ? $request->input('lastname') : '';
        $postalCode = $request->input('postal_code') !== null ? $request->input('postal_code') : '';
        $farmingType = $request->input('farming_type') !== null ? $request->input('farming_type') : [];

        $fillWikiUserProfile->fill(Auth::user()->uuid, $role, $firstname, $lastname, $postalCode, $farmingType);

        $user = app(UserRepository::class)->getById(Auth::user()->uuid);
        dd($user);
        return redirect(config('neayi.wiki_url'));
    }
}
