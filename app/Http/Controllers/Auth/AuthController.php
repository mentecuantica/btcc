<?php

namespace Btcc\Http\Controllers\Auth;
use Btcc\Events\UserRegisteredPartner;
use Btcc\Models\User;
use Btcc\Models\Profile;
use \Illuminate\Http\Request;

use Illuminate\Foundation\Auth\RegistersUsers;
use Btcc\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesUsers;


class AuthController extends Controller
{
    use AuthenticatesUsers, RegistersUsers {
        AuthenticatesUsers::redirectPath insteadof RegistersUsers;
        AuthenticatesUsers::getGuard insteadof RegistersUsers;
    }

    public $registerView = 'partner.create';


    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return \Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
           // 'password' => 'required|min:6|confirmed',
            'binary_position' => 'required|in:L,R',
           // 'package_id'=>'required|exists:packages',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @todo Make as single transaction
     * @todo Add statuses
     * 
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        \DB::beginTransaction();
        try {

            $newUser =  User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt('888888'),
                'binary_position' => $data['binary_position'],
            ]);

            $profile  =new Profile();
            $profile->name = 'Name';
            $profile->surname = 'Surname';


            $newUser->profile()->save($profile);

             $newUser->makeChildOf(\Auth::user());

            \DB::commit();

            \Debugbar::addMessage('New user plus profile, and child');
        }
        catch (\Exception $e) {


            \DB::rollBack();
            \Debugbar::addMessage('Nothing in the base!');
        }










    }


    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $newPartner = $this->create($request->all());
        if ($newPartner) {
            \Event::fire(new UserRegisteredPartner(\Auth::user(),$newPartner));

            \Session::flash('status', 'Partner successfully added!');
            return redirect(route('partner.index'));
        }



        return redirect($this->redirectPath());
    }

}
