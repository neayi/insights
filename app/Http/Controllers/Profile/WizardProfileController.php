<?php

declare(strict_types=1);

namespace App\Http\Controllers\Profile;


use App\Http\Controllers\Controller;
use App\Src\UseCases\Domain\Context\Model\Characteristic;
use App\Src\UseCases\Domain\Context\Queries\GetAllCharacteristics;
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
    public function showWizard(Request $request)
    {
        $langs = explode('_', $request->getPreferredLanguage());
        $wikiCode = strtolower($langs[1]) ?? 'fr';
        $characteristics = app(GetAllCharacteristics::class)->get($wikiCode);
        $user = app(AuthGateway::class)->current()->toArray();
        $roles = app(GetUserRole::class)->get()->toArray();

        return view('users.wizard-profile.wizard', [
            'userRoles' => $roles,
            'firstname' => $user['firstname'],
            'lastname' => $user['lastname'],
            'farmingTypeMain' => $characteristics[Characteristic::FARMING_TYPE],
            'croppingTypeMain' => $characteristics[Characteristic::CROPPING_SYSTEM],
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
        return redirect()->route('verification.notice');
    }
}
