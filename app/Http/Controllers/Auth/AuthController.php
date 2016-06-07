<?php

namespace Btcc\Http\Controllers\Auth;

use Btcc\Events\UserRegisteredPartner;
use Btcc\Models\User;
use Btcc\Models\Profile;
use \Illuminate\Http\Request;

use Illuminate\Foundation\Auth\RegistersUsers;
use Btcc\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;

class AuthController extends Controller {

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

    //use ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     * @var string
     */
    protected $redirectTo = '/';



    /**
     * Create a new user instance afte
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     * @param  array $data
     *
     * @return User
     */
    protected function create(array $data)
    {
        \DB::beginTransaction();
        try {

            $newUser = User::create([
                'name'            => $data['name'],
                'email'           => $data['email'],
                'password'        => bcrypt('123456'),

            ]);

            $profile = new Profile();
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
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }

        $newPartner = $this->create($request->all());
        if ($newPartner) {
            \Event::fire(new UserRegisteredPartner(\Auth::user(), $newPartner));

            \Session::flash('status', 'Partner successfully added!');

            return redirect(route('partner.index'));
        }

        return redirect($this->redirectPath());
    }

    /**
     * Show the application login form.
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        return $this->showLoginForm();
    }

    /**
     * Show the application login form.
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $view = property_exists($this, 'loginView') ?
            $this->loginView :
            'auth.authenticate';

        if (view()->exists($view)) {
            return view($view);
        }

        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        return $this->login($request);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $lockedOut = $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->getCredentials($request);

        if (\Auth::guard($this->getGuard())->attempt($credentials, $request->has('remember'))) {
            return $this->handleUserWasAuthenticated($request, $throttles);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles && !$lockedOut) {
            $this->incrementLoginAttempts($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->loginUsername() => 'required',
            'password'             => 'required',
        ]);
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  bool                     $throttles
     *
     * @return \Illuminate\Http\Response
     */
    protected function handleUserWasAuthenticated(Request $request, $throttles)
    {
        if ($throttles) {
            $this->clearLoginAttempts($request);
        }

        if (method_exists($this, 'authenticated')) {
            return $this->authenticated($request, Auth::guard($this->getGuard())->user());
        }

        return redirect()->intended($this->redirectPath());
    }

    /**
     * Get the failed login response instance.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->back()->withInput($request->only($this->loginUsername(), 'remember'))->withErrors([
                $this->loginUsername() => $this->getFailedLoginMessage(),
            ]);
    }

    /**
     * Get the failed login message.
     * @return string
     */
    protected function getFailedLoginMessage()
    {
        return \Lang::has('auth.failed') ?
            \Lang::get('auth.failed') :
            'These credentials do not match our records.';
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function getCredentials(Request $request)
    {
        return $request->only($this->loginUsername(), 'password');
    }

    /**
     * Log the user out of the application.
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        return $this->logout();
    }

    /**
     * Log the user out of the application.
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::guard($this->getGuard())->logout();

        return redirect(property_exists($this, 'redirectAfterLogout') ?
            $this->redirectAfterLogout :
            '/');
    }

    /**
     * Get the guest middleware for the application.
     */
    public function guestMiddleware()
    {
        $guard = $this->getGuard();

        return $guard ?
            'guest:' . $guard :
            'guest';
    }

    /**
     * Get the login username to be used by the controller.
     * @return string
     */
    public function loginUsername()
    {
        return property_exists($this, 'username') ?
            $this->username :
            'email';
    }

    /**
     * Determine if the class is using the ThrottlesLogins trait.
     * @return bool
     */
    protected function isUsingThrottlesLoginsTrait()
    {
        return in_array(ThrottlesLogins::class, class_uses_recursive(static::class));
    }

    /**
     * Get the guard to be used during authentication.
     * @return string|null
     */
    protected function getGuard()
    {
        return property_exists($this, 'guard') ?
            $this->guard :
            NULL;
    }
}
