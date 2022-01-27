<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('auth.check.role');
    }

    public function index()
    {
        return view('home');
    }
}
