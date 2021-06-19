<?php


namespace App\Http\Controllers\Profile;


use App\Http\Controllers\Controller;
use App\Src\UseCases\Domain\Context\Queries\GetLastWikiUserComments;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    public function showComments()
    {
        $comments = app(GetLastWikiUserComments::class)->get(Auth::user()->uuid);
        return view('users.profile.comments', [
            'comments' => $comments,
            'picture' => Auth::user()->adminlte_image()
        ]);
    }
}
