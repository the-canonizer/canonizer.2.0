<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpVerificationMail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Mail\PhoneOTPMail;
use Illuminate\Support\Facades\Session;
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
    public function validateLogin(Request $request)
    {
        $all= $request->all();
        $arr = [
            $this->username() => 'required|string'
        ];
        if(isset($all['password']) || !(isset($all['request_opt']) && $all['request_opt'] == 'on')){
            $arr['password']= 'required|string';
        }
       $msgs =  [
        'email.required' => 'The Email/Phone Number field is required.',
        'password.required' => 'The password is required.'
       ];
        $this->validate($request, $arr,$msgs);
    }
    public function request_otp($request,$arr){
        if($arr && isset($arr['email'])){
            $user = User::where('email','=',$arr['email'])->first();
            if(isset($user) && isset($user->email)){
                $authCode = mt_rand(100000, 999999);
                $user->otp = $authCode;
                $user->update();
                 Mail::to($user->email)->bcc(config('app.admin_bcc'))->send(new OtpVerificationMail($user,true));
                return redirect()->route('login.otp',['user'=>base64_encode($user->email)]);
            }else{
                 $userPhone = User::where('phone_number','=',$arr['email'])->first();
                if(isset($userPhone) && isset($userPhone->email)){
                    if($userPhone->mobile_verified != 1){
                        $errors = [$this->username() => 'User mobile number is not verified.'];
                        return redirect()->back()
                        ->withInput($request->only($this->username(), 'User mobile number is not verified.'))
                        ->withErrors($errors);    
                    }else{
                        $authCode = mt_rand(100000, 999999);
                        $userPhone->otp = $authCode;
                        $userPhone->update();
                        $result= [];
                        $result['otp'] = $authCode;
                        $result['subject'] = "Canonizer verification code";
                        $receiver = $userPhone->phone_number . "@" . $userPhone->mobile_carrier;
                        Session::flash('otpsent', "A 6 digit code has been sent on your phone number for verification.");
                        Mail::to($receiver)->bcc(config('app.admin_bcc'))->send(new PhoneOTPMail($userPhone, $result));
                        return redirect()->route('login.otp',['user'=>base64_encode($userPhone->email)]);
                    }
                }else{
                     $errors = [$this->username() => 'User does not exists.'];
                    return redirect()->back()
                ->withInput($request->only($this->username(), 'User does not exists.'))
                ->withErrors($errors);    
                }
                 
            }
        }else{
            $errors = [$this->username() => 'Email is required.'];
             return redirect()->back()
            ->withInput($request->only($this->username(), 'Email is required.'))
            ->withErrors($errors);
        }
    }
    public function getOtpForm(Request $request){
        $user = $request->get('user');
        return view('auth.loginotp', compact('user'));
    }
     public function login(Request $request)
     {
        $all = $request->all();
        $this->validateLogin($request);
        if(isset($all['request_opt']) && $all['request_opt'] == 'on'){
            return $this->request_otp($request,$all);
        }else{

            // If the class is using the ThrottlesLogins trait, we can automatically throttle
            // the login attempts for this application. We'll key this by the username and
            // the IP address of the client making these requests into this application.
            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);

                return $this->sendLockoutResponse($request);
            }

            if ($this->attemptLogin($request)) {
                return $this->sendLoginResponse($request);
            }

            // If the login attempt was unsuccessful we will increment the number of attempts
            // to login and redirect the user back to the login form. Of course, when this
            // user surpasses their maximum number of attempts they will get locked out.
            $this->incrementLoginAttempts($request);

            return $this->sendFailedLoginResponse($request); 
        }
        

    }

    public function validateLoginOtp(Request $request){
        $all = $request->all();
        $otp = $all['otp'];        
        $email = base64_decode($all['user']);
        $user = User::where('email', '=', $email)->first();
        $message = [
          'otp.required'=>'Please enter OTP'  
        ];
        $validator = Validator::make($all, [
                    'otp' => 'required',
        ],$message);

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator) // send back all errors to the login form
                            ->withInput();
        }

        if ($otp != $user->otp) {
            session()->flash('error', 'Incorrect OTP Entered');
            return redirect()->back();
        }
        $user->otp = '';
        $user->update();
        Auth::guard()->login($user);
        return redirect()->to('/home');
    }
}
