<?php

use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider')->name('auth.provider');
Route::get('register/{provider}', 'Auth\RegisterController@redirectToProvider')->name('register.auth.provider');
Route::any('callback/{provider}', 'Auth\LoginController@handleProviderCallback')->name('auth.provider.callback');
Route::any('register/callback/{provider}', 'Auth\RegisterController@handleProviderCallback')->name('auth.provider.register_callback');

Route::get('/home', 'HomeController@index')->name('home');
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
