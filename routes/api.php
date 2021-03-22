<?php

use Illuminate\Support\Facades\Route;

Route::get('user', 'Api\OAuthController@userByToken');


Route::get('user/avatar/{id}/{dim}', 'Api\UserController@avatar');
Route::get('icon/{id}/{dim?}', 'Api\PictureController@serve')->name('api.icon.serve');
Route::get('user/{id}/context', 'Api\UserController@context');





