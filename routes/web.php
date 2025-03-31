<?php

use Illuminate\Support\Facades\Route;

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', function (){
    return redirect('/login');
});

Route::get('user/logout', 'Api\OAuthController@logout');

Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider')->name('auth.provider');
Route::get('register/{provider}', 'Auth\RegisterController@redirectToProvider')->name('register.auth.provider');
Route::any('login/callback/{provider}', 'Auth\LoginController@handleProviderCallback')->name('auth.provider.callback');
Route::any('register/callback/{provider}', 'Auth\RegisterController@handleProviderCallback')->name('auth.provider.register_callback');
Route::get('register-social-network/error', 'Auth\RegisterController@showErrorRegisterFormSocialNetwork')->name('register-social-network');
Route::post('register-social-network/error', 'Auth\RegisterController@registerAfterError')->name('auth.register-social-network');

Route::group(['middleware' => ['auth', 'is.wizard.profile.available']], function() {
    Route::get('profile-wizard', 'Profile\WizardProfileController@showWizard')->name('wizard.profile');
    Route::post('profile-wizard', 'Profile\WizardProfileController@processWizard')->name('wizard.profile.process');
});

Route::get('{wikiCode}/neayi/discourse/sso', 'Discourse\SsoController@login')
    ->middleware('auth')
    ->name('neayi.discourse.sso');

Route::get('tp/{username}/{uuid}', 'Profile\ProfileController@show')->name('show.profile.logged-visitor');
Route::get('comments', 'Profile\CommentsController@showComments')->name('profile.comments.show');

Route::group(['middleware' => ['auth', 'verified']], function() {
    Route::get('profile', 'Profile\ProfileController@showEdit')->name('show.profile');
    Route::post('update-avatar', 'Profile\ProfileController@updateProfilePicture')->name('user.update.avatar');
    Route::post('delete-avatar', 'Profile\ProfileController@removeAvatar')->name('user.delete.avatar');
    Route::post('context/update/description', 'Profile\ProfileController@updateDescription')->name('context.update.description');
    Route::post('context/update', 'Profile\ProfileController@updateContext')->name('context.update');
    Route::post('context/update/characteristics', 'Profile\ProfileController@updateCharacteristics')->name('context.update.characteristics');

    Route::get('structures', 'Profile\ProfileController@autoCompleteStructure')->name('profile.structure.search');
    Route::get('context/search-characteristics', 'Profile\ProfileController@searchCharacteristics')->name('profile.characteristics.search');
    Route::post('context/characteristic', 'Profile\ProfileController@createCharacteristic')->name('profile.characteristic.create');
    Route::post('context/characteristic/add', 'Profile\ProfileController@addCharacteristicsToContext')->name('profile.characteristic.add');

    Route::post('update-avatar', 'Profile\ProfileController@updateProfilePicture')->name('user.update.avatar');
});

Route::group(['middleware' => ['auth', 'auth.check.role']], function() {
    Route::get('/user/{id}/edit/form', 'UsersController@editShowForm')->name('user.edit.form');
    Route::post('/user/{id}/edit', 'UsersController@editProcess')->name('user.edit');
    Route::post('/user/{id}/delete', 'UsersController@delete')->name('user.delete');

    Route::get('/user/edit/profile', 'ProfileController@showEditProfile')->name('user.edit.profile.show');
    Route::post('/user/edit/profile', 'ProfileController@processEditProfile')->name('user.edit.profile');

    Route::get('/geolocation', \App\Http\Controllers\Profile\GeolocationController::class)->name('geolocation');
});

