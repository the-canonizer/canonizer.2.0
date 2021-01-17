<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use App\User;
use App\Model\SocialUser;
use App\Model\Nickname;
use App\Model\Support;
use App\Library\General;
use Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\WelcomeMail;
use App\Mail\OtpVerificationMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;

class SocialController extends Controller
{
    public function redirect($provider)
    {

     return Socialite::driver($provider)->redirect();
    
   }

    public function Callback(Request $request,$provider)
	{

		try{
			if ((!$request->has('code') &&  $request->has('denied')) || ($request->has('error') && $request->has('error_description')) ) {
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
      $user_email = ($userSocial->getEmail()) ? $userSocial->getEmail() : Auth::user()->email;
      $social_name =($userSocial->getNickname() && $userSocial->getNickname()!='') ? $userSocial->getNickname():$userSocial->getName();
       	$social_user = SocialUser::where(['social_email' => $user_email,'provider'=>$provider])->first();
       	 	if(isset($social_user) && isset($social_user->user_id) &&  $social_user->user_id != Auth::user()->id){
       	 		Session::flash('already_exists', "Email is already linked with another account");
       	 		$another_user = User::where(['id' => $social_user->user_id])->first();
       	 		return redirect()->route('settings.sociallinks')->with(['another_user' => $another_user] );
       	 	}else{

       	 		$socialUser = SocialUser::create([
	                'user_id'       => Auth::user()->id,
	                'social_email'  => $user_email,
	                'provider_id'   => $userSocial->getId(),
	                'provider'      => $provider,
	                'social_name'   => $social_name,
            	]);
            return redirect()->route('settings.sociallinks');	
       	 	}
    		
    	}else{
    		$user_email =  $userSocial->getEmail();
    		if(!empty($user_email)){
    			$social_name = ($userSocial->getNickname() && $userSocial->getNickname()!='') ? $userSocial->getNickname():$userSocial->getName();
    		$social_user = SocialUser::where(['social_email' => $user_email,'provider'=>$provider,'provider_id'=>$userSocial->getId()])->first();
	        if(isset($social_user) && isset($social_user->user_id)){
	        	$users       =   User::where(['id' => $social_user->user_id])->first();
	        	Auth::login($users);
			    return redirect('/');
	        }else{
	        		$users  =  User::where(['email' => $user_email])->first();
					if(isset($users) && isset($users->email)){
							$socialUser = SocialUser::create([
				                'user_id'       => $users->id,
				                'social_email'  => $user_email,
				                'provider_id'   => $userSocial->getId(),
				                'provider'      => $provider,
				                'social_name'   => $social_name,
				            ]);
				            Auth::login($users);
				            return redirect('/');
				        }else{
				        	
				        		$authCode = mt_rand(100000, 999999);
								$user = User::create([
					                'first_name'    => $userSocial->getName(),
					                'email'         => $user_email,
					                'otp'			=> $authCode
					            ]);
					            $socialUser = SocialUser::create([
					                'user_id'       => $user->id,
					                'social_email'  => $user_email,
					                'provider_id'   => $userSocial->getId(),
					                'provider'      => $provider,
					                 'social_name'   => $social_name,
					            ]);
					             //otp email
						       Mail::to($user->email)->bcc(config('app.admin_bcc'))->send(new OtpVerificationMail($user));
						       return redirect()->route('register.otp', ['user' => base64_encode($user->email)]);
				        	
				 	}
	        	}
    		}else{
    			session()->put('social_user',$userSocial);
    			session()->put('provider',$provider);
    			return redirect()->route('social.askemail');
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

	public function delete(Request $request)
    {
    	$input = $request->all();

    	$social_user = SocialUser::find($input['id']);
        $social_user->delete();  
        return redirect()->route('settings.sociallinks')
                        ->with('success','Social Link deleted successfully');
    }

    public function getAskEmail(Request $request){
        return view('auth.askemail');
    }

     public function postVerifyEmail(Request $request){
    	$all = $request->all();
    	$userSocial = session()->get('social_user');
    	$provider = session()->get('provider');
    	$message = [
          'email.required'=>'Please enter Email'  
        ];
        $validator = Validator::make($all, [
                    'email' => 'required|email',
        ],$message);

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator) 
                            ->withInput();
        }
        $users  =  User::where(['email' => $all['email']])->first();

        $user_email =  $all['email'];
     	$social_name = ($userSocial && $userSocial->getNickname() && $userSocial->getNickname()!='') ? $userSocial->getNickname():$userSocial->getName();

					if(isset($users) && isset($users->email) && $users->status!=0){
							$socialUser = SocialUser::create([
				                'user_id'       => $users->id,
				                'social_email'  => $user_email,
				                'provider_id'   => $userSocial->getId(),
				                'provider'      => $provider,
				                'social_name'   => $social_name,
				            ]);
				            Auth::login($users);
				            session()->forget('social_user');
    						session()->forget('provider');
				            return redirect('/');
				        }else if(isset($users) && isset($users->email) && $users->status ==0){
				        		Mail::to($users->email)->bcc(config('app.admin_bcc'))->send(new OtpVerificationMail($users));
						       return redirect()->route('register.otp', ['user' => base64_encode($users->email)]);
				        }else{
				        	
				        		$authCode = mt_rand(100000, 999999);
								$user = User::create([
					                'first_name'    => $userSocial->getName(),
					                'email'         => $user_email,
					                'otp'			=> $authCode,
					                'status'		=> 0
					            ]);
					            $socialUser = SocialUser::create([
					                'user_id'       => $user->id,
					                'social_email'  => $user_email,
					                'provider_id'   => $userSocial->getId(),
					                'provider'      => $provider,
					                 'social_name'   => $social_name,
					            ]);
					             //otp email
					           
						       Mail::to($user->email)->bcc(config('app.admin_bcc'))->send(new OtpVerificationMail($user));
						       return redirect()->route('register.otp', ['user' => base64_encode($user->email)]);
				        	
				 	}
	 }

	public function deactivateuser(Request $request){
		$input = $request->all();
		$user_to_deactivate = $input['user_deactivate'];
		// deactivate user
		$user = User::where('id','=',$user_to_deactivate)->first();
		$user->status = 0;
		$user->save();
		// delete all user supports 
		$encode = General::canon_encode($user_to_deactivate);
        //get nicknames
        $nicknames = Nickname::where('owner_code', '=', $encode)->get();
        $userNickname = Nickname::personNicknameArray();

        $as_of_time=time()+100;
        $supportedTopic = Support::whereIn('nick_name_id', $userNickname)
                ->whereRaw("(start < $as_of_time) and ((end = 0) or (end > $as_of_time))")
                ->groupBy('topic_num')->orderBy('start', 'DESC')->get();
        if(count($supportedTopic) > 0){
        	foreach($supportedTopic as $k=>$v){
        		$allUserSupports = Support::where('topic_num',$v->topic_num)
							->whereIn('nick_name_id',$userNickname)
							->whereRaw("(start < $as_of_time) and ((end = 0) or (end > $as_of_time))")
							->orderBy('support_order','ASC')							
							->get();
		 if(count($allUserSupports) > 0){
		 	foreach($allUserSupports as $key=>$support){
		 		  $currentSupport = Support::where('support_id', $support->support_id);
		 		  $currentSupport->update(array('end' => time()));
		 		}
		 	  }
           }
        }

        // removing linked social accounts 
        SocialUser::where('user_id', $user_to_deactivate)->delete();
        
		if(Auth::user()->id == $user_to_deactivate){
			return redirect()->route('login');
		}else{
			return redirect()->route('settings.sociallinks');
		}
	}
}
