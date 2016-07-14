<?php

namespace Btcc\Http\Controllers;

use Btcc\Models\Transaction\Transactable;
use Btcc\Models\Transaction\UserTransaction;
use Btcc\Models\User;
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


        //$transactions = UserTransaction::with('reciever')->whereSenderId(\Auth::id())->get();
        $transactions = user()->with('transactions')->find(1)->transactions;



        return view('transaction.index')->with('transactions',$transactions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function refund()
    {
        $t =  new UserTransaction();

        $t->type = Transactable::TYPE_CASHIN_FUNDING;
        $t->status = Transactable::STATUS_NEW;


        return view('transaction.refund',['transaction'=>new UserTransaction()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateTransactionRequest $request)
    {
        //$request->possibleTypes = (new UserTransaction)->getPossibleTypes();
        

        $input = $request->all();

        $t = new UserTransaction();
        $t->fill($input);
        $t->user_id = \Auth::id();
        $t->sender_id = \Auth::id();
        $t->reciever_id = \Auth::id();
        $t->debit_flag = true;
        $t->credit_flag = false;
        $t->type = Transactable::TYPE_CASHIN_FUNDING;
        $t->status = Transactable::STATUS_NEW;

        if ($t->save()) {
            \Flash::success('Transaction successfully added!');
            return redirect('transaction');

        };

        return back()->withErrors();


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
        return view('transaction.edit');
    }


}
