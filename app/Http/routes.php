<?php


Route::auth();

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
    Route::resource('transaction', 'TransactionController',['only'=>['index','show','store']]);

    Route::any('transaction/withdraw', 'TransactionController@withdraw');
    Route::any('transaction/transfer', 'TransactionController@transfer');

    Route::resource('partner', 'PartnerController');

    Route::post('profile/update', ['as'   => 'profile.update',
                                   'uses' => 'AccountController@profileUpdate'
    ]);

    Route::resource('supportTicket', 'SupportController',['only'=>['index','create','store']]);






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

Route::get('/ququ',function(\Btcc\Repositories\TreeRepository $repository) {

//    $result = DB::table('tree_ternary')->pluck('path_to_root');

  //  $result = DB::query()->select('child_id',DB::raw('parent_id FROM bt_get_descendants(:id)'))->addSelect('pp')->toSql(); // (['parent_id, child_id, bt_position, depth, level'],)

   // dump(DB::query()->selectRaw(':id,:bd',[':id'=>1,':bd'=>1])->getBindings());

    /**
     * @param int $parentId
     * @param int $depth
     *
     * @return \Illuminate\Database\Query\Builder
     */




        /**@var \Illuminate\Database\Query\Builder $builder **/

        //return $builder;

    //};

    $fnc = sprintf('bt_get_ancestors(%d) as t',1);
    //dd(DB::query()->addSelect(['t.ss','t.bt_position'])->from(DB::raw($fnc))->toSql());

    dump( $repository->binaryChildren(1,10)->joinUsers()->get());


    $result = $repository->binaryChildren(4);
    //dump(DB::query()->addSelect('child_id')->select('parent_id')->selectRaw('FROM bt_get_descendants(:id) as bd',[':id'=>1])->toSql());
    //dump(DB::query()->addSelect('child_id')->select('parent_id')->selectRaw('FROM bt_get_descendants(:id) as bd',[':id'=>1])->toSql());
    //dump(DB::query()->selectSub('SELECT child_id, parent_id FROM bt_get_descendants(:id)','d')->addSelect(['d.child_id'])->toSql());


    //dd($result);
    //$result =  collect($result);
    //dd($result);
    return view('blanco',compact('result'));
});


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




