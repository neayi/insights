<?php


namespace App\Http\Controllers\Profile;


use App\Http\Controllers\Controller;
use App\Src\UseCases\Domain\Agricultural\Queries\ContextQueryByUser;
use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;
use App\Src\UseCases\Domain\Users\Dto\GetUserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    private function description()
    {
        return 'Président du Gabb32 : les Bios du Gers (Groupement des agriculteurs biologiques et biodynamiques).

J’ai commencé à convertir mon exploitation en agriculture biologique en 2010. Suite à ce changement, j’ai cherché à reconcevoir mon système de culture en mettant en œuvre des techniques autres que le désherbage chimique pour la gestion des adventices sur mes parcelles.

Mes convictions: Sol couvert l’hiver, vie du sol, contre l’érosion, enrichir le sol en matière organique, avec légumineuses pour apport d’azote, meilleure structure et porosité du sol.';
    }


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
            'description' => $this->description(),
            'characteristics' => array_merge($context['productions'], $context['characteristics'])
        ]);
    }

    public function updateProfilePicture(Request $request)
    {
        if ($request->has('picture')) {
            $picture['path_picture'] = $request->file('picture')->path();
            $picture['original_name'] = $request->file('picture')->getClientOriginalName();
            $picture['mine_type'] = $request->file('picture')->getMimeType();
        }


    }

    public function updateAccountData()
    {
        return [];
    }



}
