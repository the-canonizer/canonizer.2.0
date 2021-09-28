<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use App\Mail\OtpVerificationMail;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    //use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $message = [
            'password.regex'=>'Password must be atleast 8 characters, including atleast one digit, one lower case letter and one special character(@,# !,$..).',
            'first_name.regex' => 'The first name must be in alphabets and space only.',
            'first_name.required' => 'The first name field is required.',
            'first_name.max' => 'The first name can not be more than 100.',
            'middle_name.regex' => 'The middle name must be in alphabets and space only.',
            'middle_name.max' => 'The middle name can not be more than 100.',
            'last_name.regex' => 'The last name must be in alphabets and space only.',
            'last_name.required' => 'The last name field is required.',
            'last_name.max' => 'The last name can not be more than 100.',
        ];
        return Validator::make($data, [
            'first_name' => 'required|regex:/^[a-zA-Z ]*$/|string|max:100',
            'last_name' => 'required|regex:/^[a-zA-Z ]*$/|string|max:100',
			'middle_name' => 'nullable|regex:/^[a-zA-Z ]*$/|max:100',
            'email' => 'required|string|email|max:255|unique:person',
            'CaptchaCode' => 'required|valid_captcha',
            'password' => ['required','regex:/^(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^\w\s]).{8,}$/','confirmed'],
        ],$message);
    }
    
    
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        
        $this->validator($request->all())->validate();

        return $this->create($request->all());

        //$this->guard()->login($user);

//        return $this->registered($request, $user)
//                        ?: redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
      
         //Send 6 digit code & move to next step
        $authCode = mt_rand(100000, 999999);
        
        $user = User::create([
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'first_name' =>$data['first_name'],
            'last_name' =>$data['last_name'],
            'middle_name' =>$data['middle_name'],
            'otp'=>$authCode
        ]);
        
        //otp email
        try{
            
           Mail::to($user->email)->bcc(config('app.admin_bcc'))->send(new OtpVerificationMail($user));
          //return view('auth.verifyotp')->with('user', base64_encode($user->email));
           return redirect()->route('register.otp', ['user' => base64_encode($user->email)]);
          //return redirect()->to('register/verify-otp')->withInput(['user', base64_encode($user->email)]);
        }catch(\Swift_TransportException $e){
            throw new \Swift_TransportException($e);
        } 
        
    }
    
    public function getOtpForm(Request $request){
        $user = $request->get('user');
        return view('auth.verifyotp', compact('user'));
    }
    
    
    public function postVerifyOtp(Request $request){
        
        $all = $request->all();
        $email = base64_decode($all['user']);
        $otp = $all['otp'];
        $user = User::where('email', '=', $email)->first();
        $message = [
          'otp.required'=>'Please enter One Time Verification Code'  
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
            session()->flash('error', ' Incorrect One Time Verification Code Entered');
            return redirect()->back();
        }
        //auth response date
        $user->status = 1;
        $user->otp='';
        $user->update();
         session()->forget('social_user');
         session()->forget('provider');
        // send help link in email
        $link = 'topic/132-Help/1-Agreement';	
        try{
            
        Mail::to($user->email)->bcc(config('app.admin_bcc'))->send(new WelcomeMail($user,$link));
        
        Auth::guard()->login($user);
        return redirect()->to('/home');
        
        }catch(\Swift_TransportException $e){
                            throw new \Swift_TransportException($e);
                        }    
        //return $user;
    }
}
