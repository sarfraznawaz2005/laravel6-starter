<?php

namespace Modules\Admin\Http\Actions\Admin;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Sarfraznawaz2005\Actions\Action;

class LoginAdmin extends Action
{
    use AuthenticatesUsers;

    protected $redirectTo = 'admin/panel';

    /**
     * Perform the action.
     *
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __invoke(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->credentials($request);

        // also check for "admin" status
        $credentials['is_admin'] = 1;

        // also check if user is active
        $credentials['is_active'] = 1;

        if ($this->guard()->attempt($credentials, $request->has('remember'))) {

            // success
            noty('Welcome ' . user()->full_name);

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}
