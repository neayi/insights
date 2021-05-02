<?php


namespace App\Http\Controllers\Profile;


use App\Http\Controllers\Controller;
use App\Src\UseCases\Domain\Agricultural\Dto\GetAllCharacteristics;
use App\Src\UseCases\Domain\Agricultural\Dto\GetUserPractises;
use App\Src\UseCases\Domain\Agricultural\Queries\ContextQueryByUser;
use App\Src\UseCases\Domain\Agricultural\Queries\GetLastWikiUserComments;
use App\Src\UseCases\Domain\Context\UpdateCharacteristics;
use App\Src\UseCases\Domain\Context\UpdateDescription;
use App\Src\UseCases\Domain\Context\UpdateMainData;
use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;
use App\Src\UseCases\Domain\Users\Dto\GetUserRole;
use App\Src\UseCases\Domain\Users\UpdateUserAvatar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show(ContextQueryByUser $contextQueryByUser)
    {
        $allCharacteristics = app(GetAllCharacteristics::class)->get();
        $context = $contextQueryByUser->execute(Auth::user()->uuid)->toArray();
        $user = app(AuthGateway::class)->current()->toArray();
        $roles = app(GetUserRole::class)->get()->toArray();
        $practises = app(GetUserPractises::class)->get(Auth::user()->uuid);

        $usersCharacteristics =  array_merge($context['productions'], $context['characteristics']);
        $uuidsUserCharacteristics = array_column($usersCharacteristics, 'uuid');
        $role = last($user['roles']);

        return view('users.profile.profile', [
            'context' => $context,
            'userRoles' => $roles,
            'role' => $role,
            'user' => $user,
            'characteristics' => $usersCharacteristics,
            'uuidsUserCharacteristics' => $uuidsUserCharacteristics,
            'farmingType' => $allCharacteristics[GetAllCharacteristics::type],
            'croppingType' => $allCharacteristics[GetAllCharacteristics::typeSystem],
        ]);
    }

    public function updateProfilePicture(Request $request, UpdateUserAvatar $updateUserAvatar)
    {
        $picture = [];
        if ($request->has('file')) {
            $picture['path_picture'] = $request->file('file')->path();
            $picture['original_name'] = $request->file('file')->getClientOriginalName();
            $picture['mine_type'] = $request->file('file')->getMimeType();
        }

        $pathPicture = $updateUserAvatar->execute(Auth::user()->uuid, $picture);
        return asset('storage/'.str_replace('app/public/', '', $pathPicture));
    }

    public function updateDescription(Request $request, UpdateDescription $updateDescription)
    {
        $description = $request->input('description', '');
        $updateDescription->execute($description);
        return $description;
    }

    public function updateContext(Request $request, UpdateMainData $updateMainData)
    {
        $role = $request->input('role') !== null ? $request->input('role') : '';
        $firstname = $request->input('firstname') !== null ? $request->input('firstname') : '';
        $lastname = $request->input('lastname') !== null ? $request->input('lastname') : '';
        $postalCode = $request->input('postal_code') !== null ? $request->input('postal_code') : '';
        $email = $request->input('email') !== null ? $request->input('email') : '';
        $sector = $request->input('sector') !== null ? $request->input('sector') : '';
        $structure = $request->input('structure') !== null ? $request->input('structure') : '';

        $updateMainData->execute($postalCode, $sector, $structure, $email, $firstname, $lastname, $role);
        return [];
    }

    public function updateCharacteristics(Request $request, UpdateCharacteristics $updateCharacteristics)
    {
        $characteristics = $request->input('farming_type');
        $updateCharacteristics->execute($characteristics);
        return [];
    }

}
