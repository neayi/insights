<?php


namespace App\Http\Controllers\Profile;


use App\Http\Controllers\Controller;
use App\Src\UseCases\Domain\Agricultural\Queries\ContextQueryByUser;
use App\Src\UseCases\Domain\Context\UpdateDescription;
use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;
use App\Src\UseCases\Domain\Users\Dto\GetUserRole;
use App\Src\UseCases\Domain\Users\UpdateUserAvatar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show(ContextQueryByUser $contextQueryByUser)
    {
        $context = $contextQueryByUser->execute(Auth::user()->uuid)->toArray();
        $user = app(AuthGateway::class)->current()->toArray();
        $roles = app(GetUserRole::class)->get()->toArray();

        $role = last($user['roles']);
        return view('users.profile.profile', [
            'context' => $context,
            'userRoles' => $roles,
            'role' => $role,
            'user' => $user,
            'characteristics' => array_merge($context['productions'], $context['characteristics'])
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

    public function updateContext()
    {
        return [];
    }



}
