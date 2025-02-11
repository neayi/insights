<?php

use Illuminate\Support\Facades\Route;

Route::get('user', 'Api\OAuthController@userByToken');


Route::get('user/avatar/{id}/{dim}', 'Api\UserController@avatar');
Route::get('user/discourse/avatar/{username}/{letter}/{color}/{dim}', 'Api\UserController@avatarDiscourse');
Route::get('user/{id}/context', 'Api\UserController@context');

Route::middleware(['wiki.session.id', 'auth:sanctum'])->group(function () {
    Route::get('page/{pageId}/counts', 'Api\InteractionController@countsInteractionOnPage');
    Route::get('user/page/{pageId}', 'Api\InteractionController@getInteractionsOnPageByUser');
    Route::post('page/{pageId}', 'Api\InteractionController@handle');
});

Route::get('page/{pageId}/followers', 'Api\InteractionController@followersOfPage');
Route::get('page/{pageId}/stats', 'Api\InteractionController@getStatsDepartment');



