<?php

declare(strict_types=1);

namespace App\Http\Controllers\Profile;

use App\LocalesConfig;
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
    public function showWizard()
    {
        $locale = \App\LocalesConfig::getPreferredLocale();
        $characteristics = app(GetAllCharacteristics::class)->get($locale->code);
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
        $email = $request->input('email') !== null ? $request->input('email') : '';
        $farmingType = $request->input('farming_type') !== null ? $request->input('farming_type') : [];
        $country = $request->input('country') !== null ? $request->input('country') : '';
        $postalCode = $request->input('postal_code') !== null ? $request->input('postal_code') : '';

        $fillWikiUserProfile->fill(Auth::user()->uuid, $role, $firstname, $lastname, $email, $country, $postalCode, $farmingType);

        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            if ($request->session()->has('sso')) {
                $sso = $request->session()->get('sso');
                $sig = $request->session()->get('sig');
                $wikiCode = $request->session()->get('wikiCode');
                \Illuminate\Support\Facades\Log::info('WizardProfile SSO redirect', ['wikiCode' => $wikiCode, 'sso' => $sso, 'sig' => $sig]);
                return redirect($wikiCode . '/neayi/discourse/sso?sso=' . urlencode($sso) . '&sig=' . urlencode($sig));
            }

            if ($request->session()->has('wiki_callback')) {
                $user->wiki_token = $request->session()->get('wiki_token');
                $user->save();
                $callback = urldecode($request->session()->get('wiki_callback'));
                return redirect($callback);
            }

            return redirect()->route('show.profile');
        }

        return redirect()->route('verification.notice');
    }
}
