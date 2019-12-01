<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

/**
 * [showLoginForm description]
 * To override the showLoginForm from the Laravel Framework
 * to redirect the URL to the previous intended URL
 *
 * @return [type] [description]
 */
    public function showLoginForm()
    {
        if(!session()->has('url.intended'))
        {
            session(['url.intended' => url()->previous()]);
        }

    return view('auth.login');
    }

     public function logout(Request $request)
        {
            $this->guard()->logout();

            $request->session()->invalidate();
            if($request->query('from') == 'admin'){
                    return redirect('/admin');
            }
            return redirect('/');
        }
}
