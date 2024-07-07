<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/panel';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'phone';
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
            'captcha_code' => 'required|captcha',
        ], [
            'captcha_code.captcha' => 'کد امنیتی وارد شده صحیح نیست'
        ]);
    }

    public function showLoginForm()
    {
        $role = \request()->role;
        return view('auth.login', compact('role'));
    }

    protected function attemptLogin(Request $request)
    {
        $attemp = $this->guard()->attempt(
            $this->credentials($request), $request->boolean('remember')
        );

        if ($attemp) {
            $user = $this->guard()->user();

            if ($user->role->name == $request->role || ($user->role->name == 'admin' && ($request->role == 'ceo' || $request->role == null))) {
                return $attemp;
            } else {
                $this->guard()->logout();

                $request->validate([
                    'notAllow' => 'required'
                ], [
                    'notAllow.required' => 'شما به این بخش دسترسی ندارید!'
                ]);

                return false;
            }
        }

        return $attemp;
    }
}
