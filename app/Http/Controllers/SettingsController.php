<?php

namespace App\Http\Controllers;

use Hash;
use Cookie;
use App\User;
use App\Model\Camp;
use App\Model\Topic;
use App\Model\Support;
use App\Model\Nickname;
use App\Library\General;
use App\Model\SocialUser;
use App\Mail\PhoneOTPMail;
use App\Model\TopicSupport;
use Illuminate\Http\Request;
use App\Model\EtherAddresses;
use App\Jobs\CanonizerService;
use App\Model\SupportInstance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewDelegatedSupporterMail;
use App\Mail\PromotedDelegatesMail;
use App\Mail\PromotedDirectSupporterMail;
use App\Mail\SupportRemovedMail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

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

    /**
     * Manage or directly join support page
     */
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
           // echo $delegate_nick_name_id; exit;
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
            $nicknames = Nickname::topicCampNicknameUsed($topicnum,$campnum,$encode);
            $userNickname = Nickname::personNicknameArray();
            $confirm_support = 0;
            $alreadySupport = Support::where('topic_num', $topicnum)->where('camp_num', $campnum)->where('end', '=', 0)->whereIn('nick_name_id', $userNickname)->get();
            if ($alreadySupport->count() > 0) {
                if($alreadySupport[0]->delegate_nick_name_id!=0){
                    $nickName = Nickname::where('id',$alreadySupport[0]->delegate_nick_name_id)->first();
                    $userFromNickname = $nickName->getUser();
                    Session::flash('warningDelegate', "You have already delegated your support for this camp to user ".$nickName->nick_name.". If you continue your delegated support will be removed.");

                }
            }
            /** #1037 */
            $delegateSupportInTopic = Support::where('topic_num', $topicnum)->where('end', '=', 0)->where('delegate_nick_name_id','!=',0)->whereIn('nick_name_id', $userNickname)->get();
            if ($delegateSupportInTopic->count() > 0) {
                $nickName = Nickname::where('id',$delegateSupportInTopic[0]->delegate_nick_name_id)->first();
                $userFromNickname = $nickName->getUser(); 
                Session::flash('warning', "You have delegated your support to user ".$nickName->nick_name." under this topic. If you continue your delegated support will be removed.");

            }
            /** By Reena Nalwa #974 */
            $alreadyDirectSupported = Support::where('topic_num', $topicnum)->where('end', '=', 0)->where('delegate_nick_name_id', '=', 0)->whereIn('nick_name_id', $userNickname)->pluck('camp_num')->toArray();
            if(count($alreadyDirectSupported) && $delegate_nick_name_id){
                                   
                Session::flash('warning', "You are directly supporting one or more camps under this topic. If you continue your direct support will be removed.");
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
            return view('settings.support', ['parentSupport' => $parentSupport, 'childSupport' => $childSupport, 'userNickname' => $userNickname, 'supportedTopic' => $supportedTopic, 'topic' => $topic, 'nicknames' => $nicknames, 'camp' => $onecamp, 'parentcamp' => $campWithParents, 'delegate_nick_name_id' => $delegate_nick_name_id,'topicData' => $topicData]);
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

            return view('settings.mysupport', ['delegatedNick' => $delegatedNick, 'userNickname' => $userNickname, 'supportedTopic' => $supportedTopic, 'nicknames' => $nicknames]);
        }
    }
    
    /**
     * While adding support to camps from manage support 
     * actions takes place here are:
     * Add, Re-order,Delete
     * Upadte @Reena Nalwa Talentelgia
     */
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

            /** IN case of delegated support check for any direct support and remove them */
            $anyDelegator = Support::where('topic_num', $data['topic_num'])->whereIn('delegate_nick_name_id', [$data['nick_name']])->where('end', '=', 0)->groupBy('nick_name_id')->get(); //#1088
            if(isset($data['delegate_nick_name_id']) && $data['delegate_nick_name_id']){
                $data = $this->removeDirectSupport($data);
            }
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
            /** check point 1 if removing support from one camp and adding in another 
             * in that case delegate should not promted it should simply shifted with DS
            */
            $promoteDelegate = true;
            if(isset($data['support_order']) && count($data['support_order']) > 0){
                $promoteDelegate = false;
            }
            /** check point 2 if all suppot removed  */
            $ifSupportLeft = true;
            if(isset($data['removed_camp']) && count($mysupports) == count($data['removed_camp'])){
                $ifSupportLeft = false;
            }
            if (isset($mysupports) && count($mysupports) > 0 && isset($data['removed_camp']) && count($data['removed_camp']) > 0) {
               foreach ($mysupports as $singleSupport) { 
                    if(in_array($singleSupport->camp_num,$data['removed_camp'])){
                        $this->removeSupport($topic_num,$singleSupport->camp_num,$singleSupport->nick_name_id,'',$singleSupport->support_order,$promoteDelegate,$ifSupportLeft);
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
                Session::save();
                /* send support added mail to all supporter and subscribers */
                if($newcamp_mail_flag){
                    $this->emailForSupportAdded($data);   
                }
            }else if (isset($data['support_order']) && isset($data['delegate_nick_name_id']) && $data['delegate_nick_name_id'] == 0 ) {
               foreach ($data['support_order'] as $camp_num => $support_order) {
                    $last_camp = $camp_num;
                    if(!in_array($camp_num,$mysupportArray)) {
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
                        $support = Support::where('topic_num', $topic_num)->where('camp_num','=', $camp_num)->where('nick_name_id','=',$data['nick_name'])->where('end', '=', 0)->first();
                        if(!empty($support)){
                            $support->support_order = $support_order;
                            $support->save();
                            /** By Reena Nalwa Talentelgia 11th Oct #749 II Part(Re-ordering Case)  And #790 All sub-level delegates */
                            $this->reorderDelegateSupport($camp_num,$topic_num,$data['nick_name'],$support_order);
                        }
                    }
                    /* clear the existing session for the topic to get updated support count */
                    session()->forget("topic-support-{$topic_num}");
                    session()->forget("topic-support-nickname-{$topic_num}");
                    session()->forget("topic-support-tree-{$topic_num}");
                    Session::save();
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

                        //all delegator of $data['nick_name] should also assigned with this support #1088
                        if(isset($anyDelegator) && !empty($anyDelegator))
                        {
                            $this->addSupportToDelegates($anyDelegator,$cmp,$data['nick_name']);
                        }
                        /* clear the existing session for the topic to get updated support count */
                        session()->forget("topic-support-{$cmp->topic_num}");
                        session()->forget("topic-support-nickname-{$cmp->topic_num}");
                        session()->forget("topic-support-tree-{$cmp->topic_num}");
                        Session::save();
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
                // $parentUser = Nickname::getUserByNickName($data['delegate_nick_name_id']);
                $parentUserNickName = Nickname::getNickName($data['delegate_nick_name_id']);
                $nickName = Nickname::getNickName($data['nick_name']);
                $topic = Camp::getAgreementTopic($data['topic_num'],['nofilter'=>true]);
                $camp = Camp::where('topic_num', $data['topic_num'])->where('camp_num', '=', $data['camp_num'])->where('go_live_time', '<=', time())->latest('submit_time')->first();
                $result['namespace_id'] = (isset($topic->namespace_id) && $topic->namespace_id)  ?  $topic->namespace_id : 1;
                $result['topic_num'] = $data['topic_num'];
                $result['camp_num'] = $data['camp_num'];
                $result['nick_name'] = $nickName->nick_name; 
                $result['nick_name_id'] = $nickName->id; 
               // $result['object'] = $topic->topic_name . " / " . $camp->camp_name;
                $result['object'] = $topic->topic_name; // #954 only topic name should be mentioned
                $result['support_camp'] = $camp->camp_name;
                $result['subject'] = $nickName->nick_name . " has just delegated their support to you.";
                //$link = \App\Model\Camp::getTopicCampUrl($data['topic_num'],$data['camp_num']);
                $link = \App\Model\Camp::getTopicCampUrl($data['topic_num'],1); //#954
                $subscribers = Camp::getCampSubscribers($data['topic_num'], $data['camp_num']);
                $directSupporter = Support::getAllDirectSupporters($data['topic_num'], $data['camp_num']);
                //check direct supporter or not mail
                $delegate_nick_name_id = $data['delegate_nick_name_id'];
                $ifalsoDirectSupporter = Camp::checkifDirectSupporter($directSupporter, $delegate_nick_name_id);
                if(!$ifalsoDirectSupporter){
                    $this->mailParentDelegetedUser($data,$link,$result,$subscribers);
                }
                $result['subject'] = $nickName->nick_name . " has just delegated their support to " . $parentUserNickName->nick_name;
                $result['delegated_user'] = $parentUserNickName->nick_name;
                $this->mailSubscribersAndSupporters($directSupporter,$subscribers, $link, $result);
            }
            Session::save();
            Session::flash('success', "Your support update has been submitted successfully.");

            $topic = Topic::where('topic_num', $topic_num)->get()->last();            
            $this->dispatchJob($topic);
            
            return redirect(\App\Model\Camp::getTopicCampUrl($data['topic_num'],session('campnum')));            
        } else {
            return redirect()->route('login');
        }
    }

    private function mailParentDelegetedUser($data,$link,$dataObject,$subscribers){ 
        //mail return
        //return;
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
         $receiver = (config('app.env') == "production" || config('app.env') == "staging") ? $parentUser->email : config('app.admin_email');
         try{
            Mail::to($receiver)->bcc(config('app.admin_bcc'))->send(new NewDelegatedSupporterMail($parentUser, $link, $dataObject));
         }catch(\Swift_TransportException $e) {
            throw new \Swift_TransportException($e);
            //$response = $e->getMessage() ;
        } 
             
    }

    private function mailSubscribersAndSupporters($directSupporter, $subscribers, $link, $dataObject,$campList=[])
    {   //mail return
        //return;
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
                $subscriptions_list = Camp::getSubscriptionList($userSub->id, $subscriberData['topic_num']);
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
    {  //mail return
        //return;
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
    {   //mail return
        //return;
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

    private function emailForSupportAdded($data)
    {
        //mail return
       // return;
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
        //mail return
        //return;
             //$parentUser = null;
            $parentUserNickName = null;
            $result['delegate_support_deleted'] = 0;
            if(isset($data['delegate_nick_name_id']) && $data['delegate_nick_name_id']!=0){
                // $parentUser = Nickname::getUserByNickName($data['delegate_nick_name_id']);
                $parentUserNickName = Nickname::getNickName($data['delegate_nick_name_id']);
                $result['delegate_support_deleted'] = 1;
                $result['delegated_user'] = $parentUserNickName->nick_name;
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
            if($parentUserNickName){
                $result['subject'] = $nickName->nick_name . " has removed their delegated support from ". $parentUserNickName->nick_name." in ".$result['object'].".";
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
                $this->removeSupport($topic_num,'',$nick_name_id,$delegate_nick_name_id);
                Session::flash('success', "Your delegated support has been removed successfully.");
                
            }else{  // removing direct support
                $currentSupport = Support::where('support_id', $support_id);
                $currentSupportRec = $currentSupport->first();
                $input['camp_num'] = $currentSupportRec->camp_num;
                $input['nick_name'] = $currentSupportRec->nick_name_id;
                $input['topic_num'] = $topic_num;
                $promoteDelegate = true;
                $ifSupportLeft = true; //default, only useful in case od direct supporter
                $prefNo = 1;
                $supportCount = Support::where('topic_num', $topic_num)
                ->whereIn('nick_name_id', [$nick_name_id])
                ->whereRaw("(start < $as_of_time) and ((end = 0) or (end > $as_of_time))")
                ->count();
                if($supportCount == 1){ //which is going to be removed, abanodinig topic completely
                    $ifSupportLeft = false;
                }
                $this->removeSupport($topic_num,$currentSupportRec->camp_num,$nick_name_id,'',$currentSupportRec->support_order,$promoteDelegate,$ifSupportLeft);
                Session::flash('success', "Your support has been removed successfully.");                
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
                'new_password.regex' => 'Password must be at least 8 characters, including at least one digit, one lower case letter and one special character(@,# !,$..)',
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

    /**
     * By Reena Nalwa
     * This action is called from support tree page on Remove your support button
     * #831
     */
    public function remove_support($topicNum,$campNum,$nickNameId){
        $as_of_time = time();
        $support = Support::where('camp_num', $campNum)->where('topic_num', $topicNum)->where('nick_name_id', $nickNameId)->where('end', '=', 0)->first();
        $delegateNickNameId = $support->delegate_nick_name_id;
        $promoteDelegate = true;
        $ifSupportLeft = true; //default, only useful in case od direct supporter
        if(!$delegateNickNameId){
            $supportCount = Support::where('topic_num', $topicNum)
             ->whereIn('nick_name_id', [$nickNameId])
             ->whereRaw("(start < $as_of_time) and ((end = 0) or (end > $as_of_time))")
             ->count();
             if($supportCount == 1){ //which is going to be removed, abanodinig topic completely
                $ifSupportLeft = false;
             }
        }
        $this->removeSupport($topicNum,$campNum,$nickNameId,$delegateNickNameId,$support->support_order,$promoteDelegate,$ifSupportLeft);
        session()->forget("topic-support-$topicNum");
        session()->forget("topic-support-nickname-$topicNum");
        session()->forget("topic-support-tree-$topicNum");
        Session::save();

        $topic = Topic::where('topic_num', $topicNum)->get()->last();            
        $this->dispatchJob($topic);

        return redirect(\App\Model\Camp::getTopicCampUrl($topicNum ,$campNum));

    }
    
    /**
     * By Reena Nalwa 
     * If delegate supporter is removing its support it well end by promoting its delegates to their place
     * If direct supporter is removing its support checks will be impemented:
     * 1. If all support is withdrawn if yes then promot its delegate as direct supporter
     * 2. if not then withdraw support from specified camps only And
     * 3. re-order the preference number for other supported camps AND
     * 4. Same will be done for their delegates tree.
     */
    public function removeSupport($topicNum,$campNum='',$nickNameId,$delegateNickNameId=0,$currentSupportOrder='',$promoteDelegate = true,$ifSupportLeft = true){
        $startSupportOrder = $currentSupportOrder;
        $as_of_time = time();
        if($delegateNickNameId){  //A delegate supporter is removing its support
            $alldirectDelegates = Support::where('topic_num', $topicNum)->where('delegate_nick_name_id', $nickNameId)->where('end', '=', 0)->get();
            $campList = Support::where('topic_num','=',$topicNum)->where('nick_name_id', $nickNameId)->where('end', '=', 0)->pluck('camp_num')->toArray();
            Support::where('topic_num', $topicNum)->where('nick_name_id', $nickNameId)->where('end', '=', 0)->update(array('end' => time()));
            Support::where('topic_num', $topicNum)->where('delegate_nick_name_id', $nickNameId)->where('end', '=', 0)->update(array('delegate_nick_name_id' => $delegateNickNameId));
            session()->forget("topic-support-$topicNum");
            session()->forget("topic-support-nickname-$topicNum");
            session()->forget("topic-support-tree-$topicNum");
            Session::save();
            //email direct supporters and subscriber regarding withdrawl of support, TO BE DSICUSSED
            $this->mailWhenDelegateSupportRemoved($topicNum,$nickNameId,$delegateNickNameId,$campList);
            //send email to promoted delegates
            if(count($alldirectDelegates) > 0){
                $this->notifiyPromotedDelegates($topicNum,$campNum,$nickNameId,$delegateNickNameId,$alldirectDelegates);
            } 
        }else{ // A direct supporter is removing its support  
            //mailData
            $mailData['topic_num'] = $topicNum;
            $mailData['camp_num'] = $campNum;
            $mailData['nick_name'] = $nickNameId;
            $mailData['delegate_nick_name_id'] = $delegateNickNameId;
            $remaingSupportWithHighOrder = Support::where('topic_num', $topicNum)
                //->where('delegate_nick_name_id',0)
                ->whereIn('nick_name_id', [$nickNameId])
                ->whereRaw("(start < $as_of_time) and ((end = 0) or (end > $as_of_time))")
                ->where('support_order', '>', $currentSupportOrder)
                ->orderBy('support_order', 'ASC')
                ->get();     
            $endSupport = Support::where('topic_num', $topicNum)->where('camp_num', $campNum)->where('nick_name_id', $nickNameId)->where('end', '=', 0)->update(array('end' => time()));
            if ($endSupport) {
                if(!$ifSupportLeft && $promoteDelegate){ //Abondoning Topic completely, So promoting DD to its place
                   $alldirectDelegates = Support::where('topic_num', $topicNum)->where('camp_num','=', $campNum)->where('delegate_nick_name_id', $nickNameId)->where('end', '=', 0)->get();
                    if(count($alldirectDelegates) > 0){
                        Support::where('topic_num', $topicNum)->where('camp_num','=', $campNum)->where('delegate_nick_name_id', $nickNameId)->where('end', '=', 0)
                            ->update(['delegate_nick_name_id' => 0]);
                       $this->notifyPromotedDirectSupporter($topicNum,$campNum,$nickNameId,$alldirectDelegates);
                    }
                }else{ 
                    foreach ($remaingSupportWithHighOrder as $support) {
                        $support->support_order = $currentSupportOrder;
                        $support->save();
                        $currentSupportOrder++;                 
                    }
                    $this->deleteDelegateSupport($topicNum,$campNum,$nickNameId,$remaingSupportWithHighOrder,$startSupportOrder);
                }   
            } 
            /* send support deleted mail to all supporter and subscribers */
            $this->emailForSupportDeleted($mailData);
        }
        return;
    }

    public function notifiyPromotedDelegates($topicNum,$campNum='',$nickNameId,$delegateNickNameId,$alldirectDelegates){
        //mail return
        //return;
        $to = [];
        $topic = Camp::getAgreementTopic($topicNum,['nofilter'=>true]);
        $fistChoiceCamp = Support::where('topic_num', $topicNum)->where('nick_name_id', '=', $delegateNickNameId)->where('end', '=', 0)->where('support_order',1)->first();
        $camp = Camp::where('topic_num', $topicNum)->where('camp_num', '=', $fistChoiceCamp->camp_num)->where('go_live_time', '<=', time())->latest('submit_time')->first();
        $promotedFrom = Nickname::getNickName($nickNameId);
        $promotedTo = Nickname::getNickName($delegateNickNameId);       
        $data['topic_num'] = $topicNum;
        $data['camp_num'] = $camp->camp_num;
        $data['promotedFrom'] = $promotedFrom;
        $data['promotedTo'] = $promotedTo;
        $data['topic'] = $topic;
        $data['camp'] = $camp;
        $data['subject'] ="You have been promoted";
        $data['topic_link'] = \App\Model\Camp::getTopicCampUrl($topicNum,1);
        $data['camp_link'] = \App\Model\Camp::getTopicCampUrl($topicNum,$camp->camp_num);        
        foreach ($alldirectDelegates as $supporter) {
            $user = Nickname::getUserByNickName($supporter->nick_name_id);
            $receiver = (config('app.env') == "production" || config('app.env') == "staging") ? $user->email : config('app.admin_email');
            array_push($to,$receiver);
        }
        try{
        Mail::to($to)->bcc(config('app.admin_bcc'))->send(new PromotedDelegatesMail($data));
        }catch(\Swift_TransportException $e){
                    throw new \Swift_TransportException($e);// $response = $e->getMessage() ;
        } 
    }

    /**
     * When Delegates are promoted as Direct supporter
     */
    public function notifyPromotedDirectSupporter($topicNum,$campNum,$nickNameId,$alldirectDelegates){
        //mail return
        //return;
        $to = [];
        $topic = Camp::getAgreementTopic($topicNum,['nofilter'=>true]);
        $camp = Camp::where('topic_num', $topicNum)->where('camp_num', '=', $campNum)->where('go_live_time', '<=', time())->latest('submit_time')->first();
       
        $promotedFrom = Nickname::getNickName($nickNameId);
        $data['topic_num'] = $topicNum;
        $data['camp_num'] = $campNum;
        $data['promotedFrom'] = $promotedFrom;
        $data['topic'] = $topic;
        $data['camp'] = $camp;
        $data['subject'] ="You have been promoted as direct supporter";
        $data['topic_link'] = Camp::getTopicCampUrl($topicNum,1);
        $data['camp_link'] = Camp::getTopicCampUrl($topicNum,$campNum);   
        $data['url_portion'] =  Camp::getSeoBasedUrlPortion($topicNum,$campNum);     
        foreach ($alldirectDelegates as $supporter) {
            $user = Nickname::getUserByNickName($supporter->nick_name_id);
            $receiver = (config('app.env') == "production" || config('app.env') == "staging") ? $user->email : config('app.admin_email');
            array_push($to,$receiver);
        }
        try{
        Mail::to($to)->bcc(config('app.admin_bcc'))->send(new PromotedDirectSupporterMail($data));
        }catch(\Swift_TransportException $e){
                    throw new \Swift_TransportException($e);// $response = $e->getMessage() ;
        } 
    }

    /**
     * Mail direct supporter and subscribers when delegate supporter remove support
     * 
     */
    private function mailWhenDelegateSupportRemoved($topicNum,$nickNameId,$delegateNickNameId,$campList){
        //mail return
        //return;
        $parentUser = null;
        $result['delegate_support_deleted'] = 1;
        $parentUser = Nickname::getNickName($delegateNickNameId);
        $result['delegated_user'] = $parentUser->nick_name;
        $result['delegated_user_id'] = $parentUser->id;             
        $nickName = Nickname::getNickName($nickNameId);
        $topic = Camp::getAgreementTopic($topicNum,['nofilter'=>true]);        
        //$camp = Camp::where('topic_num', $data['topic_num'])->where('camp_num', '=', $data['camp_num'])->where('go_live_time', '<=', time())->latest('submit_time')->first();

        $result['topic_num'] = $topicNum;
        $result['camp_num'] = 1;
        $result['nick_name'] = $nickName->nick_name;
        $result['nick_name_id'] = $nickName->id;
        $result['object'] = $topic->topic_name;       
        $result['subject'] = $nickName->nick_name . " has removed their delegated support from ". $parentUser->nick_name . " in ".$topic->topic_name." topic.";
        $result['topic'] = $topic;
        $link = \App\Model\Camp::getTopicCampUrl($topicNum,1);
        $parentSupport = Support::where('topic_num', $topicNum)
            ->whereIn('nick_name_id', [$delegateNickNameId])
            ->orderBy('end', 'DESC')
            ->first();
        $subscribers = Camp::getCampSubscribers($topicNum);
        $directSupporterInCampList = [];
        $directSupporter = Support::getAllDirectSupporters($topicNum);
        foreach($directSupporter as $support){
            if(in_array($support->camp_num, $campList,TRUE)){  // mail to those who is supporting/subscribing camps from which support is withdrawn
                $directSupporterInCampList[] = $support;
             }
        }
        $this->notifyRemovingDelegateSupporter($nickName, $parentUser,$result);
        if($parentSupport->delegate_nick_name_id != 0){ // mail to delegate user from whome delegate support is withdrawn
           $result['mail_to_parent'] = true;
            $this->notifyRemovingDelegateSupporter($parentUser, $parentUser,$result);
        }
        //$supportsDirect = array_push($directSupporterInCampList,$deletedSupport[0]);       
        $result['support_deleted'] = 1;
        $this->mailSubscribersAndSupporters($directSupporterInCampList,$subscribers, $link, $result,$campList);   
    }

    /**
     * Remove All direct support, if user delegate their support to someone else
     * Also, ends the support of its deleagtes for all the previous supported camps
     */
    private function removeDirectSupport($data){
        $nickNameId = $data['nick_name'];
        $directSupportedCamps = Support::where('topic_num', $data['topic_num'])
            ->whereIn('nick_name_id', [$nickNameId])
            ->where('end', '=',0)
            ->where('delegate_nick_name_id', '=',0)
            ->pluck('camp_num')->toArray();
        foreach($directSupportedCamps as $camp){
           unset($data['support_order'][$camp]);
           unset($data['camp'][$camp]);
           unset($data['delegated'][$camp]);
        }

        Support::where('topic_num', $data['topic_num'])
            ->whereIn('nick_name_id', [$nickNameId])
            ->where('end', '=',0)
            ->where('delegate_nick_name_id', '=',0)->update(['end'=>time()]);

       // end support for all its deleagte as well #1088
       /*Support::where('topic_num', $data['topic_num'])
            ->whereIn('delegate_nick_name_id', [$nickNameId])
            ->where('end', '=',0)->update(['end'=>time()]);*/
        return $data;
    }
  
    private function dispatchJob($topic) {
        $selectedAlgo = 'blind_popularity';
        if(session('defaultAlgo')) {
            $selectedAlgo = session('defaultAlgo');
        }

        $asOf = 'default';
        if(session('asofDefault')) {
            $asOf = session('asofDefault');
        }
        $asOfDefaultDate = time();
        $canonizerServiceData = [
            'topic_num' =>  $topic->topic_num,
            'algorithm' => $selectedAlgo,
            'asOfDate' => $asOfDefaultDate,
            'asOf' => $asOf
        ];
        // Dispact job when create a camp
        CanonizerService::dispatch($canonizerServiceData)
            ->onQueue('canonizer-service')
            ->unique(Topic::class, $topic->id);
    }

    /**
     * Notify delegate user who is removing their support
     * 
     */

    private function notifyRemovingDelegateSupporter($nickName,$parentUser, $data){
        //mail return
        //return
        $user = Nickname::getUserByNickName($nickName->id);    
        //$data['subject'] = $nickName->nick_name . " has removed their delegated support from ". $parentUser->nick_name . " in ".$data['topic']->topic_name." topic."; 
        $data['subject'] = $data['nick_name'] . " has removed their delegated support from ". $parentUser->nick_name . " in ".$data['topic']->topic_name." topic."; 
                
        $link = \App\Model\Camp::getTopicCampUrl($data['topic_num'],1);          
        $receiver = (config('app.env') == "production" || config('app.env') == "staging") ? $user->email : config('app.admin_email');
        try{
            Mail::to($receiver)->bcc(config('app.admin_bcc'))->send(new SupportRemovedMail($user, $link, $data));
        }catch(\Swift_TransportException $e){
                    throw new \Swift_TransportException($e);
        } 
    }

    /**
     * recursive function to change the support 
     * when a supporter delegate support to other
     */
    private function addSupportToDelegates($anyDelegator,$cmp,$delegatedTo){

        foreach($anyDelegator as $delegator){
            //get delegates of this nickname for this topic and reset it first and add new one
            $anyDelegator = Support::where('topic_num', $cmp->topic_num)->whereIn('delegate_nick_name_id', [  $delegator->nick_name_id ])->where('end', '=', 0)->groupBy('nick_name_id')->get(); //#1088
           //ending support of child as well
            Support::where('topic_num',$cmp->topic_num)
            ->where('end', '=',0)
            ->where('delegate_nick_name_id',  '=', $delegatedTo)->update(['end'=>time()]);

            $supportTopic = new Support();
            $supportTopic->topic_num = $cmp->topic_num;
            $supportTopic->nick_name_id = $delegator->nick_name_id;
            $supportTopic->delegate_nick_name_id = $delegatedTo;
            $supportTopic->start = time();
            $supportTopic->camp_num = $cmp->camp_num;
            $supportTopic->support_order = $cmp->support_order;
            $supportTopic->save();

            if(count($anyDelegator) > 0){
                $this->addSupportToDelegates($anyDelegator,$cmp,$delegator->nick_name_id);                
            }
            return;
        }
    }
}


