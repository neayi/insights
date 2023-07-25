<?php

declare(strict_types=1);

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

        $token = $user->createToken('api_token');

        return [
            'id' => $user->uuid,
            'name' => $user->firstname.' '.$user->lastname,
            'realname' => $user->firstname.' '.$user->lastname,
            'email' => $user->email,
            'avatar' => $user->adminlte_image(),
            'token' => $token->plainTextToken
        ];
    }

    public function logout()
    {
        $user = Auth::user();
        $wikiUrl = $user->wikiUrl();
        if(isset($user)){
            $user->wiki_token = '';
            $user->save();
            Auth::logout();
        }
        redirect($wikiUrl);
    }
}
