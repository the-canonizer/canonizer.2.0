<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Validator;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;
use Illuminate\Http\RedirectResponse;

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

        // send resetlink in email
        $link = 'resetpassword/' . base64_encode($user->email);
        Mail::to($user->email)->send(new PasswordResetMail($user,$link));
        
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

}
