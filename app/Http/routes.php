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

Route::get('/userrepo/', function () {


    \Btcc\Repositories\UserRepository::testCreateTreeUserBundle();

    return view('users.show', []);
});

Route::get('/', function () {
    return view('landing.index');
});

Route::group(['middleware' => ['sent.auth']], function () {
    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/account', 'AccountController@index');

    Route::get('/tree', 'TreeController@index');
    Route::get('/tree/linear', 'TreeController@showLinear');
    Route::get('/tree/binary', 'TreeController@showBinaryAll');
    Route::get('/tree/binary/json', 'TreeController@showBinaryJson');
    Route::get('/tree/binary/free/{id}', 'TreeController@showBinaryFree');
    Route::get('/tree/binary/{id}', 'TreeController@showBinary');
    Route::get('/tree/ternary', 'TreeController@showTernary');
    Route::get('/tree/show/{id}', 'TreeController@showBinary');

    Route::resource('transaction', 'TransactionController');
    Route::resource('partner', 'PartnerController');

    Route::post('profile/update', ['as'   => 'profile.update',
                                   'uses' => 'AccountController@profileUpdate'
    ]);

/*    Route::get('/invite', 'InviteController@index');
    Route::post('/invite/create', 'InviteController@create');
    Route::get('/invite/list', 'InviteController@list');*/

    // Registration Routes...
    //Route::get('register', 'Auth\AuthController@showRegistrationForm');
    Route::post('register', 'Auth\AuthController@register');

    Route::any('test/initTree', 'TempController@initTree');
    Route::any('test/tree', 'TempController@testTree');
    Route::any('test/gsb', 'TempController@globalSingletonBinding');
    Route::any('test/gdisb', 'TempController@globalDISingletonBinding');


    Route::get('/logout', function () {
        \Sentinel::logout(\Sentinel::getUser());

        return redirect('/');
    });

});
//Route::group(['middleware'=>'web'], function () {


Route::get('/phpinfo', 'TempController@phpinfo');
// Authentication Routes...
//Route::get('login', 'AccountController@login');

Route::get('/login', function () {
    return view('account.login');
});
Route::post('/login', 'AccountController@postLogin');


// Password Reset Routes...
$this->get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
$this->post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
$this->post('password/reset', 'Auth\PasswordController@reset');

//Route::get('/home', 'HomeController@index');

/**
 * For edu and test
 */



/*Route::group(['namespace'  => 'Users',
              'prefix'     => 'users',
              'middleware' => ['web']
], function () {

    Route::resource('transactions', 'TransactionController', ['except' => ['show']]);
    Route::post('transactions/search', 'TransactionController@search');

});*/