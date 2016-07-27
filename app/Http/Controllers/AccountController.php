<?php

namespace Btcc\Http\Controllers;

use Btcc\Events\ProfileWasUpdated;
use Btcc\Http\Requests\ProfileUpdateRequest;
use Btcc\Models\Profile;
use Btcc\Models\User;
use Btcc\Utilities\ProfileInfo;
use Illuminate\Http\Request;

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
        $profile = $request->user()->profile;
        $user = $request->user();

        if ($profile->additional_info == NULL) {
            $profile->additional_info = new ProfileInfo();
        }


        return view('account.profile', compact('profile','user'));

    }

    public function profileUpdate(ProfileUpdateRequest $request)
    {

        $user = $request->user();

        /**@var  User $user **/;

        $data = $request->get('additional_info');


        $v = \Validator::make($data, ProfileInfo::getValidationRules());

        if ($v->fails()) {
            $this->throwValidationException($request, $v);

        }



        $user->fill($request->only(['first_name','last_name']));
        $user->profile->fill($request->only(['country_code','phone','additional_info']));
        if ($user->update() && $user->profile->update()) {

            \Flash::success('Profile been updated');
            return redirect('/account');

        }


        \Flash::error('Some error occured');

        return back();

    }


  

}
