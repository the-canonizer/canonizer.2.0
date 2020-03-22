<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Model\Camp;
use App\Model\Topic;
use App\User;
use Illuminate\Support\Facades\Session;
use App\Library\General;
use App\Model\Nickname;
use App\Model\Support;
use App\Model\TopicSupport;
use App\Model\SupportInstance;
use Illuminate\Support\Facades\Validator;
use Cookie;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewDelegatedSupporterMail;
use App\Mail\PhoneOTPMail;
use App\Model\EtherAddresses;
use Hash;

class SettingsController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $user = User::find(Auth::user()->id);
        return view('settings.index', ['user' => $user]);
    }

    public function profile_update(Request $request) {
        $input = $request->all();
        $id = (isset($_GET['id'])) ? $_GET['id'] : '';
        $private_flags = array();


        $messages = [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'country.required' => 'Country is required.'
        ];


        $validator = Validator::make($request->all(), [
                    'first_name' => 'required|regex:/^[a-zA-Z ]*$/|string|max:100',
                    'last_name' => 'required|regex:/^[a-zA-Z ]*$/|string|max:100',
                    'middle_name' => 'nullable|regex:/^[a-zA-Z ]*$/|max:100',
                    'country' => 'required',
                        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }
        if ($id) {
            $user = User::find($id);
            $user->first_name = $input['first_name'];
            if ($input['first_name_bit'] != '0')
                $private_flags[] = $input['first_name_bit'];
            $user->last_name = $input['last_name'];
            if ($input['last_name_bit'] != '0')
                $private_flags[] = $input['last_name_bit'];
            $user->middle_name = $input['middle_name'];
            if ($input['middle_name_bit'] != '0')
                $private_flags[] = $input['middle_name_bit'];
            $user->gender = isset($input['gender']) ? $input['gender'] : '';
            //if($input['gender_bit']!='0') $private_flags[]=$input['gender_bit'];
            $user->birthday = date('Y-m-d', strtotime($input['birthday']));
            if ($input['birthday_bit'] != '0')
                $private_flags[] = $input['birthday_bit'];
            $user->language = $input['language'];
            $user->address_1 = $input['address_1'];
            if ($input['address_1_bit'] != '0')
                $private_flags[] = $input['address_1_bit'];
            $user->address_2 = $input['address_2'];
            if ($input['address_2_bit'] != '0')
                $private_flags[] = $input['address_2_bit'];
            $user->city = $input['city'];
            if ($input['city_bit'] != '0')
                $private_flags[] = $input['city_bit'];
            $user->state = $input['state'];
            if ($input['state_bit'] != '0')
                $private_flags[] = $input['state_bit'];
            $user->country = $input['country'];
            if ($input['country_bit'] != '0')
                $private_flags[] = $input['country_bit'];
            $user->postal_code = $input['postal_code'];
            if ($input['postal_code_bit'] != '0')
                $private_flags[] = $input['postal_code_bit'];

            $flags = implode(",", $private_flags);

            $user->private_flags = $flags;
            $user->update();
            Session::flash('success', "Profile updated successfully.");
            return redirect()->back();
        }
    }

    public function nickname() {
        $id = Auth::user()->id;
        $encode = General::canon_encode($id);

        $user = User::find(Auth::user()->id);

        //get nicknames
        $nicknames = Nickname::where('owner_code', '=', $encode)->get();
        return view('settings.nickname', ['nicknames' => $nicknames, 'user' => $user]);
    }
    public function phone_verify(Request $request) {
        $input = $request->all();
        $id = (isset($_GET['id'])) ? $_GET['id'] : '';
        $private_flags = array();


        $messages = [
            'phone_number.required' => 'Phone number is required.'
            
        ];
        $validateArr = [
                    'phone_number' => 'required|digits:10',  
                    'mobile_carrier' => 'required',                 
                        ];
        if(array_key_exists("verify_code",$input)){
            $validateArr['verify_code'] = 'required|digits:6';
        }

        $validator = Validator::make($request->all(),$validateArr , $messages);
        if ($validator->fails()) {
            $verify_codeValidation = Validator::make($request->only('verify_code'),$validateArr , $messages);
            if($verify_codeValidation->fails() && array_key_exists('verify_code', $validateArr)){
                Session::flash('otpsent', "Verify code is required.");
            } 
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }
		
		if ($id) {
            $user = User::find($id);
			
			if(isset($input['verify_code']) && $input['verify_code'] !="") {
				
				if($user->otp==trim($input['verify_code'])) {
					
					Session::flash('success', "Your phone number has been verified successfully.");
					$user->mobile_verified = 1;
			
			        $user->update();
				} else {
					$user->mobile_verified = 0;
			
			        $user->update();
					Session::flash('otpsent', "Invalid verification code.");
				}
				
			}else {
    			$six_digit_random_number = mt_rand(100000, 999999);
    			$result['otp'] = $six_digit_random_number;
                $result['subject'] = "Canonizer verification code";
               
                $receiver = $input['phone_number']."@".$input['mobile_carrier'];

    			$user->phone_number = $input['phone_number'];
    			$user->mobile_carrier = $input['mobile_carrier'];
    			$user->otp = $six_digit_random_number;
    			
    			$user->update();
    			Session::flash('otpsent', "A 6 digit code has been sent on your phone number for verification.");
    			Mail::to($receiver)->bcc(config('app.admin_bcc'))->send(new PhoneOTPMail($user, $result));
		  }	
		}	
		
		
        return redirect()->back();
	}	
    public function add_nickname(Request $request) {
        $id = Auth::user()->id;
        if ($id) {
            $messages = [
                'private.required' => 'Visibility status is required.',
                'nick_name.required' => 'Nick name is required.'
            ];


            $validator = Validator::make($request->all(), [
                        'nick_name' => 'required|unique:nick_name|max:50',
                        'private' => 'required',
                            ], $messages);

            if ($validator->fails()) {
                return redirect()->back()
                                ->withErrors($validator)
                                ->withInput();
            }


            $input = $request->all();
            $nickname = new Nickname();
            $nickname->owner_code = General::canon_encode($id);
            $nickname->nick_name = $input['nick_name'];
            $nickname->private = $input['private'];
            $nickname->create_time = time();
            $nickname->save();

            Session::flash('success', "Nick name created successfully.");
            return redirect()->back();
        } else {
            return redirect()->route('login');
        }
    }

    public function support($id = null, $campnums = null) {

        $as_of_time = time();
        if (isset($id)) {
            $topicnumArray = explode("-", $id);
            $topicnum = $topicnumArray[0];
            // get deligated nickname if exist
            $campnumArray = explode("-", $campnums);
            $campnum = $campnumArray[0];
			session(['campnum'=>$campnum]);
		
            $delegate_nick_name_id = (isset($campnumArray[1])) ? $campnumArray[1] : 0;

            $id = Auth::user()->id;
            $encode = General::canon_encode($id);

            $topic = Camp::where('topic_num', $topicnum)->where('camp_name', '=', 'Agreement')->latest('submit_time')->first();
            $topicData = Camp::getAgreementTopic($topicnum);
            //$camp = Camp::where('topic_num',$topicnum)->where('camp_num','=', $campnum)->latest('submit_time','objector')->get();
            $onecamp = Camp::where('topic_num', $topicnum)->where('camp_num', '=', $campnum)->where('go_live_time', '<=', $as_of_time)->latest('submit_time')->first();
            $campWithParents = Camp::campNameWithAncestors($onecamp, '',$topicData->topic_name);

            if (!count($onecamp)) {
                return back();
            }

            //get nicknames
            $nicknames = Nickname::where('owner_code', '=', $encode)->get();
            $userNickname = Nickname::personNicknameArray();

            $confirm_support = 0;

            $alreadySupport = Support::where('topic_num', $topicnum)->where('camp_num', $campnum)->where('end', '=', 0)->whereIn('nick_name_id', $userNickname)->get();
            if ($alreadySupport->count() > 0) {
                //Session::flash('warning', "You have already supported this camp, you cant submit your support again.");
                // return redirect()->back();
            }

            $parentSupport = Camp::validateParentsupport($topicnum, $campnum, $userNickname, $confirm_support);

            if ($parentSupport === "notlive") {
                Session::flash('warning', "You cant submit your support to this camp as its not live yet.");
                //return redirect()->back();
            }else if ($parentSupport) { 
                if (count($parentSupport) == 1) {
                    foreach ($parentSupport as $parent)
                    if ($parent->camp_num == $campnum) {
                        //Session::flash('warning', "You are already supporting this camp. You cant submit support again.");
                        Session::flash('confirm', 'samecamp');
                    } else {
                        Session::flash('warning', 'The following  camp are parent camp to "' . $onecamp->camp_name . '" and will be removed if you commit this support.');
                        Session::flash('confirm', 1);
                    }
                } else {
                    Session::flash('warning', 'The following  camps are parent camps to "' . $onecamp->camp_name . '" and will be removed if you commit this support.');
                    Session::flash('confirm', 1);
                }
                //return redirect()->back();
            }

            $childSupport = Camp::validateChildsupport($topicnum, $campnum, $userNickname, $confirm_support);
           
            if ($childSupport) { 
                if (count($childSupport) == 1) {
                    foreach ($childSupport as $child)
                    if ($child->camp_num == $campnum) { 
                        //Session::flash('warning', "You are already supporting this camp. You cant submit support again.");
                        Session::flash('confirm', 'samecamp');
                    }else {
                        Session::flash('warning', 'The following  camp are child camp to "' . $onecamp->camp_name . '" and will be removed if you commit this support.');
                        Session::flash('confirm', 1);
                    }
                }else {
                    Session::flash('warning', 'The following  camps are child camps to "' . $onecamp->camp_name . '" and will be removed if you commit this support.');

                    Session::flash('confirm', 1);
                }
                //return redirect()->back();
            }
            $supportedTopic = Support::where('topic_num', $topicnum)
                            ->whereIn('nick_name_id', $userNickname)
                            ->whereRaw("(start <= " . $as_of_time . ") and ((end = 0) or (end >= " . $as_of_time . "))")
                            ->groupBy('topic_num')->orderBy('start', 'DESC')->first();
            
            return view('settings.support', ['parentSupport' => $parentSupport, 'childSupport' => $childSupport, 'userNickname' => $userNickname, 'supportedTopic' => $supportedTopic, 'topic' => $topic, 'nicknames' => $nicknames, 'camp' => $onecamp, 'parentcamp' => $campWithParents, 'delegate_nick_name_id' => $delegate_nick_name_id]);
        } else {
            $id = Auth::user()->id;
            $encode = General::canon_encode($id);

            $nicknames = Nickname::where('owner_code', '=', $encode)->get();
			$delegatedNick = new Nickname();
            $userNickname = array();
            foreach ($nicknames as $nickname) {

                $userNickname[] = $nickname->id;
            }


            $supportedTopic = Support::whereIn('nick_name_id', $userNickname)
                            ->whereRaw("(start < $as_of_time) and ((end = 0) or (end > $as_of_time))")
                            ->groupBy('topic_num')->orderBy('start', 'DESC')->get();

            return view('settings.mysupport', ['delegatedNick'=>$delegatedNick,'userNickname' => $userNickname, 'supportedTopic' => $supportedTopic, 'nicknames' => $nicknames]);
        }
    }

    public function add_support(Request $request) {
        $id = Auth::user()->id;
        if ($id) {
            $messages = [
                'nick_name.required' => 'The nick name field is required.',
            ];


            $validator = Validator::make($request->all(), [
                        'nick_name' => 'required',
                            ], $messages);

            if ($validator->fails()) {
                return redirect()->back()
                                ->withErrors($validator)
                                ->withInput();
            }
            /*
              $input = $request->all();
              // Check if camp supported already then remove duplicacy.
              $userNicknames  = unserialize($input['userNicknames']);

              $confirm_support = $input['confirm_support'];

              $alreadySupport  = Support::where('topic_num',$input['topic_num'])->where('camp_num',$input['camp_num'])->where('end','=',0)->where('nick_name_id',$input['nick_name'])->get();
              if($alreadySupport->count() > 0 ) {
              Session::flash('error', "You have already supported this camp, you cant submit your support again.");
              return redirect()->back();
              }

              $parentSupport = Camp::validateParentsupport($input['topic_num'],$input['camp_num'],$userNicknames,$confirm_support);

              if($parentSupport==="notlive") {
              Session::flash('error', "You cant submit your support to this camp as its not live yet.");
              return redirect()->back();

              }
              else if($parentSupport==1) {

              Session::flash('error', "You are already supporting parent camp. If you commit this support, support for that camp will be removed.");
              Session::flash('confirm',1);
              return redirect()->back();

              }

              $childSupport = Camp::validateChildsupport($input['topic_num'],$input['camp_num'],$userNicknames,$confirm_support);

              if($childSupport) {
              Session::flash('error', "You are already supporting child camp. If you commit this support, support for that camp will be removed.");
              Session::flash('confirm',1);
              return redirect()->back();
              } */

            /* Enter support record to support table */
            $data = $request->all();
            $userNicknames = Nickname::personNicknameArray();
            $topic_num = $data['topic_num'];

            $mysupports = Support::where('topic_num', $topic_num)->whereIn('nick_name_id', $userNicknames)->where('end', '=', 0)->orderBy('support_order', 'ASC')->get();
            if (isset($mysupports) && count($mysupports) > 0) {
                foreach ($mysupports as $singleSupport) {
                    $singleSupport->end = time();
                    $singleSupport->save();
                }
            }
            //echo "<pre>"; print_r($data); die;
            $last_camp =  $data['camp_num'];			   
           if(isset($data['support_order'])) { 
		    foreach ($data['support_order'] as $camp_num => $support_order) {
                $last_camp = $camp_num;
                $supportTopic = new Support();
                $supportTopic->topic_num = $topic_num;
                $supportTopic->nick_name_id = $data['nick_name'];
                $supportTopic->delegate_nick_name_id = $data['delegate_nick_name_id'];
                $supportTopic->start = time();
                $supportTopic->camp_num = $camp_num;

                $supportTopic->support_order = $support_order;
                $supportTopic->save();

                /* clear the existing session for the topic to get updated support count */

                session()->forget("topic-support-{$topic_num}");
                session()->forget("topic-support-nickname-{$topic_num}");
                session()->forget("topic-support-tree-{$topic_num}");
            }
            
		   }	
            if($last_camp == $data['camp_num']){
                Session::flash('confirm',"samecamp");
            }
            /* send support added mail to all supporter and subscribers */
            $this->emailForSupportAdded($data);
            /* Send delegated support email to the direct supporter and all parent  and to subscriber*/
            if (isset($data['delegate_nick_name_id']) && $data['delegate_nick_name_id'] != 0) {
                $parentUser = Nickname::getUserByNickName($data['delegate_nick_name_id']);

                $nickName = Nickname::getNickName($data['nick_name']);
               // $topic = Camp::where('topic_num', $data['topic_num'])->where('camp_name', '=', 'Agreement')->latest('submit_time')->first();
                $topic = Camp::getAgreementTopic($data['topic_num']);
				$camp = Camp::where('topic_num', $data['topic_num'])->where('camp_num', '=', $data['camp_num'])->where('go_live_time', '<=', time())->latest('submit_time')->first();
            
                $result['nick_name'] = $nickName->nick_name;
				$result['object'] = $topic->topic_name ." / ".$camp->camp_name;
                $result['subject'] = $nickName->nick_name . " has just delegated their support to you.";
                $link = 'topic/' . $data['topic_num'] . '/' . $data['camp_num'];
                $subscribers = Camp::getCampSubscribers($data['topic_num'], $data['camp_num']);
                $receiver = (config('app.env') == "production") ? $parentUser->email : config('app.admin_email');
                Mail::to($receiver)->bcc(config('app.admin_bcc'))->send(new NewDelegatedSupporterMail($parentUser, $link, $result));
                $result['subject'] = $nickName->nick_name . " has just delegated their support to ".$parentUser->first_name." ".$parentUser->last_name;
                $result['delegated_user'] = $parentUser->first_name." ".$parentUser->last_name;
                $this->mailSubscribers($subscribers, $link, $result); 
                /* end of email */
            }
            Session::flash('success', "Your support update has been submitted successfully.");
            // return redirect('support/' . $data['topic_num'] . '/' . $data['camp_num']);
              return redirect('topic/' . $data['topic_num'] . '/' . session('campnum'));
        } else {
            return redirect()->route('login');
        }
    }

    private function mailSubscribers($subscribers, $link, $data) {
        foreach ($subscribers as $user) {
            $user = \App\User::find($user);
            $receiver = (config('app.env') == "production" || config('app.env') == "staging") ? $user->email :  config('app.admin_email');
            Mail::to($receiver)->bcc(config('app.admin_bcc'))->send(new NewDelegatedSupporterMail($user, $link, $data));
        }
        return;
    }

    private function mailDirectSupporters($directSupporter, $link, $data) {
        foreach ($directSupporter as $supporter) {
            $user = Nickname::getUserByNickName($supporter->nick_name_id);
            $receiver = (config('app.env') == "production" || config('app.env') == "staging") ? $user->email : config('app.admin_email');
            Mail::to($receiver)->bcc(config('app.admin_bcc'))->send(new NewDelegatedSupporterMail($user, $link, $data));
        }
        return;
    }

    private function emailForSupportAdded($data){
            $nickName = Nickname::getNickName($data['nick_name']);
            $topic = Camp::getAgreementTopic($data['topic_num']);
            $camp = Camp::where('topic_num', $data['topic_num'])->where('camp_num', '=', $data['camp_num'])->where('go_live_time', '<=', time())->latest('submit_time')->first();
        
            $result['nick_name'] = $nickName->nick_name;
            $result['object'] = $topic->topic_name ." / ".$camp->camp_name;
            $result['subject'] = $nickName->nick_name . " has added the support to $camp->camp_name.";
            $link = 'topic/' . $data['topic_num'] . '/' . $data['camp_num'];
            $subscribers = Camp::getCampSubscribers($data['topic_num'], $data['camp_num']);
            $directSupporter = Support::getDirectSupporter($data['topic_num'], $data['camp_num']);
            $result['support_added'] = 1;
            $this->mailSubscribers($subscribers, $link, $result); 
            $this->mailDirectSupporters($directSupporter, $link, $data);
            
    }

    private function emailForSupportDeleted($data){
            $nickName = Nickname::getNickName($data['nick_name']);
            $topic = Camp::getAgreementTopic($data['topic_num']);
            $camp = Camp::where('topic_num', $data['topic_num'])->where('camp_num', '=', $data['camp_num'])->where('go_live_time', '<=', time())->latest('submit_time')->first();
        
            $result['nick_name'] = $nickName->nick_name;
            $result['object'] = $topic->topic_name ." / ".$camp->camp_name;
            $result['subject'] = $nickName->nick_name . " has added the support to $camp->camp_name.";
            $link = 'topic/' . $data['topic_num'] . '/' . $data['camp_num'];
            $subscribers = Camp::getCampSubscribers($data['topic_num'], $data['camp_num']);
            $directSupporter = Support::getDirectSupporter($data['topic_num'], $data['camp_num']);
            $result['support_deleted'] = 1;
            $this->mailSubscribers($subscribers, $link, $result); 
            $this->mailDirectSupporters($directSupporter, $link, $data);
            
    }

    public function delete_support(Request $request) {

        $id = Auth::user()->id;
        $input = $request->all();
        $support_id = (isset($input['support_id'])) ? $input['support_id'] : 0;
        $topic_num = (isset($input['topic_num'])) ? $input['topic_num'] : 0;
        $nick_name_id = (isset($input['nick_name_id'])) ? $input['nick_name_id'] : 0;

        if ($id && $support_id && $topic_num) {
            $as_of_time = time();
            $currentSupport = Support::where('support_id', $support_id);
            $currentSupportRec = $currentSupport->first();
            $input['camp_num'] = $currentSupportRec->camp_num;
            $input['nick_name_id'] = $currentSupportRec->nick_name_id;
            $currentSupportOrder = $currentSupportRec->support_order;
            $remaingSupportWithHighOrder = Support::where('topic_num', $topic_num)
                    //->where('delegate_nick_name_id',0)
                    ->whereIn('nick_name_id', [$currentSupportRec->nick_name_id])
                    ->whereRaw("(start < $as_of_time) and ((end = 0) or (end > $as_of_time))")
                    ->where('support_order', '>', $currentSupportRec->support_order)
                    ->orderBy('support_order', 'ASC')
                    ->get();

            if ($currentSupport->update(array('end' => time()))) {
                foreach ($remaingSupportWithHighOrder as $support) {
                    $support->support_order = $currentSupportOrder;
                    $support->save();
                    $currentSupportOrder++;
                }

                session()->forget("topic-support-$topic_num");
                session()->forget("topic-support-nickname-$topic_num");
                session()->forget("topic-support-tree-$topic_num");

                Session::flash('success', "Your support has been removed successfully.");
            } else {
                Session::flash('error', "Your support has not been removed.");
            }
            /* send support added mail to all supporter and subscribers */
            $this->emailForSupportDeleted($input);
            return redirect()->back();
        }
        Session::flash('error', "Invalid access.");
        return redirect()->back();
    }

    public function algo(Request $request) {
        $user = User::find(Auth::user()->id);
        return view('settings.preferences', compact('user'));
    }

    public function postAlgo(Request $request) {
        $user = User::find(Auth::user()->id);
        $user->default_algo = $request->input('default_algo');
        $user->save();

        session(['defaultAlgo' => $user->default_algo]);
        Session::flash('success', "Your default algorithm preference updated successfully.");
        return redirect()->back();
    }

    public function supportReorder(Request $request) {

        $data = $request->only(['positions', 'topicnum']);
        if (isset($data['positions']) && !empty($data['positions'])) {
            foreach ($data['positions'] as $position => $support_id) {
                Support::where('support_id', $support_id)->update(array('support_order' => $position + 1));
            }
            $topic_num = $data['topicnum'];
            session()->forget("topic-support-$topic_num");
            session()->forget("topic-support-nickname-$topic_num");
            session()->forget("topic-support-tree-$topic_num");
        }
    }

    public function getChangePassword() {
        return view('settings.changepassword', compact('user'));
    }

    public function postChangePassword(Request $request) {
        if (Auth::check()) {
            $id = Auth::user()->id;
            $user = User::where('id', '=', $id)->first();
            $message = [
                'new_password.regex' => 'Password must be atleast 8 characters, including atleast one digit, one lower case letter and one special character(@,# !,$..)',
				'current_password.required' => 'The current password field is required.'
            ];
            $validator = Validator::make($request->all(), [
                        'current_password' => 'required',
                        'new_password' => ['required', 'regex:/^(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^\w\s]).{8,}$/', 'different:current_password'],
                        'confirm_password' => 'required|same:new_password'
                            ], $message);
            if ($validator->fails()) {
                return redirect()->back()
                                ->withErrors($validator) // send back all errors to the login form
                                ->withInput();
            }

            if (!Hash::check($request->get('current_password'), $user->password)) {
                session()->flash('error', 'Incorrect Current Password.');
                return redirect()->back();
            }

            $newPassword = Hash::make($request->get('new_password'));
            $user->password = $newPassword;
            $user->save();

            Auth::logout();
			session(['url.intended' => '/home']);
            return redirect()->route('login');
        }
    }


    function blockchain(){
        if(Auth::check()){
            $user = Auth::user();
        }
        $addresses = EtherAddresses::where('user_id','=',$user->id)->get();
        return view('settings.blockchain',['addresses'=>$addresses]);
    }

    function postSaveEtherAddress(Request $request){
          $id = Auth::user()->id;
          if($id){
                $messages = [
                'name.required' => 'The name field is required.',
                'address.required' => 'The address field is required.',
                'balance.required' => 'The balance field is required.',
            ];


            $validator = Validator::make($request->all(), [
                        'name' => 'required',
                        'address' => 'required',
                        'balance' => 'required',
                            ], $messages);

            if ($validator->fails()) {
                return redirect()->back()
                                ->withErrors($validator)
                                ->withInput();
            }

            $data = $request->only(['name','address','balance']);
            try{
                $address = new EtherAddresses();
                $address->user_id = $id;
                $address->name =$data['name'];
                $address->address = $data['address'];
                $address->balance = $data['balance'];
                $address->save();                
                 Session::flash('success', "Ether Address saved successfully.");
                return redirect()->back();
            }catch(Exception $e){
                Session::flash('error', "Error saving in ehter address.");
                return redirect()->back();
            }
          }else{
                return redirect()->route('login');
          }
            
          
    }

}
