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

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $user = \Auth::getUser();

        $profile = $user->profile;
        if ($profile==NULL) {
            $profile = new Profile();

        }



        return view('account.index',compact('user','profile'));
    }

    public function profileUpdate(ProfileUpdateRequest $request)
    {
        $user_id = \Auth::user()->id;
        $profile = \Auth::user()->profile;
        if ($profile==NULL) {
            $profile = new Profile;
            $profile->user_id = $user_id;
            $profile->fill($request->all());

            if (\Auth::user()->profile()->save($profile)!==FALSE) {
                event(new ProfileWasUpdated($profile,true));
            }

        }
        else {
    
            if ($profile->update($request->all())) {
                event(new ProfileWasUpdated($profile));
                return redirect('/account')->with(['message'=>'Success']);
            }
            

        }

            
    }
}
