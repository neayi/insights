<?php

use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/organization/add/form', 'OrganizationsController@showAddForm')->name('organization.add.form');
Route::post('/organization/add', 'OrganizationsController@processAdd')->name('organization.add');
