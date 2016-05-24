<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/account', '\Btcc\Http\Controllers\AccountController@index');


Route::post('profile/update',['as'=>'profile.update', 'uses' => 'AccountController@profileUpdate']);

Route::get('users/{user}', function (Btcc\Models\User $user) {
    return $user;
});

Route::auth();

Route::get('/home', 'HomeController@index');
