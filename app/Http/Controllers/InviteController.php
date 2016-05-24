<?php

namespace Btcc\Http\Controllers;

use Btcc\Models\User;
use Illuminate\Http\Request;

use Btcc\Http\Requests;

class InviteController extends Controller
{
    public function index()
    {
       $user = \Auth::user();

        $newUser = new User();
        return view('invite.index',compact('user','newUser'));
    }

    public function create()
    {
       $user = \Auth::user();

        $newUser = new User();
        return view('invite.index',compact('user','newUser'));
    }
}
