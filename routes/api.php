<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('user', 'Api\OAuthController@userByToken');


Route::get('user/avatar/{id}/{dim}', 'Api\UserController@avatar');



