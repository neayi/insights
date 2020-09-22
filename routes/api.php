<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('user', 'Api\OAuthController@userByToken');



