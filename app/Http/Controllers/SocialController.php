<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use App\User;
use App\Model\SocialUser;
use Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\WelcomeMail;
use App\Mail\OtpVerificationMail;
use Illuminate\Auth\Events\Registered;

class SocialController extends Controller
{
    public function redirect($provider)
    {

     return Socialite::driver($provider)->redirect();
    
   }

    public function Callback(Request $request,$provider)
	{

		try{
			if (!$request->has('code') || $request->has('denied')) {
       			 if($request->has('error') && $request->has('error_description')){
       			 	Session::flash('social_error', $request['error_description']);
       			 }else{
       			 	Session::flash('social_error', "Cancelled $provider login authentication");	
       			 }
				 if (Auth::check()) {
				 	return redirect()->route('settings.sociallinks');
				 }else{
				 	return redirect()->route('login');	
				 }
				 
			}

			if($provider == 'twitter'){
			$userSocial =   Socialite::driver('twitter')->user();
       }else{
       		$userSocial =   Socialite::driver($provider)->stateless()->user();
       }
       if (Auth::check()) {
       	 $social_user = SocialUser::where(['social_email' => $userSocial->getEmail()])->first();
       	 	if(isset($social_user) && isset($social_user->user_id) &&  $social_user->user_id != Auth::user()->id){
       	 		Session::flash('already_exists', "Email is already linked with another account");
       	 		$another_user = User::where(['id' => $social_user->user_id])->first();
       	 		return redirect()->route('settings.sociallinks')->with(['another_user' => $another_user] );
       	 	}else{
       	 		$socialUser = SocialUser::create([
	                'user_id'       => Auth::user()->id,
	                'social_email'  => $userSocial->getEmail(),
	                'provider_id'   => $userSocial->getId(),
	                'provider'      => $provider,
            	]);
            return redirect()->route('settings.sociallinks');	
       	 	}
    		
    	}else{
    		$social_user = SocialUser::where(['social_email' => $userSocial->getEmail(),'provider'=>$provider,'provider_id'=>$userSocial->getId()])->first();
        if(isset($social_user) && isset($social_user->user_id)){
        	$users       =   User::where(['id' => $social_user->user_id])->first();
        	Auth::login($users);
		    return redirect('/');
        }else{
        		$users  =  User::where(['email' => $userSocial->getEmail()])->first();
				if(isset($user) && isset($user->email)){
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

		}catch(Exception $e){
			Session::flash('social_error', "Error in $provider login");
				 if (Auth::check()) {
				 	return redirect()->route('settings.sociallinks');
				 }else{
				 	return redirect()->route('login');	
				 }
		}
		
        
        
	}
}
