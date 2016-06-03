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
    //if (Auth::guest()) {
        return view('landing.index');
  //  }

   // return view('dashboard.index');
});

Route::group(['middleware'=>'auth.next'], function() {

    Route::any('/next', function(Request $request) {
        return $request;
    });
});
//Route::group(['middleware'=>'web'], function () {

    Route::get('users/{user}', function (Btcc\Models\User $user) {
        return $user;
    });

    Route::get('/account', '\Btcc\Http\Controllers\AccountController@index');

    Route::get('/tree', '\Btcc\Http\Controllers\TreeController@index');
    Route::get('/tree/linear', '\Btcc\Http\Controllers\TreeController@showLinear');
    Route::get('/tree/binary', '\Btcc\Http\Controllers\TreeController@showBinary');
    Route::get('/tree/ternary', '\Btcc\Http\Controllers\TreeController@showTernary');
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



    Route::any('test/initTree', 'TempController@initTree');
    Route::any('test/gsb', 'TempController@globalSingletonBinding');
    Route::any('test/gdisb', 'TempController@globalDISingletonBinding');
//});




// Authentication Routes...
//Route::get('login', 'AccountController@login');




Route::get('/login', function() {
    return view('account.login');
});
Route::post('/login', 'AccountController@postLogin');
Route::get('/logout',function() {
    \Sentinel::logout(\Sentinel::getUser());

    return redirect('/');
});



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