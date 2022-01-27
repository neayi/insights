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
    Route::get('profile-wizard', 'FrontOffice\WizardProfileController@showWizard')->name('wizard.profile');
    Route::post('profile-wizard', 'FrontOffice\WizardProfileController@processWizard')->name('wizard.profile.process');
});

Route::get('tp/{username}/{uuid}', 'FrontOffice\ProfileController@show')->name('show.profile.logged-visitor');
Route::get('comments', 'FrontOffice\CommentsController@showComments')->name('profile.comments.show');

Route::group(['middleware' => ['auth', 'verified']], function() {
    Route::get('profile', 'FrontOffice\ProfileController@showEdit')->name('show.profile');
    Route::post('update-avatar', 'FrontOffice\ProfileController@updateProfilePicture')->name('user.update.avatar');
    Route::post('delete-avatar', 'FrontOffice\ProfileController@removeAvatar')->name('user.delete.avatar');
    Route::post('context/update/description', 'FrontOffice\ProfileController@updateDescription')->name('context.update.description');
    Route::post('context/update', 'FrontOffice\ProfileController@updateContext')->name('context.update');
    Route::post('context/update/characteristics', 'FrontOffice\ProfileController@updateCharacteristics')->name('context.update.characteristics');

    Route::get('structures', 'FrontOffice\ProfileController@autoCompleteStructure')->name('profile.structure.search');
    Route::get('context/search-characteristics', 'FrontOffice\ProfileController@searchCharacteristics')->name('profile.characteristics.search');
    Route::post('context/characteristic', 'FrontOffice\ProfileController@createCharacteristic')->name('profile.characteristic.create');
    Route::post('context/characteristic/add', 'FrontOffice\ProfileController@addCharacteristicsToContext')->name('profile.characteristic.add');

    Route::post('update-avatar', 'FrontOffice\ProfileController@updateProfilePicture')->name('user.update.avatar');
});

Route::group(['middleware' => ['auth', 'auth.check.role']], function() {
    Route::get('/organizations', 'BackOffice\OrganizationsController@list')->name('organization.list');
    Route::post('/organizations', 'BackOffice\OrganizationsController@listOrganizations')->name('organization.list.datatable');
    Route::get('/organization/add/form', 'BackOffice\OrganizationsController@showAddForm')->name('organization.add.form');
    Route::post('/organization/add', 'BackOffice\OrganizationsController@processAdd')->name('organization.add');
    Route::get('/organization/{id}/edit/form', 'BackOffice\OrganizationsController@showEditForm')->name('organization.edit.form');
    Route::post('/organization/{id}/edit', 'BackOffice\OrganizationsController@processEdit')->name('organization.edit');

    Route::post('/organization/users/prepare-invite', 'BackOffice\OrganizationsController@prepareInvitation')->name('organization.users.prepare-invite');
    Route::post('/organization/users/invite', 'BackOffice\OrganizationsController@sendInvitations')->name('organization.users.invite');

    Route::get('/organization/{id}/users', 'BackOffice\UsersController@showListUsers')->name('users.list');
    Route::post('/organization/{id}/users', 'BackOffice\UsersController@listUsers')->name('users.list.datatable');
    Route::get('/organization/invite/accept', 'BackOffice\OrganizationsController@acceptInvite')->name('organization.invite.show');
    Route::get('/organization/user/join', 'BackOffice\OrganizationsController@joinOrganization')->name('organization.user.join');

    Route::get('/user/{id}/edit/form', 'BackOffice\UsersController@editShowForm')->name('user.edit.form');
    Route::post('/user/{id}/edit', 'BackOffice\UsersController@editProcess')->name('user.edit');
    Route::post('/user/{id}/delete', 'BackOffice\UsersController@delete')->name('user.delete');
    Route::post('/user/{id}/organization/{organization}/grant', 'BackOffice\UsersController@grantAsAdmin')->name('user.grant-admin.organization');
    Route::post('/user/{id}/organization/{organization}/revoke', 'BackOffice\UsersController@revokeAsAdmin')->name('user.revoke-admin.organization');
    Route::post('/user/{id}/organization/leave', 'BackOffice\UsersController@leaveOrganization')->name('user.leave.organization');

    Route::get('/user/edit/profile', 'BackOffice\ProfileController@showEditProfile')->name('user.edit.profile.show');
    Route::post('/user/edit/profile', 'BackOffice\ProfileController@processEditProfile')->name('user.edit.profile');
});
