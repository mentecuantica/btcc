<?php




Route::group(['middleware' => ['auth']], function () {
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




    Route::any('test', 'TempController@index');




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

Route::get('/phpinfo', 'TempController@phpinfo');



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
Route::auth();

Route::get('/home', 'HomeController@index');
