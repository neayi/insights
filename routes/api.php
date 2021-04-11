<?php

use Illuminate\Support\Facades\Route;

Route::get('user', 'Api\OAuthController@userByToken');


Route::get('user/avatar/{id}/{dim}', 'Api\UserController@avatar');
Route::get('icon/{id}/{dim?}', 'Api\PictureController@serve')->name('api.icon.serve');
Route::get('user/{id}/context', 'Api\UserController@context');


Route::middleware(['wiki.session.id', 'is.stateful'])->group(function () {
    Route::get('page/{pageId}/counts', 'Api\InteractionController@countsInteractionOnPage');
    Route::get('user/page/{pageId}', 'Api\InteractionController@getInteractionsOnPageByUser');
    Route::post('page/{pageId}', 'Api\InteractionController@handle');
});


