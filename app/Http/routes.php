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

    $email = 'test@repo.ru';

    $oldUser = \Btcc\Models\User::with('linear')->where('email', '=', $email)->first();

    if ($oldUser) {
        /**@var User $oldUser * */
        $oldUser->linear->delete();
        $oldUser->profile->delete();
        $oldUser->delete();
    }


    $user = ['email'    => 'test@repo.ru',
             'password' => '123456'
    ];
    $package_id = \Btcc\Models\Package::all('id')->random()->id;

    $profile = [
        'phone'=>'9222222222',
        'package_id' => $package_id,
    ];

    $binary = [
      'parent_id'=>4,
      'position'=>'R'
    ];

    \Btcc\Repositories\UserRepository::createNewUserBundle(1, $user, $profile,$binary);

    return view('users.show', []);
});

Route::get('/', function () {
    return view('landing.index');
});

Route::group(['middleware' => ['sent.auth']], function () {
    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/account', '\Btcc\Http\Controllers\AccountController@index');

    Route::get('/tree', '\Btcc\Http\Controllers\TreeController@index');
    Route::get('/tree/linear', '\Btcc\Http\Controllers\TreeController@showLinear');
    Route::get('/tree/binary/{id}', 'TreeController@showBinary');
    Route::get('/tree/ternary', '\Btcc\Http\Controllers\TreeController@showTernary');
    Route::get('/tree/binaryJson', 'TreeController@binaryTree');
    Route::get('/tree/show/{id}', 'TreeController@show');

    Route::resource('transaction', 'TransactionController');
    Route::resource('partner', 'PartnerController');

    Route::post('profile/update', ['as'   => 'profile.update',
                                   'uses' => 'AccountController@profileUpdate'
    ]);

    Route::get('/invite', 'InviteController@index');
    Route::post('/invite/create', 'InviteController@create');
    Route::get('/invite/list', 'InviteController@list');

    // Registration Routes...
    Route::get('register', 'Auth\AuthController@showRegistrationForm');
    Route::post('register', 'Auth\AuthController@register');

    Route::any('test/initTree', 'TempController@initTree');
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

// Instead, you could have a handy list of patterns and reuse them everywhere:
// Patterns
Route::pattern('id', '\d+');
Route::pattern('hash', '[a-z0-9]+');
Route::pattern('hex', '[a-f0-9]+');
Route::pattern('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
Route::pattern('base', '[a-zA-Z0-9]+');
Route::pattern('slug', '[a-z0-9-]+');
Route::pattern('username', '[a-z0-9_-]{3,16}');

Route::group(['namespace'  => 'Users',
              'prefix'     => 'users',
              'middleware' => ['web']
], function () {

    /*
    |--------------------------------------------------------------------------
    | Transaction Routes
    |--------------------------------------------------------------------------
    */

    Route::resource('transactions', 'TransactionController', ['except' => ['show']]);
    Route::post('transactions/search', 'TransactionController@search');

});