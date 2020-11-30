<?php


namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;


class OAuthController extends BaseController
{
    public function userByToken(Request $request)
    {
        $token = $request->input('wiki_token', null);

        if($token === null || $token === ''){
            return ['error' => 'invalid_token'];
        }
        $user = User::where('wiki_token', $token)->first();

        if($user === null){
            return ['error' => 'invalid_token'];
        }

        return [
            'id' => $user->uuid,
            'name' => ucfirst($user->firstname).' '.ucfirst($user->lastname),
            'realname' => ucfirst($user->firstname).' '.ucfirst($user->lastname),
            'email' => $user->email,
            'avatar' => $user->adminlte_image()
        ];
    }

    public function logout()
    {
        $user = Auth::user();
        $user->wiki_token = '';
        $user->save();
        Auth::logout();
        redirect(config('neayi.wiki_url'));
    }
}
