<?php

namespace Btcc\Http\Controllers;

use Btcc\Models\UsersTransaction;
use Illuminate\Http\Request;

use Btcc\Http\Requests;
use Illuminate\Support\Facades\Session;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //todo List transactions

        $transactions = UsersTransaction::with('recieverUser')->whereSender(\Auth::id())->get();

        return view('transaction.index')->with('transactions',$transactions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //dd(UsersTransaction::getTransactionTypes());

        return view('transaction.create',['transaction'=>new UsersTransaction()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->possibleTypes = (new UsersTransaction)->getPossibleTypes();
        
        $this->validate($request, [
            'reciever' => 'required|numeric|exists:users,id',
            //'type' => 'required|in_array:possibleTypes',
            'amount' => 'required|numeric',
        ]);

        $input = $request->all();

        $userTransaction = new UsersTransaction();
        $userTransaction->fill($input);
        $userTransaction->user_id = \Auth::id();
        $userTransaction->sender = \Auth::id();
        $userTransaction->debit_flag = true;
        $userTransaction->status  = 0;

        if ($userTransaction->save()) {
            Session::flash('flash_message', 'Transaction successfully added!');
            return redirect('transaction');

        };

        return redirect()->withErrors()->back();


        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('transaction.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('transaction.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
