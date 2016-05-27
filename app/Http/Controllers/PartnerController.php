<?php

namespace Btcc\Http\Controllers;

use Btcc\Models\User;
use Illuminate\Http\Request;

use Btcc\Http\Requests;
use Illuminate\Support\Facades\Session;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //todo List transactions



        //$partners = \Auth::user()->subPartners(5);
        $partners = \Auth::user()->directPartners();




      return view('partner.index',compact('partners'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
    public function show(User $partner)
    {

        return $partner;
       // return view('partner.create');
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
