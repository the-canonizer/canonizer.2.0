<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use App\User;
use App\Model\SocialUser;
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
       if (Auth::check()) {
    		$socialUser = SocialUser::create([
                'user_id'       => Auth::user()->id,
                'social_email'  => $userSocial->getEmail(),
                'provider_id'   => $userSocial->getId(),
                'provider'      => $provider,
            ]);
            return redirect()->route('settings.sociallinks');
    	}else{
    		$social_user = SocialUser::where(['social_email' => $userSocial->getEmail(),'provider'=>$provider,'provider_id'=>$userSocial->getId()])->first();
        if($social_user){
        	$users       =   User::where(['id' => $social_user->user_id])->first();
        	Auth::login($users);
		    return redirect('/');
        }else{
        		$users  =  User::where(['email' => $userSocial->getEmail()])->first();
				if($users){
						$socialUser = SocialUser::create([
			                'user_id'       => $users->id,
			                'social_email'  => $userSocial->getEmail(),
			                'provider_id'   => $userSocial->getId(),
			                'provider'      => $provider,
			            ]);
			            Auth::login($users);
			            return redirect('/');
			        }else{
			        	
			        		$authCode = mt_rand(100000, 999999);
							$user = User::create([
				                'first_name'    => $userSocial->getName(),
				                'email'         => $userSocial->getEmail(),
				                'otp'			=> $authCode
				            ]);
				            $socialUser = SocialUser::create([
				                'user_id'       => $users->id,
				                'social_email'  => $userSocial->getEmail(),
				                'provider_id'   => $userSocial->getId(),
				                'provider'      => $provider,
				            ]);
				             //otp email
					       Mail::to($user->email)->bcc(config('app.admin_bcc'))->send(new OtpVerificationMail($user));
					       return redirect()->route('register.otp', ['user' => base64_encode($user->email)]);
			        	
			 	}
        	}
    	}
        
        
	}
}
