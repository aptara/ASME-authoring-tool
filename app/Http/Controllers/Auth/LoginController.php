<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/chapters';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(\Illuminate\Http\Request $request) 
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) 
        {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        
        if ($this->guard()->validate($this->credentials($request))) 
        {
            $user = $this->guard()->getLastAttempted();

            if ($user->active_status && $this->attemptLogin($request)) 
            {
                return $this->sendLoginResponse($request);
            }
            else 
            {
                $this->incrementLoginAttempts($request);
                return redirect()
                    ->back()
                    ->withInput($request->only($this->username(), 'remember'))
                    ->withErrors(['email' => 'User deactivated']);
            }
        }
    
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}
