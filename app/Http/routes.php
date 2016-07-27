<?php


Route::get('login', 'Auth\AuthController@getLogin');
Route::post('login', 'Auth\AuthController@login');
Route::get('logout', 'Auth\AuthController@logout');



// Password Reset Routes...
Route::get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
Route::post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
Route::post('password/reset', 'Auth\PasswordController@reset');


Route::group(['middleware' => ['auth']], function () {

    Route::get('/dashboard', 'DashboardController@index');



    Route::get('/tree', function() {
         return view('tree.index',[]);

    });
    Route::get('tree/linear', 'Tree\LinearController@index');
    Route::get('tree/ternary', 'Tree\TernaryController@index');
    Route::get('tree/binary', 'Tree\BinaryController@index');
    Route::get('tree/binary/show/{id}', 'Tree\BinaryController@show');


    Route::get('transaction/refund', 'TransactionController@refund');
    Route::any('transaction/withdraw', 'TransactionController@withdraw');
    Route::any('transaction/transfer', 'TransactionController@transfer');
    Route::resource('transaction', 'TransactionController',['only'=>['index','show','store']]);


    Route::resource('partner', 'PartnerController');


    Route::get('account', 'AccountController@index');
    Route::get('account/password', 'Auth\PasswordController@actionChangePassword')->name('account.password');
    Route::post('account/password', 'Auth\PasswordController@changePassword')->name('account.password-change');
    Route::get('account/profile', 'AccountController@profile')->name('account.profile');
    Route::post('account/profile/update','AccountController@profileUpdate')->name('account.profile.update');


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





});

Route::get('/test2', function() {



});



/*Route::group(['namespace'  => 'Users',
              'prefix'     => 'users',
              'middleware' => ['web']
], function () {

    Route::resource('transactions', 'TransactionController', ['except' => ['show']]);
    Route::post('transactions/search', 'TransactionController@search');

});*/




