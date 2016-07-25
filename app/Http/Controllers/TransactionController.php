<?php

namespace Btcc\Http\Controllers;

use Btcc\Models\Transaction\Transactable;
use Btcc\Models\Transaction\UserTransaction;
use Btcc\Models\User;
use Illuminate\Http\Request;

use Btcc\Http\Requests;
use Illuminate\Support\Facades\Session;
use Mockery\CountValidator\Exception;

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
        $transactions = User::with('transactions')->find(user()->id)->transactions;



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

    public function withdraw(Request $request)
    {
        $t =  new UserTransaction();

        $t->type = Transactable::TYPE_CASHOUT_WITHDRAW;
        $t->status = Transactable::STATUS_NEW;

        if ($request->getMethod()=='POST') {
            return $this->withdrawProcess($request);
        }


        return view('transaction.withdraw',['transaction'=>$t]);
    }

    protected function withdrawProcess(Request $request)
    {
        $rules = [
            'wallet'=>'required|bitcoinAddress',
            'amount'=>'required|hasEnoughFunds'
        ];

        $this->validate($request, $rules);


        $input = $request->all();

        $t = new UserTransaction();
        $t->fill($input);
        $t->user_id = \Auth::id();
        $t->sender_id = \Auth::id();
        $t->reciever_id = \Auth::id();
        $t->debit_flag = FALSE;
        $t->credit_flag = true;
        $t->type = Transactable::TYPE_CASHOUT_WITHDRAW;
        $t->status = Transactable::STATUS_NEW;
        $t->comment = $input['wallet'];


        if ($t->save()) {
            \Flash::success('Funds have been sent to: '.$input['wallet']);
            return redirect('transaction');
        };


        return redirect()->back();
    }


    public function transfer(Request $request)
    {
        $t =  new UserTransaction();

        $t->type = Transactable::TYPE_CASHOUT_WITHDRAW;
        $t->status = Transactable::STATUS_NEW;

        if ($request->getMethod()=='POST') {
            return $this->transferProcess($request);
        }


        return view('transaction.transfer',['transaction'=>$t]);
    }


    protected function transferProcess(Request $request)
    {
        $rules = [
            'email'=>'required|exists:users,email',
            'amount'=>'required|hasEnoughFunds'
        ];

        $this->validate($request, $rules);

        $input = $request->all();

        $receiverUser = User::whereEmail($input['email'])->first();

        //dd($receiverUser);

        if (!$receiverUser) {
            \Flash::error('Reciever user not found');
            return redirect()->back();
        }

        \DB::beginTransaction();

        try {
            // CREDIT TRANSACTION

            $tCredit = new UserTransaction();
            $tCredit->fill($input);
            $tCredit->user_id = \Auth::id();
            $tCredit->sender_id = \Auth::id();
            $tCredit->reciever_id = $receiverUser->id;
            $tCredit->debit_flag = FALSE;
            $tCredit->credit_flag = true;
            $tCredit->type = Transactable::TYPE_CASHOUT_WITHDRAW;
            $tCredit->status = Transactable::STATUS_NEW;
            $tCredit->comment = $input['email'];


            // DEBIT TRANSACTION
            $tDebit = new UserTransaction();
            $tDebit->fill($input);
            $tDebit->user_id = $receiverUser->id;
            $tDebit->sender_id = \Auth::id();
            $tDebit->reciever_id = $receiverUser->id;
            $tDebit->debit_flag = true;
            $tDebit->credit_flag = false;
            $tDebit->type = Transactable::TYPE_CASHIN_FUNDING;
            $tDebit->status = Transactable::STATUS_NEW;

            $saveBoth = $tCredit->save() && $tDebit->save();

            \DB::commit();

            \Flash::success('Funds have been sent to: '.$input['email']);
            return redirect('transaction');

        }
        catch (Exception $e) {
            \DB::rollBack();

            \Flash::error('Error while transasctional saving ');
        }







        return redirect()->back();

    }

}
