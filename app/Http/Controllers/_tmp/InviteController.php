<?php

namespace Btcc\Http\Controllers;

use Btcc\Models\User;
use Btcc\Models\Invite;
use Illuminate\Http\Request;

use Btcc\Http\Requests;

class InviteController extends Controller
{
    public function index()
    {

        $invite = null;
        /*       if ($session->has('new')) {
                   $invite = $session->get('new');
               }*/

        $invites = Invite::all()->where('user_id',\Auth::id());


        return view('invite.list',compact('invites','invite'));

    }

    public function create()
    {
        $user = \Auth::user();

        $newInvite = new Invite();
        return view('invite.index',compact('user','newInvite'));
    }

    public function store(Requests\UserInviteCreateRequest $request)
    {

       $user = \Auth::user();

        $newInvite = new Invite();
        $newInvite->user_id = $user->id;
        $newInvite->package_id = $request->package_id;
        $newInvite->type = 0;
        $newInvite->status = Invite::NEW;
        $newInvite->email = $request->email;

        if ($newInvite->save()) {
            return redirect('/invite/list')->with('new',$newInvite);
        }

        return json_encode($newInvite);

        //$newUser = new User();
        //return view('invite.index',compact('user','newUser'));
    }


    public function list(\Session $session) {


    }
}
