<?php


Route::get('login', 'Auth\AuthController@showLoginForm');
Route::post('login', 'Auth\AuthController@login');
Route::get('logout', 'Auth\AuthController@logout');



// Password Reset Routes...
Route::get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
Route::post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
Route::post('password/reset', 'Auth\PasswordController@reset');


Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/account', 'AccountController@index');

    Route::get('/tree', function() {
         return view('tree.index',[]);

    });
    Route::get('/tree/linear/index', 'Tree\LinearController@index');
    Route::get('/tree/ternary/index', 'Tree\TernaryController@index');
    Route::get('/tree/binary/index', 'Tree\BinaryController@index');
    Route::get('/tree/binary/show/{id}', 'Tree\BinaryController@show');


   // Route::get('transactions', 'TransactionController@index');
    Route::get('transaction/refund', 'TransactionController@refund');
    Route::any('transaction/withdraw', 'TransactionController@withdraw');
    Route::any('transaction/transfer', 'TransactionController@transfer');
    Route::resource('transaction', 'TransactionController',['only'=>['index','show','store']]);

    Route::resource('partner', 'PartnerController');

    Route::get('account/profile', ['as'   => 'acoount.profile',
                                   'uses' => 'AccountController@profile'
    ]);


    Route::post('account/profile/update', ['as'   => 'acoount.profile.update',
                                   'uses' => 'AccountController@profileUpdate'
    ]);

    Route::resource('supportTicket', 'SupportController',['only'=>['index','create','store']]);
    Route::get('/faq', 'PagesController@faq');








});
//Route::group(['middleware'=>'web'], function () {
Route::get('/', function () {

    if (\Auth::guest()) {
        return view('landing.index');
    }
    else {
        return redirect('/dashboard');
    }
});

Route::get('/phpinfo', function() {
    phpinfo();
});


Route::get('/test', function() {

    return \Btcc\Models\Transaction\UserTransaction::getSummary(1);


});



/*Route::group(['namespace'  => 'Users',
              'prefix'     => 'users',
              'middleware' => ['web']
], function () {

    Route::resource('transactions', 'TransactionController', ['except' => ['show']]);
    Route::post('transactions/search', 'TransactionController@search');

});*/




