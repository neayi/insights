<?php


namespace App\Http\Controllers\Profile;


use App\Http\Controllers\Controller;
use App\Src\UseCases\Domain\Context\Dto\GetFarmingType;
use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;
use App\Src\UseCases\Domain\Users\Dto\GetUserRole;
use App\Src\UseCases\Domain\Users\Profile\FillWikiUserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller used for filling public profile for the wiki
 */
class WizardProfileController extends Controller
{
    public function showWizard()
    {
        $farmingType = app(GetFarmingType::class)->get();
        $user = app(AuthGateway::class)->current()->toArray();
        $roles = app(GetUserRole::class)->get()->toArray();

        return view('users.wizard-profile.wizard', [
            'userRoles' => $roles,
            'firstname' => $user['firstname'],
            'lastname' => $user['lastname'],
            'farmingType' => $farmingType[GetFarmingType::type]['others'],
            'farmingTypeMain' => array_merge($farmingType[GetFarmingType::type]['main'], $farmingType[GetFarmingType::type]['others']),
            'croppingType' => $farmingType[GetFarmingType::typeSystem]['others'],
            'croppingTypeMain' => array_merge($farmingType[GetFarmingType::typeSystem]['main'], $farmingType[GetFarmingType::typeSystem]['others']),
            'email' => $user['email']
        ]);
    }

    public function processWizard(Request $request, FillWikiUserProfile $fillWikiUserProfile)
    {
        $role = $request->input('role') !== null ? $request->input('role') : '';
        $firstname = $request->input('firstname') !== null ? $request->input('firstname') : '';
        $lastname = $request->input('lastname') !== null ? $request->input('lastname') : '';
        $postalCode = $request->input('postal_code') !== null ? $request->input('postal_code') : '';
        $email = $request->input('email') !== null ? $request->input('email') : '';
        $farmingType = $request->input('farming_type') !== null ? $request->input('farming_type') : [];

        $fillWikiUserProfile->fill(Auth::user()->uuid, $role, $firstname, $lastname, $email, $postalCode, $farmingType);
        if(session()->has('wiki_callback')){
            $user = Auth::user();
            $user->wiki_token = session()->get('wiki_token');
            $user->save();
            $callback = urldecode(session()->get('wiki_callback'));
            return redirect($callback);
        }
        return redirect(config('neayi.wiki_url'));
    }
}
