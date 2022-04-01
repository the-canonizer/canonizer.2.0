<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Mail\PhoneOTPMail;
use Illuminate\Support\Str;
use App\Model\ResetPassword;
use Illuminate\Http\Request;
use App\Mail\PasswordResetMail;
use App\Mail\OtpVerificationMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Password Reset Controller
      |--------------------------------------------------------------------------
      |
      | This controller is responsible for handling password reset emails and
      | includes a trait which assists in sending these notifications from
      | your application to your users. Feel free to explore this trait.
      |
     */

    //use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
    }

    public function showLinkRequestForm() {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request) {
        $validator = $this->validateEmail($request);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }
        $user = $this->emailExist($request);
        if (!$user) {
            return back()->withErrors(['email' => 'Email not found in our record']);
        }

        if(!isset($user->status) || $user->status === 0) {
            $verify_link = url('getVerificationCode');
            $email = $request->get('email',null);
            if(!empty($email)){
                $verify_link .= '?user='.base64_encode($email);
            }
            $authCode = mt_rand(100000, 999999);
            $user->otp = $authCode;
            $user->update();
            Session::flash('otpsent', "One Time Verification Code has been sent to your registered email address.");
            try{
                Mail::to($user->email)->send(new OtpVerificationMail($user,true));
                return redirect($verify_link);
            }catch(\Swift_TransportException $e){
                throw new \Swift_TransportException($e);
            }

            return back()->with(['forgot_password_error' => 'Your account is not verified yet. Click on the link <span ><a href="'.url('getVerificationCode').'" class="verification-link">Request for Verification Code</a></span> to get a new code on you registered email or mobile.']);
        }

        // send resetlink in email
        $token = Uuid::uuid4();
        ResetPassword::create([
            "id" => $token,
            "user_id" => $user->id,
            "expires_at" => Carbon::now()->addDays(2)
        ]);
        $link = 'resetpassword/' . $token;
        try{
            Mail::to($user->email)->send(new PasswordResetMail($user,$link));
        }catch(\Swift_TransportException $e){
            throw new \Swift_TransportException($e);
        } 
        
        return redirect('resetlinksent');
    }

    protected function validateEmail(Request $request) {
        return Validator::make($request->all(), [
                    'email' => 'required|email'
        ]);
        // $this->validate($request, ['email' => 'required|email']);
    }

    protected function emailExist(Request $request) {
        $email = $request->get('email');
        $user = User::where('email', $email)->first();
        return !empty($user) ? $user : false;
    }

    public function resetLinkSent() {
        return view('auth.passwords.resetlinksent');
    }

    public function showVerificationCodeForm(Request $request) {
        $email = $request->get('user',null);
        if(!empty($email)){
            $email = base64_decode($email);
            $tempUser = User::where('email', $email)->first();
            if(!empty($tempUser)){
                try{
                    $user = base64_encode($tempUser->email);
                    return view('auth.verifyotp', compact('user'));
                }catch(\Swift_TransportException $e){
                    throw new \Swift_TransportException($e);
                }
            }
        }
        return view('auth.reqVerificationCode');
    }

    public function getVerificationCode(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }
        $data = $request->all();
        
        if(isset($data['email'])) {
            $tempUser = User::where('email', $data['email'])->first();
            if(isset($tempUser)) {
                $authCode = mt_rand(100000, 999999);
                $tempUser->otp = $authCode;
                $tempUser->update();
                try{
                    Mail::to($tempUser->email)->bcc(config('app.admin_bcc'))->send(new OtpVerificationMail($tempUser,true));
                    $user = base64_encode($tempUser->email);
                    return view('auth.verifyotp', compact('user'));
                }catch(\Swift_TransportException $e){
                    throw new \Swift_TransportException($e);
                } 
            } else {
                $tempUser = User::where('phone_number', $data['email'])->first();
                if(isset($tempUser)) {
                    if($tempUser->mobile_verified === 0) {
                        return back()->with(['verification_code_error' => 'Your mobile number is not verified.']);
                    }
                    $authCode = mt_rand(100000, 999999);
                    $tempUser->otp = $authCode;
                    $tempUser->update();
                    $result= [];
                    $result['otp'] = $authCode;
                    $result['subject'] = "Canonizer verification code";
                    $receiver = $tempUser->phone_number . "@" . $tempUser->mobile_carrier;
                    Session::flash('otpsent', "A 6 digit code has been sent on your phone number for verification.");
                    try{
                        Mail::to($receiver)->bcc(config('app.admin_bcc'))->send(new PhoneOTPMail($tempUser, $result));
                        $user = base64_encode($tempUser->email);
                        return view('auth.verifyotp', compact('user'));
                    }catch(\Swift_TransportException $e){
                        throw new \Swift_TransportException($e);
                    } 
                } else {
                    return redirect()->back()->withErrors(['email' => 'Email/Phone Number does not exists in our record']);
                }
            }            
        } else {
            $errors = [$this->username() => 'Email is required.'];
            return redirect()->back()
            ->withInput($request->only($this->username(), 'Email is required.'))
            ->withErrors($errors);
        }
    }
}
