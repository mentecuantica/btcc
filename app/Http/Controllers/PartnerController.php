<?php

namespace Btcc\Http\Controllers;

use Btcc\Events\Event;
use Btcc\Events\UserRegisteredPartner;
use Btcc\Models\Tree\TreeBinary;
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
        $user = \Sentinel::getUser();

        /**@var User $user **/


        // get an nested collection of LinearTree with User inside
        $linearTree = $user->getLinearTree();






      return view('partner.index',['partners'=>[]]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $userId = \Sentinel::getUser()->id;




        list($parent, $jsonNodes) = TreeBinary::generateJsonBinary(0);
        //dd($userId,$jsonNodes,$parent);
        return view('partner.create',compact('parent','jsonNodes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       dd($request);

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
