<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use App\User;
use Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use App\Mail\OtpVerificationMail;
use Illuminate\Auth\Events\Registered;

class SocialController extends Controller
{
    public function redirect($provider)
    {

     return Socialite::driver($provider)->redirect();
    }

    public function Callback($provider)
	{
		if($provider == 'twitter'){
			$userSocial =   Socialite::driver('twitter')->user();
       }else{
       		$userSocial =   Socialite::driver($provider)->stateless()->user();
       }
        $users       =   User::where(['email' => $userSocial->getEmail()])->first();
		if($users){
		            Auth::login($users);
		            return redirect('/');
		        }else{
					$user = User::create([
		                'first_name'          => $userSocial->getName(),
		                'email'         => $userSocial->getEmail(),
		                'provider_id'   => $userSocial->getId(),
		                'provider'      => $provider,
		            ]);
		             $link = 'topic/132-Help/1';	
		              if(isset($user) && isset($user->email) && $user->email!=='')	{
		              		Mail::to($user->email)->bcc(config('app.admin_bcc'))->send(new WelcomeMail($user,$link));
		              }
				        
				        
				        Auth::guard()->login($user);
		         return redirect()->route('home');
		 }
	}
}
