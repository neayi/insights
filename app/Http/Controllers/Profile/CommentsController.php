<?php


namespace App\Http\Controllers\Profile;


use App\Http\Controllers\Controller;
use App\Src\UseCases\Domain\Context\Queries\GetLastWikiUserComments;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    public function showComments(?string $userId)
    {
        $userId = isset($userId) ? $userId : Auth::user()->uuid;
        $comments = app(GetLastWikiUserComments::class)->get($userId);
        return view('users.profile.comments', [
            'comments' => $comments,
            'picture' => Auth::user()->adminlte_image()
        ]);
    }
}
