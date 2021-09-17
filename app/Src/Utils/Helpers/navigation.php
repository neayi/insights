<?php

use Illuminate\Support\Facades\Auth;

function redirectToWiki()
{
    $user = Auth::user();
    $user->wiki_token = session()->get('wiki_token');
    $user->save();
    $callback = urldecode(session()->get('wiki_callback'));
    return redirect($callback);
}
