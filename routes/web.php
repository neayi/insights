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
Route::any('login/callback/{provider}', 'Auth\LoginController@handleProviderCallback')->middleware('transform.request.login')->name('auth.provider.callback');
Route::any('register/callback/{provider}', 'Auth\RegisterController@handleProviderCallback')->middleware('transform.request.login')->name('auth.provider.register_callback');
Route::get('register-social-network/error', 'Auth\RegisterController@showErrorRegisterFormSocialNetwork')->name('register-social-network');
Route::post('register-social-network/error', 'Auth\RegisterController@registerAfterError')->name('auth.register-social-network');

Route::group(['middleware' => ['auth', 'is.wizard.profile.available']], function() {
    Route::get('profile-wizard', 'Profile\WizardProfileController@showWizard')->name('wizard.profile');
    Route::post('profile-wizard', 'Profile\WizardProfileController@processWizard')->name('wizard.profile.process');
});

Route::get('tp/{username}/{uuid}', 'Profile\ProfileController@show')->name('show.profile.logged-visitor');

Route::group(['middleware' => ['auth']], function() {
    Route::get('profile', 'Profile\ProfileController@showEdit')->name('show.profile');
    Route::post('update-avatar', 'Profile\ProfileController@updateProfilePicture')->name('user.update.avatar');
    Route::post('delete-avatar', 'Profile\ProfileController@removeAvatar')->name('user.delete.avatar');
    Route::post('context/update/description', 'Profile\ProfileController@updateDescription')->name('context.update.description');
    Route::post('context/update', 'Profile\ProfileController@updateContext')->name('context.update');
    Route::post('context/update/characteristics', 'Profile\ProfileController@updateCharacteristics')->name('context.update.characteristics');

    Route::get('comments', 'Profile\CommentsController@showComments')->name('profile.comments.show');
    Route::get('structures', 'Profile\ProfileController@autoCompleteStructure')->name('profile.structure.search');
    Route::get('context/search-characteristics', 'Profile\ProfileController@searchCharacteristics')->name('profile.characteristics.search');
    Route::post('context/characteristic', 'Profile\ProfileController@createCharacteristic')->name('profile.characteristic.create');
    Route::post('context/characteristic/add', 'Profile\ProfileController@addCharacteristicsToContext')->name('profile.characteristic.add');

    Route::post('update-avatar', 'Profile\ProfileController@updateProfilePicture')->name('user.update.avatar');

});

Route::group(['middleware' => ['auth', 'auth.check.role']], function() {
    Route::get('/organizations', 'OrganizationsController@list')->name('organization.list');
    Route::post('/organizations', 'OrganizationsController@listOrganizations')->name('organization.list.datatable');
    Route::get('/organization/add/form', 'OrganizationsController@showAddForm')->name('organization.add.form');
    Route::post('/organization/add', 'OrganizationsController@processAdd')->name('organization.add');
    Route::get('/organization/{id}/edit/form', 'OrganizationsController@showEditForm')->name('organization.edit.form');
    Route::post('/organization/{id}/edit', 'OrganizationsController@processEdit')->name('organization.edit');

    Route::post('/organization/users/prepare-invite', 'OrganizationsController@prepareInvitation')->name('organization.users.prepare-invite');
    Route::post('/organization/users/invite', 'OrganizationsController@sendInvitations')->name('organization.users.invite');

    Route::get('/organization/{id}/users', 'UsersController@showListUsers')->name('users.list');
    Route::post('/organization/{id}/users', 'UsersController@listUsers')->name('users.list.datatable');
    Route::get('/organization/invite/accept', 'OrganizationsController@acceptInvite')->name('organization.invite.show');
    Route::get('/organization/user/join', 'OrganizationsController@joinOrganization')->name('organization.user.join');

    Route::get('/user/{id}/edit/form', 'UsersController@editShowForm')->name('user.edit.form');
    Route::post('/user/{id}/edit', 'UsersController@editProcess')->name('user.edit');
    Route::post('/user/{id}/delete', 'UsersController@delete')->name('user.delete');
    Route::post('/user/{id}/organization/{organization}/grant', 'UsersController@grantAsAdmin')->name('user.grant-admin.organization');
    Route::post('/user/{id}/organization/{organization}/revoke', 'UsersController@revokeAsAdmin')->name('user.revoke-admin.organization');
    Route::post('/user/{id}/organization/leave', 'UsersController@leaveOrganization')->name('user.leave.organization');

    Route::get('/user/edit/profile', 'ProfileController@showEditProfile')->name('user.edit.profile.show');
    Route::post('/user/edit/profile', 'ProfileController@processEditProfile')->name('user.edit.profile');
});
