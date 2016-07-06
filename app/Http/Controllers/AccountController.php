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

    public function profileUpdate(ProfileUpdateRequest $request)
    {
        $user_id = \Auth::user()->id;
        $profile = \Auth::user()->profile;
        if ($profile == NULL) {
            $profile = new Profile;
            $profile->user_id = $user_id;
            $profile->fill($request->all());

            if (\Auth::user()->profile()->save($profile) !== FALSE) {
                event(new ProfileWasUpdated($profile, TRUE));
            }

        }
        else {

            if ($profile->update($request->all())) {
                event(new ProfileWasUpdated($profile));

                return redirect('/account')->with(['message' => 'Success']);
            }

        }

    }

    public function login()
    {
    
    }

  

    public function postLogin(Request $request)
    {
        $credentials = $request->only([
            'email',
            'password'
        ]);

        $loginValidator = \Validator::make($credentials, [
            'password' => 'required|max:255',
            'email'    => 'required|email|max:255',
        ]);

        if ($loginValidator->valid()) {
            if (\Auth::authenticate($credentials)) {
                return redirect('/dashboard')->with(['message'=>'Welcome']);

            }

        }
        return back()->withErrors($loginValidator)->withInput(['email']);


    }
}
