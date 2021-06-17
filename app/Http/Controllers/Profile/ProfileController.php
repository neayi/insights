<?php


namespace App\Http\Controllers\Profile;


use App\Http\Controllers\Controller;
use App\Src\UseCases\Domain\Context\Dto\GetAllCharacteristics;
use App\Src\UseCases\Domain\Context\Queries\ContextQueryByUser;
use App\Src\UseCases\Domain\Context\Queries\GetUserPractises;
use App\Src\UseCases\Domain\Context\Queries\InteractionsQueryByUser;
use App\Src\UseCases\Domain\Context\Queries\SearchCharacteristics;
use App\Src\UseCases\Domain\Context\UseCases\AddCharacteristicsToContext;
use App\Src\UseCases\Domain\Context\UseCases\CreateCharacteristic;
use App\Src\UseCases\Domain\Context\UseCases\UpdateCharacteristics;
use App\Src\UseCases\Domain\Context\UseCases\UpdateDescription;
use App\Src\UseCases\Domain\Context\UseCases\UpdateMainData;
use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;
use App\Src\UseCases\Domain\Users\Dto\GetUserRole;
use App\Src\UseCases\Domain\Users\RemoveAvatar;
use App\Src\UseCases\Domain\Users\UpdateUserAvatar;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

class ProfileController extends Controller
{
    public function show(ContextQueryByUser $contextQueryByUser)
    {
        $allCharacteristics = app(GetAllCharacteristics::class)->get();
        try {
            $context = $contextQueryByUser->execute(Auth::user()->uuid)->toArray();
        }catch (\Throwable $e){
            return redirect()->route('wizard.profile');
        }
        $user = app(AuthGateway::class)->current()->toArray();
        $roles = app(GetUserRole::class)->get()->toArray();
        $practises = app(GetUserPractises::class)->get(Auth::user()->uuid);
        $interactions = app(InteractionsQueryByUser::class)->get(Auth::user()->uuid);
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
            'practises' => $practises,
            'interactions' => $interactions
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
        $updateDescription->execute($descProcessed = htmlspecialchars(nl2br($description)));
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
        $characteristics = $request->input('farming_type');
        $updateCharacteristics->execute($characteristics);
        return [];
    }

    public function autoCompleteStructure(Request $request)
    {
        $qry = $request->input('q');
        $client = new Client();
        $uri = config('wiki.api_uri').'?action=query&list=search&srwhat=text&srsearch='.$qry.'&srqiprofile=classic_noboostlinks&srnamespace=3000&format=json';
        $response = $client->get($uri);
        $content = json_decode($response->getBody()->getContents(), true);
        if(isset($content['query']['search'])){
            $results = array_column($content['query']['search'], 'title');
            return array_map(function ($item){
                return str_replace('Structure:', '', $item);
            }, $results);
        }
        return ['results' => []];
    }

    public function searchCharacteristics(Request $request, SearchCharacteristics $searchCharacteristics)
    {
        $search = $request->input('search', '');
        $type = $request->input('type', '');
        return view('users.profile.search-characteristics', [
            'characteristics' => $searchCharacteristics->execute($type, $search),
            'search' => $search,
            'type' => $type,
        ]);
    }

    public function createCharacteristic(Request $request, CreateCharacteristic $createCharacteristic)
    {
        $title = $request->input('title', '');
        $type = $request->input('type', '');
        $createCharacteristic->execute(Uuid::uuid4(), $type, $title);
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
