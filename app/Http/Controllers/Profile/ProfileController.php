<?php

declare(strict_types=1);

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Src\UseCases\Domain\Context\Model\Characteristic;
use App\Src\UseCases\Domain\Context\Queries\GetContextByUser;
use App\Src\UseCases\Domain\Context\Queries\GetAllCharacteristics;
use App\Src\UseCases\Domain\Context\Queries\GetUserPractises;
use App\Src\UseCases\Domain\Context\Queries\GetInteractionsByUser;
use App\Src\UseCases\Domain\Context\Queries\SearchCharacteristics;
use App\Src\UseCases\Domain\Context\Queries\SearchStructure;
use App\Src\UseCases\Domain\Context\UseCases\AddCharacteristicsToContext;
use App\Src\UseCases\Domain\Context\UseCases\CreateCharacteristic;
use App\Src\UseCases\Domain\Context\UseCases\UpdateCharacteristics;
use App\Src\UseCases\Domain\Context\UseCases\UpdateDescription;
use App\Src\UseCases\Domain\Context\UseCases\UpdateMainData;
use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;
use App\Src\UseCases\Domain\Users\Dto\GetUserRole;
use App\Src\UseCases\Domain\Users\GetUser;
use App\Src\UseCases\Domain\Users\RemoveAvatar;
use App\Src\UseCases\Domain\Users\UpdateUserAvatar;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class ProfileController extends Controller
{
    public function showEdit(GetContextByUser $contextQueryByUser)
    {
        $wikiCode = Auth::user()->wiki;
        $allCharacteristics = app(GetAllCharacteristics::class)->get($wikiCode);
        try {
            $context = $contextQueryByUser->execute(Auth::user()->uuid)->toArray();
        }catch (\Throwable $e){
            Log::emergency($e->getMessage().' '.$e->getLine().' '.$e->getFile().' '.$e->getTraceAsString());
            return redirect()->route('wizard.profile');
        }

        $user = app(AuthGateway::class)->current()->toArray();
        $roles = app(GetUserRole::class)->get()->toArray();
        $practises = app(GetUserPractises::class)->get(Auth::user()->uuid);
        $interactions = app(GetInteractionsByUser::class)->get(Auth::user()->uuid);
        $usersCharacteristics =  array_merge($context['productions'], $context['characteristics'], $context['characteristics_departement']);
        $uuidsUserCharacteristics = array_column($usersCharacteristics, 'uuid');
        $role = last($user['roles']);
        $routeComment = route('profile.comments.show');

        return view('users.profile.profile', [
            'edit' => true,
            'context' => $context,
            'userRoles' => $roles,
            'role' => $role,
            'user' => $user,
            'characteristics' => $usersCharacteristics,
            'uuidsUserCharacteristics' => $uuidsUserCharacteristics,
            'farmingType' => $allCharacteristics[Characteristic::FARMING_TYPE],
            'croppingType' => $allCharacteristics[Characteristic::CROPPING_SYSTEM],
            'practises' => $practises,
            'interactions' => $interactions,
            'routeComment' => $routeComment
        ]);
    }

    public function show(string $username, string $userId, GetContextByUser $contextQueryByUser)
    {
        $user = app(GetUser::class)->get($userId)->toArray();
        $routeComment = route('profile.comments.show', ['user_id' => $userId]);

        $contextRepo = $contextQueryByUser->execute($userId);
        if (!empty($contextRepo)) {
            $context = $contextRepo->toArray();
            $usersCharacteristics = array_merge($context['productions'], $context['characteristics']);
        } else {
            $context = [
                'fullname' => $user['firstname'] . ' ' . $user['lastname'],
                'description' => '',
            ];
            $usersCharacteristics = [];
        }

        $roles = app(GetUserRole::class)->get()->toArray();
        $practises = app(GetUserPractises::class)->get($userId);
        $interactions = app(GetInteractionsByUser::class)->get($userId);
        $uuidsUserCharacteristics = array_column($usersCharacteristics, 'uuid');

        $role = !empty($user['roles']) ? last($user['roles']) : 'others';

        return view('users.profile.profile', [
            'edit' => false,
            'context' => $context,
            'userRoles' => $roles,
            'role' => $role,
            'user' => $user,
            'characteristics' => $usersCharacteristics,
            'uuidsUserCharacteristics' => $uuidsUserCharacteristics,
            'practises' => $practises,
            'interactions' => $interactions,
            'routeComment' => $routeComment,
            'more' => User::query()->where('uuid', $userId)->first()->toArray()
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
        $updateDescription->execute($descProcessed = nl2br(htmlspecialchars($description)));
        return $descProcessed;
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
        $characteristics = $request->input('farming_type', []);
        $updateCharacteristics->execute($characteristics);
        return [];
    }

    public function autoCompleteStructure(Request $request, SearchStructure $searchStructure)
    {
        $qry = $request->input('q');
        return $searchStructure->execute($qry);
    }

    public function searchCharacteristics(Request $request, SearchCharacteristics $searchCharacteristics)
    {
        $search = $request->input('search', '');
        $type = $request->input('type', '');
        return view('users.profile.search-characteristics', [
            'pages' => $searchCharacteristics->execute($type, $search),
            'search' => $search,
            'type' => $type,
        ]);
    }

    public function createCharacteristic(Request $request, CreateCharacteristic $createCharacteristic)
    {
        $title = $request->input('title', '');
        $type = $request->input('type', '');
        $createCharacteristic->execute(Uuid::uuid4()->toString(), $type, $title);
        return redirect()->back();
    }

    public function addCharacteristicsToContext(Request $request, AddCharacteristicsToContext $addCharacteristicsToContext)
    {
        $characteristics = $request->input('farming_type');
        $addCharacteristicsToContext->execute($characteristics);
        return redirect()->back();
    }

    public function removeAvatar(RemoveAvatar $removeAvatar, AuthGateway $authGateway)
    {
        $currentUser = $authGateway->current();
        $removeAvatar->execute($currentUser->id());
        return redirect()->back();
    }
}
