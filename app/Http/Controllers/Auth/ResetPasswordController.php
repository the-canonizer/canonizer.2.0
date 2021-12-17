<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Model\ResetPassword;
use Illuminate\Http\Request;
use App\Mail\RecoverAccountMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\RedirectsUsers;

class ResetPasswordController extends Controller {

    use RedirectsUsers;
    /*
      |--------------------------------------------------------------------------
      | Password Reset Controller
      |--------------------------------------------------------------------------
      |
      | This controller is responsible for handling password reset requests
      | and uses a simple trait to include this behavior. You're free to
      | explore this trait and override any methods you wish to tweak.
      |
     */

    //use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
    }

    public function showResetForm(Request $request, $token = null) {
        if (empty($token)) {
            return view('auth.passwords.resetlinkinvalid')->with(['error' => 'Your password reset link is not valid']);
        }

        $checkToken = ResetPassword::where("id", $token)->first();
        if (empty($checkToken)) {
            return view('auth.passwords.resetlinkinvalid')->with(['error' => 'Your password reset link is not valid, or already used']);
        }

        if (!empty($checkToken->reset_at)) {
            return view('auth.passwords.resetlinkinvalid')->with(['error' => 'Your password reset link has already been used']);
        }

        $isExpired = Carbon::now()->gt(Carbon::parse($checkToken->expires_at));

        if ($isExpired) {
            return view('auth.passwords.resetlinkinvalid')->with(['error' => 'Your password reset link has expired']);
        }

        return view('auth.passwords.reset')->with(
                        ['token' => $token]
        );
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request) {

        $this->validate($request, $this->rules(), $this->validationErrorMessages());

        $token = $request->get('reset_token');
        $password = $request->get('password');
        if (empty($token)) {
            return back()->with(['forgot_password_error' => 'Your password reset link is not valid']);
        }

        $checkToken = ResetPassword::where("id", $token)->first();
        if (empty($checkToken)) {
            return back()->with(['forgot_password_error' => 'Your password reset link is not valid, or already used']);
        }

        if (!empty($checkToken->reset_at)) {
            return back()->with(['forgot_password_error' => 'Your password reset link has already been used']);
        }

        $isExpired = Carbon::now()->gt(Carbon::parse($checkToken->expires_at));

        if ($isExpired) {
            return back()->with(['forgot_password_error' => 'Your password reset link has expired']);
        }

        $user = User::getById($checkToken->user_id);
        $response = $this->resetPassword($user, $password);
        if ($response) {
			$checkToken->update(['reset_at' => Carbon::now()]);
			// send help link in email
			//$link = 'topic/38-Canonized-help-statement-text/1';
			$link = 'topic/132-Help/1';
            try{
			    Mail::to($user->email)->send(new RecoverAccountMail($user,$link));			
                return $this->sendResetResponse("Password Reset Successfully");
            }catch(\Swift_TransportException $e){
                throw new \Swift_TransportException($e);
            } 
        }
        return $this->sendResetFailedResponse($request, $response);
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules() {
        return [
            'password' => ['required','regex:/^(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^\w\s]).{8,}$/','confirmed'
                ],
        ];
    }

    /**
     * Get the password reset validation error messages.
     *
     * @return array
     */
    protected function validationErrorMessages() {
        return $message = [
            'password.regex'=>'Password must be atleast 8 characters, including atleast one digit, one lower case letter and one special character(@,# !,$..)'
        ];
    }

    /**
     * Get the password reset credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request) {
        return $request->only(
                        'email', 'password', 'password_confirmation', 'token'
        );
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password) {
        $user->forceFill([
            'password' => bcrypt($password),
            'remember_token' => Str::random(60),
        ])->save();

        $this->guard()->login($user);
        return true;
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetResponse($response) {
        return redirect($this->redirectPath())->with('success', trans($response));
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetFailedResponse(Request $request, $response) {
        return redirect()->back()
                        ->withInput($request->only('email'))
                        ->withErrors(['email' => trans($response)]);
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard() {
        return Auth::guard();
    }

}
