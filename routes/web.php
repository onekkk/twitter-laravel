<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/welcome', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/', 'HomeController@index')->name('home');
Route::post('/', 'HomeController@add');
Route::get('/userDetail/{userId}', 'UserDetailController@index')->where('id', '[0-9]+')->name('userDetail');
Route::post('/follow', 'FollowController@upload');
Route::get('/profile', 'ProfileController@index')->name('profile');
Route::get('/profile_edit', 'ProfileEditController@index')->name('profile_edit');
Route::post('/profile_edit', 'ProfileEditController@update');

