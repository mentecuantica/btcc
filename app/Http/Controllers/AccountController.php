<?php

namespace Btcc\Http\Controllers;

use Btcc\Events\Event;
use Btcc\Events\ProfileWasUpdated;
use Btcc\Http\Requests;
use Btcc\Http\Requests\ProfileUpdateRequest;
use Btcc\Models\Profile;
use Btcc\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class AccountController extends Controller {

    /**
     * Show the application dashboard.
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $user = \Auth::getUser();



        return view('account.index', ['profile'=>$user->profile,'user'=>$user]);
    }

    public function profile(Request $request)
    {

        return view('account.profile', ['profile'=>$request->user()->profile,'user'=>$request->user()]);

    }

    public function profileUpdate(ProfileUpdateRequest $request)
    {

        $user = $request->user();

        /**@var  User $user **/;

        $user->fill($request->only(['first_name','last_name']));
        $user->profile->fill($request->only(['country_code','phone']));

        if ($user->update() && $user->profile->update()) {

            \Flash::success('Profile been updated');
            return redirect('/account');

        }


        \Flash::error('Some error occured');

        return back();

    }


  

}
