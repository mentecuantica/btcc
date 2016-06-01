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
    if (Auth::guest()) {
        return view('landing.index');
    }

    return view('dashboard.index');
});

Route::group(['middleware'=>'auth'], function () {
    Route::get('users/{user}', function (Btcc\Models\User $user) {
        return $user;
    });

    Route::get('/account', '\Btcc\Http\Controllers\AccountController@index');
    Route::get('/tree', '\Btcc\Http\Controllers\TreeController@index');
    Route::get('/tree/binaryJson', 'TreeController@binaryTree');
    Route::get('/tree/show/{id}', 'TreeController@show');

    Route::resource('transaction', 'TransactionController');
    Route::resource('partner', 'PartnerController');

    Route::post('profile/update',['as'=>'profile.update', 'uses' => 'AccountController@profileUpdate']);

    Route::get('/invite', 'InviteController@index');
    Route::post('/invite/create', 'InviteController@create');
    Route::get('/invite/list', 'InviteController@list');


    // Registration Routes...
    Route::get('register', 'Auth\AuthController@showRegistrationForm');
    Route::post('register', 'Auth\AuthController@register');
    Route::get('logout', 'Auth\AuthController@logout');



    Route::any('test/initTree', 'TempController@initTree');
    Route::any('test/gsb', 'TempController@globalSingletonBinding');
    Route::any('test/gdisb', 'TempController@globalDISingletonBinding');
});





// Authentication Routes...
$this->get('login', 'Auth\AuthController@showLoginForm');
$this->post('login', 'Auth\AuthController@login');

// Password Reset Routes...
$this->get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
$this->post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
$this->post('password/reset', 'Auth\PasswordController@reset');

//Route::get('/home', 'HomeController@index');


/**
 * For edu and test
 */
















// Instead, you could have a handy list of patterns and reuse them everywhere:
// Patterns
Route::pattern('id', '\d+');
Route::pattern('hash', '[a-z0-9]+');
Route::pattern('hex', '[a-f0-9]+');
Route::pattern('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
Route::pattern('base', '[a-zA-Z0-9]+');
Route::pattern('slug', '[a-z0-9-]+');
Route::pattern('username', '[a-z0-9_-]{3,16}');