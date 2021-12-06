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
use App\Model\SocialUser;
use Hash;


class SettingsController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $user = User::find(Auth::user()->id);
        return view('settings.index', ['user' => $user]);
    }

    public function profile_update(Request $request)
    {
        $input = $request->all();
        $id = (isset($_GET['id'])) ? $_GET['id'] : '';
        $private_flags = array();
        
        $messages = [
            'first_name.required' => 'The first name field is required.',
            'first_name.max' => 'The first name can not be more than 100.',
            'first_name.regex' => 'The first name must be in alphabets and space only.',
            'last_name.required' => 'The last name field is required.',
            'last_name.max' => 'The last name can not be more than 100.',
            'last_name.regex' => 'The last name must be in alphabets and space only.',
            'middle_name.regex' => 'The middle name must be in alphabets and space only.',
            'middle_name.max' => 'The middle name can not be more than 100.',
        ];
        

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|regex:/^[a-zA-Z ]*$/|string|max:100',
            'last_name' => 'required|regex:/^[a-zA-Z ]*$/|string|max:100',
            'middle_name' => 'nullable|regex:/^[a-zA-Z ]*$/|max:100',
            //'postal_code' => 'nullable|regex:/^[a-zA-Z0-9 ]*$/|max:100',
           // 'country' => 'required',
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
			
			if ($input['email_bit'] != '0')
                $private_flags[] = $input['email_bit'];
			
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
            if ($input['email_bit'] != '0')
                $private_flags[] = $input['email_bit'];

            $flags = implode(",", $private_flags);
            $user->default_algo = $request->input('default_algo');
            $user->private_flags = $flags;
            $user->update();
        
        session(['defaultAlgo' => $user->default_algo]);
            Session::flash('success', "Profile updated successfully.");
            return redirect()->back();
        }
    }

    public function nickname()
    {
        $id = Auth::user()->id;
        $encode = General::canon_encode($id);

        $user = User::find(Auth::user()->id);

        //get nicknames
        $nicknames = Nickname::where('owner_code', '=', $encode)->get();
        return view('settings.nickname', ['nicknames' => $nicknames, 'user' => $user]);
    }
    public function phone_verify(Request $request)
    {
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
        if (array_key_exists("verify_code", $input)) {
            $validateArr['verify_code'] = 'required|digits:6';
        }

        $validator = Validator::make($request->all(), $validateArr, $messages);
        if ($validator->fails()) {
            $verify_codeValidation = Validator::make($request->only('verify_code'), $validateArr, $messages);
            if ($verify_codeValidation->fails() && array_key_exists('verify_code', $validateArr)) {
                Session::flash('otpsent', "Verify code is required.");
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if ($id) {
            $user = User::find($id);

            if (isset($input['verify_code']) && $input['verify_code'] != "") {

                if ($user->otp == trim($input['verify_code'])) {

                    Session::flash('success', "Your phone number has been verified successfully.");
                    $user->mobile_verified = 1;

                    $user->update();
                } else {
                    $user->mobile_verified = 0;

                    $user->update();
                    Session::flash('otpsent', "Invalid One Time Verification Code.");
                }
            } else {
                $six_digit_random_number = mt_rand(100000, 999999);
                $result['otp'] = $six_digit_random_number;
                $result['subject'] = "Canonizer verification code";

                $receiver = $input['phone_number'] . "@" . $input['mobile_carrier'];

                $user->phone_number = $input['phone_number'];
                $user->mobile_carrier = $input['mobile_carrier'];
                $user->otp = $six_digit_random_number;

                $user->update();
                Session::flash('otpsent', "A 6 digit code has been sent on your phone number for verification.");
                try{

                Mail::to($receiver)->bcc(config('app.admin_bcc'))->send(new PhoneOTPMail($user, $result));
                }catch(\Swift_TransportException $e){
                        throw new \Swift_TransportException($e);
                        //$response = $e->getMessage();
                    } 
            }
        }


        return redirect()->back();
    }
    public function add_nickname(Request $request)
    {
        $id = Auth::user()->id;
        if ($id) {
            $messages = [
                'private.required' => 'Visibility status is required.',
                'nick_name.required' => 'Nick name is required.',
                'nick_name.max' => 'Nick name can not be more than 50 characters.',
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

    public function support($id = null, $campnums = null)
    {
        $as_of_time = time();
        if (isset($id)) {
            $topicnumArray = explode("-", $id);
            $topicnum = $topicnumArray[0];
            // get deligated nickname if exist
            $campnumArray = explode("_", $campnums);
            $campnum = explode("-",$campnumArray[0])[0];
            session(['campnum' => $campnum]);
            $delegate_nick_name_id = (isset($campnumArray[1])) ? $campnumArray[1] : 0;
            if(!$delegate_nick_name_id){
                $delegate_nick_name_id = 0;
            }
            $id = Auth::user()->id;
            $encode = General::canon_encode($id);

            $topic = Camp::where('topic_num', $topicnum)->where('camp_name', '=', 'Agreement')->latest('submit_time')->first();
            $topicData = Camp::getAgreementTopic($topicnum,['nofilter'=>true]);
            //$camp = Camp::where('topic_num',$topicnum)->where('camp_num','=', $campnum)->latest('submit_time','objector')->get();
            $onecamp = Camp::where('topic_num', $topicnum)->where('camp_num', '=', $campnum)->where('go_live_time', '<=', $as_of_time)->latest('submit_time')->first();
            $campWithParents = Camp::campNameWithAncestors($onecamp, '', $topicData->topic_name);
            if (!count($onecamp)) {
                return back();
            }

            //get nicknames
            //$nicknames = Nickname::where('owner_code', '=', $encode)->get();
            $nicknames = Nickname::topicCampNicknameUsed($topicnum,$campnum,$encode);
            $userNickname = Nickname::personNicknameArray();

            $confirm_support = 0;

            $alreadySupport = Support::where('topic_num', $topicnum)->where('camp_num', $campnum)->where('end', '=', 0)->whereIn('nick_name_id', $userNickname)->get();
            if ($alreadySupport->count() > 0) {
                if($alreadySupport[0]->delegate_nick_name_id!=0){
                    $nickName = Nickname::where('id',$alreadySupport[0]->delegate_nick_name_id)->first();
                    $userFromNickname = $nickName->getUser();
                    Session::flash('warningDelegate', "You have already delegated your support for this camp to user ".$userFromNickname->first_name." ".$userFromNickname->last_name.". If you continue your delegated support will be removed.");

                }
                //Session::flash('warning', "You have already supported this camp, you can't submit your support again.");
                // return redirect()->back();
            }



            $parentSupport = Camp::validateParentsupport($topicnum, $campnum, $userNickname, $confirm_support);
             if ($parentSupport === "notlive") {
                Session::flash('warning', "You cant submit your support to this camp as its not live yet.");
                //return redirect()->back();
            } else if ($parentSupport) {
                if (count($parentSupport) == 1) {
                   
                    foreach ($parentSupport as $parent){
                        
                        $parentCampName = Camp::getCampNameByTopicIdCampId($onecamp->topic_num, $parent->camp_num, $as_of_time);
                        if ($parent->camp_num == $campnum) {
                            //Session::flash('warning', "You are already supporting this camp. You can't submit support again.");
                            Session::flash('confirm', 'samecamp');
                        } else {
                            Session::flash('warning', '"'.$onecamp->camp_name .'" is a child camp to "' .$parentCampName .'", so if you commit support to "'.$onecamp->camp_name .'", the support of the parent camp "' .$parentCampName .'" will be removed.');
                            Session::flash('confirm', 1);
                        }
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
                    {
                        $childCampName = Camp::getCampNameByTopicIdCampId($topicnum, $child->camp_num, $as_of_time);
                        if ($child->camp_num == $campnum && $child->delegate_nick_name_id == 0) {
                            // Session::flash('warning', "You are already supporting this camp. You cant submit support again.");
                            Session::flash('confirm', 'samecamp');
                        } else {
                                Session::flash('warning', '"'.$onecamp->camp_name .'" is a parent camp to "'. $childCampName. '", so if you commit support to "'.$onecamp->camp_name .'", the support of the child camp "'. $childCampName. '" will be removed.');
                                Session::flash('confirm', 1);
                            }
                    }
                } else {
                        
                        Session::flash('warning', '"'.$onecamp->camp_name .'" is a parent camp to this list of child camps. If you commit support to "'.$onecamp->camp_name .'", the support of the camps in this list will be removed.');

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
           // $nicknames = Nickname::topicCampNicknameUsed($topicnum,$campnum,$encode);
            $delegatedNick = new Nickname();
            $userNickname = array();
            foreach ($nicknames as $nickname) {
                $userNickname[] = $nickname->id;
            }


            $supportedTopic = Support::whereIn('nick_name_id', $userNickname)
                ->whereRaw("(start < $as_of_time) and ((end = 0) or (end > $as_of_time))")
                ->groupBy('topic_num')->orderBy('start', 'DESC')->get();

            return view('settings.mysupport', ['delegatedNick' => $delegatedNick, 'userNickname' => $userNickname, 'supportedTopic' => $supportedTopic, 'nicknames' => $nicknames]);
        }
    }

    public function add_support(Request $request)
    {
        $id = Auth::user()->id;
        $alreadyMailed = [];
        $as_of_time = time();
        if ($id) {
            $messages = [
                'nick_name.required' => 'The nick name field is required.',
                'nick_name.max' => 'The nick name can not be more than 50 characters.',
            ];
            $validator = Validator::make($request->all(), [
                'nick_name' => 'required|max:50',
            ], $messages);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
           
            /* Enter support record to support table */
            $data = $request->all();
            $userNicknames = Nickname::personNicknameArray();
            $topic_num = $data['topic_num'];
            $mysupportArray = [];
            $myDelegatedSupports = [];
            $myDelegator = Support::where('topic_num', $topic_num)->whereIn('delegate_nick_name_id', $userNicknames)->where('end', '=', 0)->groupBy('nick_name_id')->get();
            $mysupports = Support::where('topic_num', $topic_num)->whereIn('nick_name_id', $userNicknames)->where('end', '=', 0)->orderBy('support_order', 'ASC')->get();
           if(isset($mysupports) && count($mysupports) > 0){
                foreach ($mysupports as $spp){
                    if($spp['delegate_nick_name_id'] == 0){
                        $mysupportArray[] =  $spp->camp_num;  
                    }else{
                        $myDelegatedSupports[] =  $spp->camp_num;  
                    }
                }
            }

                       
            if (isset($mysupports) && count($mysupports) > 0 && isset($data['removed_camp']) && count($data['removed_camp']) > 0) {
                foreach ($mysupports as $singleSupport) { 
                    if(in_array($singleSupport->camp_num,$data['removed_camp'])){
                       /** By Reena Nalwa Talentelgia #790 III part
                        * Pushing delegating hierarchy up if direct supporter remove all his/her support
                        */
                       if(count($mysupports) == count($data['removed_camp'])){
                            Support::where('topic_num', $topic_num)->where('camp_num','=', $singleSupport->camp_num)->where('delegate_nick_name_id', $singleSupport->nick_name_id)->where('end', '=', 0)
                            ->update(['delegate_nick_name_id' => 0]);
                            //send email to all direct delegates promted as direct supportes
                        }else{ 
                            //remove delegation from camp including sub-elevel
                            $currentSupportOrder = $singleSupport->support_order;
                            $remaingSupportWithHighOrder = Support::where('topic_num', $topic_num)
                                //->where('delegate_nick_name_id',0)
                                ->whereIn('nick_name_id', [$singleSupport->nick_name_id])
                                ->whereRaw("(start < $as_of_time) and ((end = 0) or (end > $as_of_time))")
                                ->where('support_order', '>', $singleSupport->support_order)
                                ->orderBy('support_order', 'ASC')
                                ->get(); 

                            foreach ($remaingSupportWithHighOrder as $support) {
                                $support->support_order = $currentSupportOrder;
                                $support->save();
                                $currentSupportOrder++;                 
                            }
                             $this->deleteDelegateSupport($topic_num,$singleSupport->camp_num,$singleSupport->nick_name_id,$remaingSupportWithHighOrder,$singleSupport->support_order);
                        }

                        $singleSupport->end = time();
                        $singleSupport->save();
                        $mailData = $data;
                        $mailData['camp_num'] = $singleSupport->camp_num;
                         /* send support deleted mail to all supporter and subscribers */
                         try{
                         $this->emailForSupportDeleted($mailData); 
                         }catch(Exception $e){
                             
                         }
                    } 
                 }    
            }

              /** if user is delegating to someone else or is directly supporting  then all the old delegated  supports will be removed #702 **/
            if(isset($myDelegatedSupports) && count($myDelegatedSupports) > 0){
                foreach ($mysupports as $singleSupport) {

                     if(in_array($singleSupport->camp_num,$myDelegatedSupports)){
                        $singleSupport->end = time();
                        $singleSupport->save();
                        $mailData = $data;
                        $mailData['camp_num'] = $singleSupport->camp_num;
                        /* send support deleted mail to all supporter and subscribers */
                       $this->emailForSupportDeleted($mailData);    
                    }             
                }
            }
            
            $last_camp =  $data['camp_num'];
            $newcamp_mail_flag = false;
           
            if(isset($myDelegatedSupports) && count($myDelegatedSupports) > 0 && isset($data['delegate_nick_name_id']) && $data['delegate_nick_name_id'] == 0 ){ // removing delegte support and directly supporting camp
                        $last_camp = $data['camp_num'];
                        $newcamp_mail_flag = true;
                        $supportTopic = new Support();
                        $supportTopic->topic_num = $topic_num;
                        $supportTopic->nick_name_id = $data['nick_name'];
                        $supportTopic->delegate_nick_name_id = $data['delegate_nick_name_id'];
                        $supportTopic->start = time();
                        $supportTopic->camp_num = $data['camp_num'];
                        $supportTopic->support_order = 1;
                        $supportTopic->save();

                         /* clear the existing session for the topic to get updated support count */

                    session()->forget("topic-support-{$topic_num}");
                    session()->forget("topic-support-nickname-{$topic_num}");
                    session()->forget("topic-support-tree-{$topic_num}");
                         /* send support added mail to all supporter and subscribers */
                        if($newcamp_mail_flag){
                            $this->emailForSupportAdded($data);   
                        }
            }else if (isset($data['support_order']) && isset($data['delegate_nick_name_id']) && $data['delegate_nick_name_id'] == 0 ) {
               foreach ($data['support_order'] as $camp_num => $support_order) {
                    $last_camp = $camp_num;
                   if(!in_array($camp_num,$mysupportArray)){
                        $newcamp_mail_flag = true;
                        $data['camp_num'] = $camp_num;
                        $supportTopic = new Support();
                        $supportTopic->topic_num = $topic_num;
                        $supportTopic->nick_name_id = $data['nick_name'];
                        $supportTopic->delegate_nick_name_id = $data['delegate_nick_name_id'];
                        $supportTopic->start = time();
                        $supportTopic->camp_num = $camp_num;
                        $supportTopic->support_order = $support_order;
                        $supportTopic->save();

                        /** If any user hase delegated their support to this user, record/add there delegated support #749 including sub-level delegates(&49 II Part) */
                        $this->addDelegatedSupport($myDelegator,$topic_num,$camp_num,$support_order,$data['nick_name']);
                        
                    }else{
                        $support = Support::where('topic_num', $topic_num)->where('camp_num','=', $camp_num)->where('nick_name_id','=',$data['nick_name'])->where('end', '=', 0)->get();
                        $support[0]->support_order = $support_order;
                        $support[0]->save();
                        /** By Reena Nalwa Talentelgia 11th Oct #749 II Part(Re-ordering Case)  And #790 All sub-level delegates */
                        $this->reorderDelegateSupport($camp_num,$topic_num,$data['nick_name'],$support_order);
                    }
                    /* clear the existing session for the topic to get updated support count */
                    session()->forget("topic-support-{$topic_num}");
                    session()->forget("topic-support-nickname-{$topic_num}");
                    session()->forget("topic-support-tree-{$topic_num}");
                }
                /* send support added mail to all supporter and subscribers */
                if($newcamp_mail_flag){
                    $this->emailForSupportAdded($data);   
                }
            }else if($data['delegate_nick_name_id'] !=0){
                 $delegateUsersupports = Support::where('topic_num', $topic_num)->where('nick_name_id', $data['delegate_nick_name_id'])->where('end', '=', 0)->orderBy('support_order', 'ASC')->get();
                 if(isset($delegateUsersupports) && count($delegateUsersupports) > 0){
                    foreach($delegateUsersupports as $cmp){
                        $last_camp = $cmp->camp_num;
                        if(in_array($last_camp,$mysupportArray)){ // if user is also a direct supporter end that support
                            $support = Support::where('topic_num', $topic_num)->where('camp_num','=', $last_camp)->where('nick_name_id','=',$data['nick_name'])->where('end', '=', 0)->get();
                            $support[0]->end = time();
                            $support[0]->save();
                        }
                        
                        $data['camp_num'] = $cmp->camp_num;
                        $supportTopic = new Support();
                        $supportTopic->topic_num = $cmp->topic_num;
                        $supportTopic->nick_name_id = $data['nick_name'];
                        $supportTopic->delegate_nick_name_id = $data['delegate_nick_name_id'];
                        $supportTopic->start = time();
                        $supportTopic->camp_num = $cmp->camp_num;
                        $supportTopic->support_order = $cmp->support_order;
                        $supportTopic->save();   

                        /* clear the existing session for the topic to get updated support count */
                        session()->forget("topic-support-{$cmp->topic_num}");
                        session()->forget("topic-support-nickname-{$cmp->topic_num}");
                        session()->forget("topic-support-tree-{$cmp->topic_num}");
                    }
                 }                
            }
            if ($last_camp == $data['camp_num']) {
                Session::flash('confirm', "samecamp");
            }
           /* remove delegate support if user is support directly */
             if(isset($data['delegated']) && count($data['delegated']) > 0 && $data['delegate_nick_name_id'] == 0){
                foreach($data['delegated'] as $k=>$d){
                    if($d !=0){
                        $support = Support::where('topic_num', $topic_num)->where('camp_num','=', $k)->where('nick_name_id','=',$data['nick_name'])->where('delegate_nick_name_id','=',$d)->where('end', '=', 0)->get();
                        if($support && count($support)> 0 ){
                            $support[0]->end = time();
                            $support[0]->save();  
                        }
                    }
                }
               
             }
            /* Send delegated support email to the direct supporter and all parent  and to subscriber*/
            if (isset($data['delegate_nick_name_id']) && $data['delegate_nick_name_id'] != 0) {
                $parentUser = Nickname::getUserByNickName($data['delegate_nick_name_id']);
                $nickName = Nickname::getNickName($data['nick_name']);
                $topic = Camp::getAgreementTopic($data['topic_num'],['nofilter'=>true]);
                $camp = Camp::where('topic_num', $data['topic_num'])->where('camp_num', '=', $data['camp_num'])->where('go_live_time', '<=', time())->latest('submit_time')->first();
                $result['topic_num'] = $data['topic_num'];
                $result['camp_num'] = $data['camp_num'];
                $result['nick_name'] = $nickName->nick_name;
                $result['object'] = $topic->topic_name . " / " . $camp->camp_name;
                $result['support_camp'] = $camp->camp_name;
                $result['subject'] = $nickName->nick_name . " has just delegated their support to you.";
                $link = \App\Model\Camp::getTopicCampUrl($data['topic_num'],$data['camp_num']);
                $subscribers = Camp::getCampSubscribers($data['topic_num'], $data['camp_num']);
                $directSupporter = Support::getAllDirectSupporters($data['topic_num'], $data['camp_num']);
                $this->mailParentDelegetedUser($data,$link,$result,$subscribers);
                $result['subject'] = $nickName->nick_name . " has just delegated their support to " . $parentUser->first_name . " " . $parentUser->last_name;
                $result['delegated_user'] = $parentUser->first_name . " " . $parentUser->last_name;
                $this->mailSubscribersAndSupporters($directSupporter,$subscribers, $link, $result);
            }
            Session::flash('success', "Your support update has been submitted successfully.");
            return redirect(\App\Model\Camp::getTopicCampUrl($data['topic_num'],session('campnum')));
        } else {
            return redirect()->route('login');
        }
    }

    private function mailParentDelegetedUser($data,$link,$dataObject,$subscribers){
        $parentUser = Nickname::getUserByNickName($data['delegate_nick_name_id']);
        $topic = \App\Model\Topic::where('topic_num', '=', $data['topic_num'])->latest('submit_time')->get();
        $topic_name_space_id = isset($topic[0]) ? $topic[0]->namespace_id : 1;
        $nickName = \App\Model\Nickname::find($data['nick_name']);
        $supported_camp = $nickName->getSupportCampList($topic_name_space_id,['nofilter'=>true]);
        $supported_camp_list = $nickName->getSupportCampListNamesEmail($supported_camp, $data['topic_num'],$data['camp_num']);
        $dataObject['support_list'] = $supported_camp_list;
        $ifalsoSubscriber = Camp::checkifSubscriber($subscribers, $parentUser);
        if ($ifalsoSubscriber) {
            $dataObject['also_subscriber'] = 1;
            $dataObject['sub_support_list'] = Camp::getSubscriptionList($parentUser->id, $data['topic_num'],$data['camp_num']);
        }
         $receiver = (config('app.env') == "production") ? $parentUser->email : config('app.admin_email');
         try{

         Mail::to($receiver)->bcc(config('app.admin_bcc'))->send(new NewDelegatedSupporterMail($parentUser, $link, $dataObject));
         }catch(\Swift_TransportException $e){
                        throw new \Swift_TransportException($e);
                        //$response = $e->getMessage() ;
                    } 
             
    }

    private function mailSubscribersAndSupporters($directSupporter, $subscribers, $link, $dataObject)
    {
        $alreadyMailed = [];
        foreach ($directSupporter as $supporter) {
             $supportData = $dataObject;
            $user = Nickname::getUserByNickName($supporter->nick_name_id);
            $alreadyMailed[] = $user->id;
            $topic = \App\Model\Topic::where('topic_num', '=', $supportData['topic_num'])->latest('submit_time')->get();
            $topic_name_space_id = isset($topic[0]) ? $topic[0]->namespace_id : 1;
            $nickName = \App\Model\Nickname::find($supporter->nick_name_id);
            $supported_camp = $nickName->getSupportCampList($topic_name_space_id,['nofilter'=>true]);
            $supported_camp_list = $nickName->getSupportCampListNamesEmail($supported_camp, $supportData['topic_num'],$supportData['camp_num']);
             $supportData['support_list'] = $supported_camp_list;
            $ifalsoSubscriber = Camp::checkifSubscriber($subscribers, $user);
            if ($ifalsoSubscriber) {
                $supportData['also_subscriber'] = 1;
                $supportData['sub_support_list'] = Camp::getSubscriptionList($user->id, $supportData['topic_num'],$supportData['camp_num']);
            }

            $receiver = (config('app.env') == "production" || config('app.env') == "staging") ? $user->email : config('app.admin_email');
            try{

            Mail::to($receiver)->bcc(config('app.admin_bcc'))->send(new NewDelegatedSupporterMail($user, $link, $supportData));
            }catch(\Swift_TransportException $e){
                        throw new \Swift_TransportException($e);
            } 
        }
        
        foreach ($subscribers as $usr) {
            $subscriberData = $dataObject;
            $userSub = \App\User::find($usr);
            if (!in_array($userSub->id, $alreadyMailed, TRUE)) {
                $alreadyMailed[] = $userSub->id;
                $subscriptions_list = Camp::getSubscriptionList($userSub->id, $subscriberData['topic_num'],$subscriberData['camp_num']);
                $subscriberData['support_list'] = $subscriptions_list;
               $receiver = (config('app.env') == "production" || config('app.env') == "staging") ? $userSub->email : config('app.admin_email');
                $subscriberData['subscriber'] = 1;
                try{

                Mail::to($receiver)->bcc(config('app.admin_bcc'))->send(new NewDelegatedSupporterMail($userSub, $link, $subscriberData));
                }catch(\Swift_TransportException $e){
                    throw new \Swift_TransportException($e);
                } 
            }
        }
        return;
    }

    private function mailSubscribers($subscribers, $link, $data, $alreadyMailed)
    {
        foreach ($subscribers as $user) {
            $user = \App\User::find($user);
            if (!in_array($user->id, $alreadyMailed, TRUE)) {
                $receiver = (config('app.env') == "production" || config('app.env') == "staging") ? $user->email : config('app.admin_email');
                try{

                Mail::to($receiver)->bcc(config('app.admin_bcc'))->send(new NewDelegatedSupporterMail($user, $link, $data));
                }catch(\Swift_TransportException $e){
                       throw new \Swift_TransportException($e);// $response = $e->getMessage() ;
                    } 
            }
        }
        return;
    }

    private function mailDirectSupporters($directSupporter, $link, $data)
    {
        foreach ($directSupporter as $supporter) {
            $user = Nickname::getUserByNickName($supporter->nick_name_id);
            $receiver = (config('app.env') == "production" || config('app.env') == "staging") ? $user->email : config('app.admin_email');
            try{

            Mail::to($receiver)->bcc(config('app.admin_bcc'))->send(new NewDelegatedSupporterMail($user, $link, $data));
            }catch(\Swift_TransportException $e){
                       throw new \Swift_TransportException($e);// $response = $e->getMessage() ;
                    } 
        }
        return;
    }

    private function emailForSupportAdded($data){
            $parentUser = Nickname::getUserByNickName($data['nick_name']);
            $nickName = Nickname::getNickName($data['nick_name']);
            $topic = Camp::getAgreementTopic($data['topic_num'],['nofilter'=>true]);
            $camp = Camp::where('topic_num', $data['topic_num'])->where('camp_num', '=', $data['camp_num'])->where('go_live_time', '<=', time())->latest('submit_time')->first();
        
            $result['topic_num'] = $data['topic_num'];
            $result['camp_num'] = $data['camp_num'];
            $result['nick_name'] = $nickName->nick_name;
            $result['object'] = $topic->topic_name ." / ".$camp->camp_name;
            $result['support_camp'] = $camp->camp_name;
            $result['subject'] = $nickName->nick_name . " has added their support to ".$result['object'].".";
            $link = \App\Model\Camp::getTopicCampUrl($data['topic_num'],$data['camp_num']);
            $subscribers = Camp::getCampSubscribers($data['topic_num'], $data['camp_num']);
            $directSupporter = Support::getAllDirectSupporters($data['topic_num'], $data['camp_num']);
            $result['support_added'] = 1;
            $this->mailSubscribersAndSupporters($directSupporter,$subscribers, $link, $result);
            
    }

    private function emailForSupportDeleted($data){
            $parentUser = null;
            $result['delegate_support_deleted'] = 0;
            if(isset($data['delegate_nick_name_id']) && $data['delegate_nick_name_id']!=0){
                $parentUser = Nickname::getUserByNickName($data['delegate_nick_name_id']);
                $result['delegate_support_deleted'] = 1;
                $result['delegated_user'] = $parentUser->first_name . " " . $parentUser->last_name;
            }        
           $nickName = Nickname::getNickName($data['nick_name']);
            $topic = Camp::getAgreementTopic($data['topic_num'],['nofilter'=>true]);
            $camp = Camp::where('topic_num', $data['topic_num'])->where('camp_num', '=', $data['camp_num'])->where('go_live_time', '<=', time())->latest('submit_time')->first();

            $result['topic_num'] = $data['topic_num'];
            $result['camp_num'] = $data['camp_num'];
            $result['nick_name'] = $nickName->nick_name;
            $result['object'] = $topic->topic_name ." / ".$camp->camp_name;
            $result['support_camp'] = $camp->camp_name;
            $result['subject'] = $nickName->nick_name . " has removed their support from ".$result['object'].".";
            if($parentUser){
                $result['subject'] = $nickName->nick_name . " has removed their delegated support from ". $parentUser->first_name . " " . $parentUser->last_name." in ".$result['object'].".";
            }
           
            $link = \App\Model\Camp::getTopicCampUrl($data['topic_num'],$data['camp_num']);
            $deletedSupport = Support::where('topic_num', $data['topic_num'])
                ->whereIn('nick_name_id', [$data['nick_name']])
                ->orderBy('end', 'DESC')
                ->get();

            $subscribers = Camp::getCampSubscribers($data['topic_num'], $data['camp_num']);
            $directSupporter = Support::getAllDirectSupporters($data['topic_num'], $data['camp_num']);
            $supportsDirect = array_push($directSupporter,$deletedSupport[0]);
            $result['support_deleted'] = 1;
            $this->mailSubscribersAndSupporters($directSupporter,$subscribers, $link, $result);   
    }

    public function delete_support(Request $request)
    {
        
        $id = Auth::user()->id;
        $input = $request->all();
        $support_id = (isset($input['support_id'])) ? $input['support_id'] : 0;
        $topic_num = (isset($input['topic_num'])) ? $input['topic_num'] : 0;
        $nick_name_id = (isset($input['nick_name_id'])) ? $input['nick_name_id'] : 0;
        $delegate_nick_name_id = (isset($input['delegate_nick_name_id'])) ? $input['delegate_nick_name_id'] : 0;

        if ($id && $support_id && $topic_num) {
             $as_of_time = time();
             /** removing delegated support #702 **/
            if($delegate_nick_name_id !=0){
                $currentDeglegateSupport = Support::where('topic_num', $topic_num)->where('nick_name_id','=',$nick_name_id)->where('delegate_nick_name_id',$delegate_nick_name_id)->whereRaw("(start < $as_of_time) and ((end = 0) or (end > $as_of_time))")->get();
                if(isset($currentDeglegateSupport) && count($currentDeglegateSupport) > 0){
                    foreach($currentDeglegateSupport as $spp){
                        $input['camp_num'] = $spp->camp_num;
                        $input['nick_name'] = $spp->nick_name_id;
                        $input['delegate_nick_name_id'] = $spp->delegate_nick_name_id;
                        $input['topic_num'] = $topic_num;
                        $currentSupportOrder = $spp->support_order;
                        $remaingSupportWithHighOrder = Support::where('topic_num', $topic_num)
                            ->whereIn('nick_name_id', [$spp->nick_name_id])
                            ->whereRaw("(start < $as_of_time) and ((end = 0) or (end > $as_of_time))")
                            ->where('support_order', '>', $spp->support_order)
                            ->orderBy('support_order', 'ASC')
                            ->get();
                        $spp->end = time();
                        $spp->save();
                       foreach ($remaingSupportWithHighOrder as $support) {
                            $support->support_order = $currentSupportOrder;
                            $support->save();
                            $currentSupportOrder++;
                        }
                        session()->forget("topic-support-$spp->topic_num");
                        session()->forget("topic-support-nickname-$spp->topic_num");
                        session()->forget("topic-support-tree-$spp->topic_num");
                        $this->emailForSupportDeleted($input);// sending email for removed support for delegated user
                    }
                }
                Session::flash('success', "Your delegated support has been removed successfully.");
                
            }else{
                $currentSupport = Support::where('support_id', $support_id);
                $currentSupportRec = $currentSupport->first();
                $input['camp_num'] = $currentSupportRec->camp_num;
                $input['nick_name'] = $currentSupportRec->nick_name_id;
                $input['topic_num'] = $topic_num;
                $currentSupportOrder = $currentSupportRec->support_order;
                $remaingSupportWithHighOrder = Support::where('topic_num', $topic_num)
                    //->where('delegate_nick_name_id',0)
                    ->whereIn('nick_name_id', [$currentSupportRec->nick_name_id])
                    ->whereRaw("(start < $as_of_time) and ((end = 0) or (end > $as_of_time))")
                    ->where('support_order', '>', $currentSupportRec->support_order)
                    ->orderBy('support_order', 'ASC')
                    ->get(); 

                if ($currentSupport->update(array('end' => time()))) {

                    if(count($remaingSupportWithHighOrder) == 0){ //By Reena Talentelgia #790 pushing up hierachy of deleagtes
                        $directDelegates = Support::where('topic_num', $topic_num)->where('camp_num','=', $currentSupportRec->camp_num)->where('delegate_nick_name_id', $nick_name_id)->where('end', '=', 0)->get();
                        Support::where('topic_num', $topic_num)->where('camp_num','=', $currentSupportRec->camp_num)->where('delegate_nick_name_id', $nick_name_id)->where('end', '=', 0)
                            ->update(['delegate_nick_name_id' => 0]);
                        //mail
                        //$this->mailToDelegatesOnPushingUpHierachy($directDelegates,$topic_num,$currentSupportRec->camp_num,$nick_name_id);
                    }else{
                        foreach ($remaingSupportWithHighOrder as $support) {
                            $support->support_order = $currentSupportOrder;
                            $support->save();
                            $currentSupportOrder++;                 
                        }
                        $this->deleteDelegateSupport($topic_num,$currentSupportRec->camp_num,$nick_name_id,$remaingSupportWithHighOrder,$currentSupportRec->support_order);
    
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
            }
           
            
            return redirect()->back();
        }
        Session::flash('error', "Invalid access.");
        return redirect()->back();
    }

    public function algo(Request $request)
    {
        $user = User::find(Auth::user()->id);
        return view('settings.preferences', compact('user'));
    }

    public function postAlgo(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->default_algo = $request->input('default_algo');
        $user->save();

        session(['defaultAlgo' => $user->default_algo]);
        Session::flash('success', "Your default algorithm preference updated successfully.");
        return redirect()->back();
    }

    public function supportReorder(Request $request)
    {
        $data = $request->only(['positions', 'topicnum']);
        if (isset($data['positions']) && !empty($data['positions'])) {
            foreach ($data['positions'] as $position => $support_id) {
                /** By Reena Nalwa Talentelgia #749 II part (re-order) */ 
                $support = Support::where('support_id', $support_id)->first();
                $supportOrder = $position + 1;                
                $this->reorderDelegateSupport($support->camp_num,$support->topic_num,$support->nick_name_id, $supportOrder);
                /** ends */
                Support::where('support_id', $support_id)->update(array('support_order' => $position + 1));
            }
            $topic_num = $data['topicnum'];
            session()->forget("topic-support-$topic_num");
            session()->forget("topic-support-nickname-$topic_num");
            session()->forget("topic-support-tree-$topic_num");
        }
    }

    public function getChangePassword()
    {
        return view('settings.changepassword', compact('user'));
    }

    public function postChangePassword(Request $request)
    {
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
  private function link_exists($provider,$data){
        $returnArr = [];
        foreach ($data as $key => $value) {
            if($value->provider == $provider){
                $returnArr = $value;
            }
        }
        return $returnArr;
    }
    function sociallinks(){
        if(Auth::check()){
            $providers = ['google','facebook','github','twitter','linkedin'];
            $user = Auth::user();
            $socialdata = []; 
            $social_data = SocialUser::where('user_id','=',$user->id)->get();
            if(count($social_data) > 0){
                foreach($providers as $key=>$d){
                    $data_exist = $this->link_exists($d,$social_data);
                    if($data_exist && isset($data_exist->provider)){
                         $socialdata[$d]=$data_exist;   
                    }else{
                         $socialdata[$d]=['provider'=>$d];   
                    }
                }
            }else{
                
                foreach($providers as $key=>$d){
                    $socialdata[$d]=['provider'=>$d];   
                } 
            }
            return view('settings.sociallinks',['sociallinks'=>$socialdata,'providers'=>$providers]);
        }else{
            return redirect()->route('login');
        }
        
        
    }
    function blockchain(){
        if(Auth::check()){
            $user = Auth::user();
        }
        $addresses = EtherAddresses::where('user_id', '=', $user->id)->get();

        $api_key = '0d4a2732eca64e71a1be52c3a750aaa4';                      // Project Key
        $ether_url = 'https://mainnet.infura.io/v3/' + $api_key;            // Ether Url

        foreach ($addresses as $key=>$ether) {                                       // If users has multiple addresses

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://mainnet.infura.io/v3/0d4a2732eca64e71a1be52c3a750aaa4",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "{\"jsonrpc\":\"2.0\",\"method\":\"eth_getBalance\",\"params\": [\"$ether->address\", \"latest\"],\"id\":1}",
                CURLOPT_HTTPHEADER => array(
                  "Accept-Encoding: gzip, deflate",
                  "Cache-Control: no-cache",
                  "Connection: keep-alive",
                  "Content-Type: application/json",
                  "Host: mainnet.infura.io"
                ),
              ));

            $curl_response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return 0;
            } 
            else {
                $curl_result_obj = json_decode($curl_response);
                $balance = $curl_result_obj->result;
                // $total_ethers += (hexdec($balance)/1000000000000000000);       // Convert Ether to Wei
                $addresses[$key]->balance = (hexdec($balance)/1000000000000000000); 
            }
        }
        return view('settings.blockchain', ['addresses' => $addresses]);
    }

    function postSaveEtherAddress(Request $request)
    {
        $id = Auth::user()->id;
        if ($id) {
            $messages = [
                'name.required' => 'The name field is required.',
                'address.required' => 'The address field is required.',
                'balance.required' => 'The balance field is required.',
            ];


            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'address' => 'required|unique:ether_address',
                'balance' => 'required',
            ], $messages);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $data = $request->only(['name', 'address', 'balance']);
            try {
                $address = new EtherAddresses();
                $address->user_id = $id;
                $address->name = $data['name'];
                $address->address = $data['address'];
                $address->balance = $data['balance'];
                $address->save();
                Session::flash('success', "Ether Address saved successfully.");
                return redirect()->back();
            } catch (Exception $e) {
                Session::flash('error', "Error saving in ehter address.");
                return redirect()->back();
            }
        } else {
            return redirect()->route('login');
        }
    }

    /**
     * By Reena Nalwa
     * Talentelgia
     * #790 Re-order delegates including all Sub-level
     */
    public function reorderDelegateSupport($campNum,$topicNum,$nickNameId,$supportOrder){        
        Support::where('camp_num', $campNum)->where('topic_num', $topicNum)->where('delegate_nick_name_id', $nickNameId)->where('end', '=', 0)->update(array('support_order' => $supportOrder));
        $subLevelDelegates = Support::where('camp_num', $campNum)->where('topic_num', $topicNum)->where('delegate_nick_name_id', $nickNameId)->where('end', '=', 0)->get();
        if(count($subLevelDelegates) > 0){
            foreach($subLevelDelegates as $delegates){
                $this->reorderDelegateSupport($campNum,$topicNum,$delegates->nick_name_id,$supportOrder);
            }
        }
        return;
    }

    /**
     * By Reena Nalwa
     * Talentelgia
     * #790 Add delegates support including all Sub-level
     */
    public function addDelegatedSupport($myDelegator,$topic_num,$camp_num,$support_order,$delegatedTo){
        foreach($myDelegator as $delegator){
            $supportTopic = new Support();
            $supportTopic->topic_num = $topic_num;
            $supportTopic->nick_name_id = $delegator->nick_name_id;
            $supportTopic->delegate_nick_name_id = $delegatedTo;
            $supportTopic->start = time();
            $supportTopic->camp_num = $camp_num;
            $supportTopic->support_order = $support_order;
            $supportTopic->save();

            //get sublevel of delgates
            $userNicknames = Nickname::personNicknameArray($delegator->nick_name_id);
            $subLevelDelegates = Support::where('topic_num', $topic_num)->whereIn('delegate_nick_name_id', $userNicknames)->where('end', '=', 0)->groupBy('nick_name_id')->get();
            if(count($subLevelDelegates) > 0){
                $this->addDelegatedSupport($subLevelDelegates,$topic_num,$camp_num,$support_order,$delegator->nick_name_id);
            }
        }
        return;
    }

    /**
     * Reena Nalwa
     * Talentelgia
     * #790 delegates & sub-level delegates
     * Delete  All delegates support including all sub-levels
     */

    public function deleteDelegateSupport($topic_num,$camp_num,$delegatedTo,$remaingSupportWithHighOrder,$currentSupportOrder){
        $orderRestart = $currentSupportOrder;
        $subLevelDelegates = Support::where('topic_num', $topic_num)->where('delegate_nick_name_id', $delegatedTo)->where('end', '=', 0)->get();
        Support::where('topic_num', $topic_num) 
                ->where('camp_num',$camp_num)
                ->where('delegate_nick_name_id','=',$delegatedTo)
                ->update(array('end' => time()));                          
        foreach ($remaingSupportWithHighOrder as $support) {
            Support::where('topic_num', $topic_num) 
                ->where('camp_num',$support->camp_num)
                ->where('delegate_nick_name_id','=',$delegatedTo)
                ->update(array('support_order' => $orderRestart));
            $orderRestart++;
        }
        if(count($subLevelDelegates) > 0){
            foreach($subLevelDelegates as $delegates){
                $this->deleteDelegateSupport($topic_num,$camp_num,$delegates->nick_name_id,$remaingSupportWithHighOrder,$currentSupportOrder);
            }
        }
        return;
    }

    public function mailToDelegatesOnPushingUpHierachy($directSupporter,$topicNum,$campNum,$nick_name_id){
        $to = [];
        $as_of_time = time();
        $topic = Camp::getAgreementTopic($topicNum,['nofilter'=>true]);
        $camp = Camp::where('topic_num', $topicNum)->where('camp_num', '=', $campNum)->where('go_live_time', '<=', time())->latest('submit_time')->first();

        $data['topic_num'] = $topicNum;
        $data['camp_num'] = $campNum;
        $data['nick_name'] = $nick_name_id;
        $data['object'] = $topic->topic_name ." / ".$camp->camp_name;
        $data['camp-name'] = $camp->camp_name;
        $data['subject'] ="You have been  assigned as a direct supporter of ".$result['object'].".";
        $data['link'] = \App\Model\Camp::getTopicCampUrl($topicNum,$data['camp_num']);        
        foreach ($directSupporter as $supporter) {
            $user = Nickname::getUserByNickName($supporter->nick_name_id);
            $receiver = (config('app.env') == "production" || config('app.env') == "staging") ? $user->email : config('app.admin_email');
            array_push($to,$receiver);
        }
        echo "<pre>"; print_r($data); 
        echo "<pre>"; print_r($to);
        exit;

        try{
        Mail::to($to)->bcc(config('app.admin_bcc'))->send(new PushingUpDelegatesHierachyMail($link, $data));
        }catch(\Swift_TransportException $e){
                    throw new \Swift_TransportException($e);// $response = $e->getMessage() ;
        } 
    }
}
