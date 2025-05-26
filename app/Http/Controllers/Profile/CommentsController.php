<?php

declare(strict_types=1);

namespace App\Http\Controllers\Profile;


use App\Http\Controllers\Controller;
use App\Src\UseCases\Domain\Context\Queries\GetLastUserComments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    public function showComments(Request $request, GetLastUserComments $getLastUserComments)
    {
        $userId = $request->input('user_id');
        if (empty($userId)) {
            $connectedUser = Auth::user();
            if (empty($connectedUser)) {
                return null;
            }
            $userId = Auth::user()->uuid;
        }

        $comments = $getLastUserComments->get($userId);
        return view('users.profile.comments', [
            'comments' => $comments
        ]);
    }
}
