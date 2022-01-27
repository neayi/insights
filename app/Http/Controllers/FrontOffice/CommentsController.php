<?php


namespace App\Http\Controllers\FrontOffice;


use App\Http\Controllers\Controller;
use App\Src\UseCases\Domain\Context\Queries\GetLastWikiUserComments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    public function showComments(Request $request)
    {
        $userId = $request->input('user_id');
        if (empty($userId))
            $userId = Auth::user()->uuid;

        $comments = app(GetLastWikiUserComments::class)->get($userId);
        return view('users.profile.comments', [
            'comments' => $comments
        ]);
    }
}
