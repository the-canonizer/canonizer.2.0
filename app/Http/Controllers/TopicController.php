<?php

namespace App\Http\Controllers;

use DB;
use Validator;
use App\Model\Camp;
use App\Model\Topic;
use App\Library\Wiky;
use App\Model\Support;
//use App\Library\Wikiparser\wikiParser;
use App\Model\NewsFeed;
use App\Model\Nickname;
use App\Library\General;
use App\Model\Statement;
use App\Model\Namespaces;
use Illuminate\Http\Request;
use App\Model\ChangeAgreeLog;
use App\Model\NamespaceRequest;
use App\Mail\ThankToSubmitterMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ObjectionToSubmitterMail;
use App\Mail\PurposedToSupportersMail;
use App\Mail\ProposedChangeMail;
use App\Mail\NewDelegatedSupporterMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\RedirectsUsers;

/**
 * TopicController Class Doc Comment
 *
 * @category Class
 * @package  MyPackage
 * @author   Varun Gautam <gautamv16@gmail.com>
 * @license  GNU General Public License     
 * @link     http://varungautam.com
 */
class TopicController extends Controller {

    public function __construct() {
        parent::__construct();
		if(isset($_REQUEST['asof']) && $_REQUEST['asof']!=''){
                session()->put('asofDefault',$_REQUEST['asof']);
            }
            if(isset($_REQUEST['asofdate']) && $_REQUEST['asofdate']!=''){
                session()->put('asofdateDefault',$_REQUEST['asofdate']);
            }
            session()->save();
		
    }

    /**
     * Show the form for creating a new topic.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        session()->forget('asofDefault');
        $namespaces = Namespaces::all();
        $nickNames = Nickname::personNickname();
        return view('topics.create', compact('namespaces', 'nickNames'));
    }

    /**
     * Store a newly created topic,objected topic in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $all = $request->all();
         $validatorArray = ['topic_name' => 'required|max:30|regex:/^[a-zA-Z0-9\s]+$/',
            'namespace' => 'required',
            'create_namespace' => 'required_if:namespace,other|max:100',
            'nick_name' => 'required'
            //'note' => 'required'
        ];

        /**
         * @updated By Talentelgia
         */
        $liveTopicData = Topic::select('topic.*')
                            ->join('camp','camp.topic_num','=','topic.topic_num')
                            ->where('camp.camp_name','=','Agreement')
                            ->where('topic_name', $all['topic_name'])
                            ->where('topic.objector_nick_id',"=",null)
                            ->whereRaw('topic.go_live_time in (select max(go_live_time) from topic where objector_nick_id is null and go_live_time < "' . time() . '" group by topic_num)')
                            ->where('topic.go_live_time', '<=', time())                            
                            ->latest('submit_time')
                            ->first();
        $nonLiveTopicData = Topic::select('topic.*')
                            ->join('camp','camp.topic_num','=','topic.topic_num')
                            ->where('camp.camp_name','=','Agreement')
                            ->where('topic_name', $all['topic_name'])
                            ->where('topic.objector_nick_id',"=",null)
                            ->where('topic.go_live_time',">",time())
                            ->first();
                     
        $message = [

            'topic_name.required' => 'Topic name is required.',
            'topic_name.max' => 'Topic name can not be more than 30 characters.',
            'topic_name.regex' => 'Topic name can only contain space and alphanumeric characters.',
            'namespace.required' => 'Namespace is required.',
            'create_namespace.required_if' => 'The Other Namespace Name field is required when namespace is other.',
            'create_namespace.max' => 'The Other Namespace Name can not be more than 100 characters.',
            'nick_name.required' => 'Nick name is required.',
            'objection_reason.required' => 'Objection reason is required.',
            'objection_reason.max' => 'Objection reason can not be more than 100.'
        ];

        $objection = '';
        if (isset($all['objection']) && $all['objection'] == 1) {
            $objection = 1;
            $validatorArray = ['objection_reason' => 'required|max:100','nick_name' => 'required'
            ];
        }

        $validator = Validator::make($request->all(), $validatorArray, $message);
        if(isset($liveTopicData) && $liveTopicData!=null){
           $validator->after(function ($validator) use ($all,$liveTopicData){  
                if (isset($all['topic_num'])) {  
                    if($liveTopicData->topic_num != $all['topic_num']){
                       $validator->errors()->add('topic_name', 'The topic name has already been taken.');
                    }
                    
                }else{ 
                    if($liveTopicData && isset($liveTopicData['topic_name'])){
                        $validator->errors()->add('topic_name', 'The topic name has already been taken.');
                    }

                }
            }); 
        }
        if(isset($nonLiveTopicData) && $nonLiveTopicData!=null){
           $validator->after(function ($validator) use ($all,$nonLiveTopicData){  
            if (isset($all['topic_num'])) {  
                    if($nonLiveTopicData->topic_num != $all['topic_num']){

                       $validator->errors()->add('topic_name', 'The topic name has already been taken.');
                    }
                    
                }else{ 
                    if($nonLiveTopicData && isset($nonLiveTopicData['topic_name'])){
                        $validator->errors()->add('topic_name', 'The topic name has already been taken.');
                    }

                }
            }); 
        }
        
        
        if ($validator->fails()) {  
            return back()->withErrors($validator->errors())->withInput($request->all());
        }

         DB::beginTransaction();
        $go_live_time = "";
        try {
            $current_time = time();
            $eventtype = "CREATE";
            $topic = new Topic();
            $topic->topic_name = isset($all['topic_name']) ? $all['topic_name'] : "";

            $topic->namespace_id = isset($all['namespace']) ? $all['namespace'] : "";
            $topic->submit_time = $current_time;
            $topic->submitter_nick_id = isset($all['nick_name']) ? $all['nick_name'] : "";
            $topic->go_live_time = $current_time; //strtotime(date('Y-m-d H:i:s', strtotime('+7 days')));
            $topic->language = 'English';
            $topic->note = isset($all['note']) ? $all['note'] : "";
            $topic->grace_period = 1;

            if (isset($all['topic_num'])) {

                $topic->topic_num = $all['topic_num'];
                $eventtype = "UPDATE";
                $message = "Topic change submitted successfully.";
                $nickNames = Nickname::personNicknameArray();

                $ifIamSingleSupporter = Support::ifIamSingleSupporter($all['topic_num'], 0, $nickNames);

                if (!$ifIamSingleSupporter) {
                    //$topic->go_live_time = strtotime(date('Y-m-d H:i:s', strtotime('+7 days')));
                    $topic->go_live_time = strtotime(date('Y-m-d H:i:s', strtotime('+'.config('app.go_live_day_limit').' days')));
                    $go_live_time = $topic->go_live_time;
                    $message = "Topic change submitted successfully.";
                }

                if (isset($all['objection']) && $all['objection'] == 1) {

                    $topic = Topic::where('id', $all['objection_id'])->first();
                    $topic->objector_nick_id = $all['nick_name'];
                    //$topic->submitter_nick_id = $all['submitter'];
                    $topic->object_reason = $all['objection_reason'];
                    $topic->object_time = $current_time;
                    $eventtype = "OBJECTION";
                    $message = "Objection submitted successfully.";
                }

                if (isset($all['topic_update']) && $all['topic_update'] == 1) {

                    $topic = Topic::where('id', $all['topic_id'])->first();
                    $eventtype = "TOPIC_UPDATE";
                    $message = "Updation to changed topic has been made successfully.";
                    $topic->topic_name = isset($all['topic_name']) ? $all['topic_name'] : "";
                    $topic->namespace_id = isset($all['namespace']) ? $all['namespace'] : "";
                    $topic->submitter_nick_id = isset($all['nick_name']) ? $all['nick_name'] : "";
                    $topic->note = isset($all['note']) ? $all['note'] : "";
                }
            } else {
                $message = "Topic created successfully.";
            }

            /* If topic is created then add default support to that topic */
            if ($topic->save()) {

                if ($eventtype == "CREATE") {
                    $supportTopic = new Support();
                    $supportTopic->topic_num = $topic->topic_num;
                    $supportTopic->nick_name_id = $all['nick_name'];
                    $supportTopic->delegate_nick_name_id = 0;
                    $supportTopic->start = $current_time;
                    $supportTopic->camp_num = 1;

                    $supportTopic->support_order = 1;
                    $supportTopic->save();

                    session()->forget("topic-support-{$topic->topic_num}");
                    session()->forget("topic-support-nickname-{$topic->topic_num}");
                    session()->forget("topic-support-tree-{$topic->topic_num}");
                }
            }


            if (isset($all['namespace']) && $all['namespace'] == 'other') { /* Create new namespace request */
                //$topic->submitter_nick_id = $all['submitter'];

                $othernamespace = trim($all['create_namespace'], '/');
                $namespace = new Namespaces();
                $namespace->parent_id = 0;
                $namespace->name = '/' .$othernamespace . '/';
               // $namespace->label = '/' . $othernamespace . '/';
                $namespace->save();

                //update namespace id
                $topic->namespace_id = $namespace->id;
                $topic->update();

                /*
                  $namespace_request = new NamespaceRequest;
                  $namespace_request->user_id = Auth::user()->id;
                  $namespace_request->name = $all['create_namespace'];
                  $namespace_request->topic_num = $topic->topic_num;
                  $namespace_request->save();
                  $topic->namespace_id = 1;
                  $topic->save(); */
            }
            DB::commit();

            Session::flash('success', $message);

            if ($eventtype == "CREATE") {

                // send history link in email
                $link = 'topic-history/' . $topic->topic_num;
				$data['type'] = "topic";
                $data['object'] = $topic->topic_name;
				$data['link'] = \App\Model\Camp::getTopicCampUrl($topic->topic_num,1);	
                try{
                     Mail::to(Auth::user()->email)->bcc(config('app.admin_bcc'))->send(new ThankToSubmitterMail(Auth::user(), $link,$data));
                }catch(\Swift_TransportException $e){
                       throw new \Swift_TransportException($e);
                    }          
            } else if ($eventtype == "OBJECTION") {

                $user = Nickname::getUserByNickName($all['submitter']);
                $liveTopic = Topic::select('topic.*')
                             ->where('topic.topic_num', $topic->topic_num)
                             ->where('topic.objector_nick_id',"=",null)
                             ->latest('topic.submit_time')
                             ->first();
                //$link = 'topic/' . $topic->topic_num . '/1';
                $link = 'topic-history/' . $topic->topic_num;             
                $data['object'] = $liveTopic->topic_name;
                $nickName = Nickname::getNickName($all['nick_name']);
                $data['topic_link'] = \App\Model\Camp::getTopicCampUrl($topic->topic_num,1);  
                $data['type'] = "Topic";
                $data['object'] = $liveTopic->topic_name;
                $data['object_type'] = ""; 
                $data['nick_name'] = $nickName->nick_name;
                $data['forum_link'] = 'forum/' . $topic->topic_num . '-' . $liveTopic->topic_name . '/1/threads';
                $data['subject'] = $data['nick_name'] . " has objected to your proposed change.";
                $data['help_link'] = General::getDealingWithDisagreementUrl();

                $receiver = (config('app.env') == "production" || config('app.env') == "staging") ? $user->email : config('app.admin_email');
                try{
                 Mail::to($receiver)->bcc(config('app.admin_bcc'))->send(new ObjectionToSubmitterMail($user, $link, $data));
                }catch(\Swift_TransportException $e){
                        throw new \Swift_TransportException($e);
                    } 
            } else if ($eventtype == "UPDATE") {
                $directSupporter = Support::getAllDirectSupporters($topic->topic_num);
                $link_history = 'topic-history/' . $topic->topic_num;
                $link = 'topic/' . $topic->topic_num . '/' . $topic->camp_num . '?asof=bydate&asofdate=' . date('Y/m/d H:i:s', $topic->go_live_time);
                $data['object'] = $topic->topic_name;
                $data['go_live_time'] = $topic->go_live_time;
                $data['type'] = 'topic : ';
                $data['typeobject'] = 'topic';
                //$data['note'] = "";
                $data['support_camp'] = "Agreement";
                $data['camp_num'] = 1;
                $data['topic_num'] = $topic->topic_num;
                $nickName = Nickname::getNickName($all['nick_name']);
                $data['nick_name'] = $nickName->nick_name;
                $data['forum_link'] = 'forum/' . $topic->topic_num . '-' . $topic->topic_name . '/1/threads';
                $data['subject'] = "Proposed change to " . $topic->topic_name . " submitted";
                
                #821 : In review topic name is displaying in Proposed change to topic email

                $liveTopic = Topic::select('topic.*')
                    ->where('topic.topic_num', $topic->topic_num)
                    ->where('topic.objector_nick_id',"=",null)
                    ->where('topic.go_live_time',"<",Carbon::now()->timestamp)
                    ->latest('topic.submit_time')
                    ->first();
                if(!empty($liveTopic)){
                    $data['object'] = $liveTopic->topic_name;
                    $data['subject'] = "Proposed change to " . $liveTopic->topic_name . " submitted";
                }
                /** #771 issue solved
                ** sent mail to all direct supporters in case someone updates in the supported topic
                **/
                if(!empty($directSupporter)){
                    
                    $this->mailSupporters($directSupporter, $link, $data);
                }

                /** #771 Topic submitter is  getting "proposed changed to topic**/
                $data['link'] = \App\Model\Camp::getTopicCampUrl($topic->topic_num,1); 
                try{
                    Mail::to(Auth::user()->email)->bcc(config('app.admin_bcc'))->send(new ProposedChangeMail(Auth::user(), $link_history,$data));
                }catch(\Swift_TransportException $e){
                       throw new \Swift_TransportException($e);
                }

            }
        } catch (Exception $e) {

            DB::rollback();
            Session::flash('error', "Fail to create topic, please try later.");
        }

        return redirect('topic-history/' . $topic->topic_num)->with(['success' => $message, 'go_live_time' => $go_live_time, 'objection' => $objection]);
    }

    /**
     * Show form to submit / object a topic.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function manage_topic(Request $request, $id) {

        $paramArray = explode("-", $id);
        $id = $paramArray[0];
        $objection = (isset($paramArray[1]) && $paramArray[1] == 'objection') ? $paramArray[1] : null;
        $topicupdate = (isset($paramArray[1]) && $paramArray[1] == 'update') ? $paramArray[1] : null;


        $topic = Topic::where('id', $id)->first();

        $request->merge(['namespace' => $topic->namespace_id]);
        if (!count($topic))
            return back();
        $namespaces = Namespaces::all();

        $nickNames = Nickname::topicNicknameUsed($topic->topic_num);

        return view('topics.managetopic', compact('topic', 'objection', 'nickNames', 'namespaces', 'topicupdate'));
    }

    /**
     * Display the specified topic data with camps/statement.
     *
     * @param  int  $id = topic_num, $parentcampnum
     * @return \Illuminate\Http\Response
     */
    public function show($id, $parentcampnum = 1) {
        $topicnumArray = explode("-", $id);
        $topicnum = $topicnumArray[0];
        if(Auth::user() && Auth::user()->id){
            $userid = Auth::user()->id;
        }else{
            $userid = null;
        }

        if(session('campnum')) {
			session()->forget('campnum');
			return redirect()->refresh();
		}
        $topicData = [];
        $topic = Camp::getAgreementTopic($topicnum, $_REQUEST);
         if(!(isset($topic) && count($topic) > 0)){
              $topicData = Topic::where('topic_num','=',$topicnum)->get();
        }
        $camp = Camp::getLiveCamp($topicnum, $parentcampnum);
        if(!(isset($camp) && count($camp) > 0)){
              $campData = Camp::where('topic_num','=',$topicnum)
                            ->where('camp_num', '=', $parentcampnum)->get();
        }
        $camp_subscriptionsData = Camp::getCampSubscription($topicnum,$parentcampnum,$userid);
        $camp_subscriptions = $camp_subscriptionsData['flag'];
        $subscribedCamp = $camp_subscriptionsData['camp'];
        $camp_subscription_data = $camp_subscriptionsData['camp_subscription_data'];
        session()->forget("topic-support-{$topicnum}");
        session()->forget("topic-support-nickname-{$topicnum}");
        session()->forget("topic-support-tree-{$topicnum}");
        if (count($camp) > 0 && count($topic) > 0) {
          $parentcamp = Camp::campNameWithAncestors($camp, '',$topic->topic_name);
        } else {
			
		 $parentcamp = "N/A";	
		}
        $wiky = new Wiky;

        //$WikiParser  = new wikiParser;
        if (count($topic) <= 0) {

            //Session::flash('error', "Topic does not exist.");
          // return back();
        }
        if (count($camp) <= 0) {

            //Session::flash('error', "Camp does not exist.");
            //return back();
        }
        //news feeds
        $editFlag = true;
        $news = NewsFeed::where('topic_num', '=', $topicnum)
                        ->where('camp_num', '=', $parentcampnum)
                        ->where('end_time', '=', null)
                        ->orderBy('order_id', 'ASC')->get();
        if(!count($news) && count($camp) && $camp->parent_camp_num != null){
            $neCampnum = $camp->parent_camp_num;
            $news = NewsFeed::where('topic_num', '=', $topicnum)
                        ->where('camp_num', '=', $neCampnum)
                        ->where('end_time', '=', null)
                        ->where('available_for_child','=',1)
                        ->orderBy('order_id', 'ASC')->get();
            $editFlag = false;
        }
        
        return view('topics.view', compact('topic', 'parentcampnum','camp_subscriptions','camp_subscription_data','subscribedCamp', 'parentcamp', 'camp', 'wiky', 'id','news','editFlag','topicData','campData'));
    }

    /**
     * Create new camp, object a camp, submit update to camp.
     *
     * @param  int  $topic_num,$camp_num
     * @return \Illuminate\Http\Response
     */
    public function create_camp(Request $request, $topicnum, $parentcampnum) {
        session()->forget('asofDefault');
        $topicnumArray = explode("-", $topicnum);
        $topicnum = $topicnumArray[0];
		
		$topic = Camp::getAgreementTopic($topicnum,['nofilter'=>true]);
         $camp = Camp::getLiveCamp($topicnum, $parentcampnum,['nofilter'=>true]);
       
        $parentcamp = Camp::campNameWithAncestors($camp, '',$topic->topic_name);
        
		$parentcampsData = Camp::getAllParentCamp($topicnum,['nofilter'=>true]);

        $nickNames = Nickname::topicNicknameUsed($topicnum);
        $allNicknames = Nickname::orderBy('nick_name', 'ASC')->get();
        return view('topics.camp_create', compact('camp','parentcampsData','topic', 'parentcampnum', 'parentcamp', 'nickNames', 'allNicknames'));
    }

    /**
     * Show the form for submiting update to camp,object a camp.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function manage_camp($id) {

        $paramArray = explode("-", $id);
        $id = $paramArray[0];
        $objection = (isset($paramArray[1]) && $paramArray[1] == 'objection') ? $paramArray[1] : null;
        $campupdate = (isset($paramArray[1]) && $paramArray[1] == 'update') ? $paramArray[1] : null;
        $camp = Camp::where('id', $id)->first();

        if (!count($camp))
            return back();
        $parentcampnum = $camp->parent_camp_num;

        $topic = Camp::getAgreementTopic($camp->topic_num);

        $parentcamp = Camp::campNameWithAncestors($camp, '',$topic->topic_name);

        $parentcampsData = Camp::getAllParentCamp($camp->topic_num);

        $nickNames = Nickname::topicNicknameUsed($camp->topic_num);

        $allNicknames = Nickname::orderBy('nick_name', 'ASC')->get();

        return view('topics.managecamp', compact('parentcampsData', 'objection', 'topic', 'camp', 'parentcampnum', 'parentcamp', 'nickNames', 'allNicknames', 'campupdate'));
    }

    /**
     * Show the form for submiting update to camp statement,object a camp statement.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function manage_statement($id) {

        $paramArray = explode("-", $id);
        $id = $paramArray[0];
        $objection = (isset($paramArray[1]) && $paramArray[1] == 'objection') ? $paramArray[1] : null;
        $statementupdate = (isset($paramArray[1]) && $paramArray[1] == 'update') ? $paramArray[1] : null;

        $statement = Statement::where('id', $id)->first();

        if (!count($statement))
            return back();

        $topic = Camp::getAgreementTopic($statement->topic_num);

        $camp = Camp::getLiveCamp($statement->topic_num, $statement->camp_num);

        $parentcampnum = isset($camp->parent_camp_num) ? $camp->parent_camp_num : 0;

        $parentcamp = Camp::campNameWithAncestors($camp, '',$topic->topic_name);

        $nickNames = Nickname::topicNicknameUsed($statement->topic_num);

        return view('topics.managestatement', compact('objection', 'nickNames', 'topic', 'statement', 'parentcampnum', 'parentcamp', 'statementupdate'));
    }

    /**
     * Create new camp statement.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function create_statement($topic_num, $camp_num) {
        $topic = Camp::getAgreementTopic($topic_num);

        $camp = Camp::getLiveCamp($topic_num, $camp_num);

        $parentcampnum = isset($camp->parent_camp_num) ? $camp->parent_camp_num : 0;

        $parentcamp = Camp::campNameWithAncestors($camp, '',$topic->topic_name);

        $nickNames = Nickname::topicNicknameUsed($topic_num);

        return view('topics.createstatement', compact('objection', 'camp', 'nickNames', 'topic', 'parentcampnum', 'parentcamp'));
    }

    /**
     * Show camp history.
     *
     * @param  varchar  $id = topic_num,int $campnum
     * @return \Illuminate\Http\Response
     */
    public function camp_history($id, $campnum) {


        $topicnumArray = explode("-", $id);
        $topicnum = $topicnumArray[0];

        $topic = Camp::getAgreementTopic($topicnum);
        $onecamp = Camp::getLiveCamp($topicnum, $campnum);
        $parentcamp = (count($onecamp)) ? Camp::campNameWithAncestors($onecamp, '',$topic->topic_name) : "n/a";
        $camps = Camp::getCampHistory($topicnum, $campnum);
        $submit_time = (count($camps)) ? $camps[0]->submit_time: null;
        $parentcampnum = (isset($onecamp->parent_camp_num)) ? $onecamp->parent_camp_num : 0;
        $nickNames = null;
        $ifIamSupporter = null;
        $ifSupportDelayed = NULL;
        if (Auth::check()) {
            $nickNames = Nickname::personNicknameArray();
            $ifIamSupporter = Support::ifIamSupporter($topicnum, $campnum, $nickNames,$submit_time);
            $ifSupportDelayed = Support::ifIamSupporter($topicnum, $campnum, $nickNames,$submit_time,$delayed=true); //if support provided after Camp submitted for IN-Review
        }

        //if(!count($onecamp)) return back();
        $wiky = new Wiky;
        return view('topics.camphistory', compact('topic', 'camps', 'parentcampnum', 'onecamp', 'parentcamp', 'wiky', 'ifIamSupporter','submit_time','ifSupportDelayed'));
    }

    /**
     * Show camp statement history.
     *
     * @param  varchar  $id = topic_num,int $campnum
     * @return \Illuminate\Http\Response
     */
    public function statement_history($id, $campnum) {

        $topicnumArray = explode("-", $id);
        $topicnum = $topicnumArray[0];

        $topic = Camp::getAgreementTopic($topicnum);
        $onecamp = Camp::getLiveCamp($topicnum, $campnum);
        $parentcamp = Camp::campNameWithAncestors($onecamp, '',$topic->topic_name);

        if (!count($onecamp))
            return back();

        $parentcampnum = isset($onecamp->parent_camp_num) ? $onecamp->parent_camp_num : 0;

        $statement = Statement::getHistory($topicnum, $campnum);
        $submit_time = (count($statement)) ? $statement[0]->submit_time: null; 
        $nickNames = null;
        $ifIamSupporter = null;
        $ifSupportDelayed = null;
        if (Auth::check()) {
            $nickNames = Nickname::personNicknameArray();
            $ifIamSupporter = Support::ifIamSupporter($topicnum, $campnum, $nickNames,$submit_time);
            $ifSupportDelayed = Support::ifIamSupporter($topicnum, $campnum, $nickNames,$submit_time, $delayed=true);
        }
        $wiky = new Wiky;
        return view('topics.statementhistory', compact('topic', 'statement', 'parentcampnum', 'onecamp', 'parentcamp', 'wiky', 'ifIamSupporter','submit_time','ifSupportDelayed'));
    }

    /**
     * Show Topic history.
     *
     * @param  varchar  $id = topic_num
     * @return \Illuminate\Http\Response
     */
    public function topic_history($id) {

        $topicnumArray = explode("-", $id);
        $topicnum = $topicnumArray[0];

        $topics = Topic::getHistory($topicnum);
        $parentTopic = (sizeof($topics) > 1) ? $topics[0]->topic_name : null;
        $submit_time = (count($topics)) ? $topics[0]->submit_time: null; 

        if (!count($topics)) {
            return back();
        }

        $wiky = new Wiky;
        $nickNames = null;
        $ifIamSupporter = null;
        $ifSupportDelayed = null;
        if (Auth::check()) {
            $nickNames = Nickname::personNicknameArray();
            $ifIamSupporter = Support::ifIamSupporter($topicnum, 1, $nickNames,$submit_time);
            $ifSupportDelayed = Support::ifIamSupporter($topicnum, 1, $nickNames,$submit_time,$delayed=true);
        }

        return view('topics.topichistory', compact('topics', 'wiky', 'ifIamSupporter', 'topicnum','parentTopic','submit_time', 'ifSupportDelayed'));
    }

    /**
     * Store submitted camp,objected camp data
     *
     * @param  varchar  $request
     * @return \Illuminate\Http\Response
     */
    public function store_camp(Request $request) {
        $all = $request->all();
       //echo "<pre>"; print_r($all); exit;
        $currentTime = time();
        $messagesVal = [
            'camp_name.regex' => 'Camp name can only contain space and alphanumeric characters.',
            'nick_name.required' => 'The nick name field is required.',
            'camp_name.required' => 'Camp name is required.',
            'camp_name.max' => 'Camp name can not be more than 30 characters.',
            'camp_about_url.max' => "Camp's about url can not be more than 1024 characters.",
            'parent_camp_num.required' => 'The parent camp name is required.',
            'objection.required' => 'Objection reason is required.',
            'objection_reason.max' => 'Objection reason can not be more than 100.'
        ];
        $validator = Validator::make($request->all(), [
            'nick_name' => 'required',
            'camp_name' => 'required|max:30|regex:/^[a-zA-Z0-9\s]+$/',
            'camp_about_url' => 'max:10',
            'parent_camp_num' => 'nullable'
            // 'note' => 'required',
        ],$messagesVal);
        
		session(['filter'=>'removed']);
        $objection = '';
        if (isset($all['objection']) && $all['objection'] == 1) {
            $objection = 1;            
            $validator = Validator::make($request->all(), [
                        'nick_name' => 'required',
                        'camp_name' => 'required|max:30|regex:/^[a-zA-Z0-9\s]+$/',
                        'objection_reason' => 'required|max:100',
            ],$messagesVal);
        }
        $topicnum = (isset($all['topic_num'])) ? $all['topic_num'] : null;
        if($topicnum!=null){

            $old_parent_camps = Camp::getAllTopicCamp($topicnum);
            /**
             * @updated By Talentelgia
             */
            $liveCamps = Camp::getAllLiveCampsInTopic($topicnum);
            $nonLiveCamps = Camp::getAllNonLiveCampsInTopic($topicnum);
            $camp_existsLive = 0;
            $camp_existsNL = 0;
            if(!empty($liveCamps)){
                foreach($liveCamps as $value){
                    if($value->camp_name == $all['camp_name']){
                        if(isset($all['camp_num']) && array_key_exists('camp_num', $all) && $all['camp_num'] == $value->camp_num){
                            $camp_existsLive = 0;
                        }else{
                            $camp_existsLive = 1;
                        }
                    }
                }
            }
            if(!empty($nonLiveCamps)){
                foreach($nonLiveCamps as $value){
                    if($value->camp_name == $all['camp_name']){
                        if(isset($all['camp_num']) && array_key_exists('camp_num', $all) && $all['camp_num'] == $value->camp_num){
                            $camp_existsNL = 0;
                        }else{ 
                             $camp_existsNL = 1;
                        }
                    }
                }
            }
            if($camp_existsLive || $camp_existsNL){
               $validator->after(function ($validator){
                     $validator->errors()->add('camp_name', 'The camp name has already been taken');
                }); 
            }
        }
       
        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput($request->all());
        }
        $message = null;
        $go_live_time = "";
        $camp = new Camp();
        $camp->topic_num = $all['topic_num'];


        $camp->parent_camp_num = isset($all['parent_camp_num']) ? $all['parent_camp_num'] : "";

        $camp->camp_name = isset($all['camp_name']) ? $all['camp_name'] : "";
        $camp->submit_time = strtotime(date('Y-m-d H:i:s'));
        $camp->go_live_time = $currentTime; //strtotime(date('Y-m-d H:i:s', strtotime('+7 days')));
        $camp->language = 'English';

        $camp->note = isset($all['note']) ? $all['note'] : "";
        $camp->key_words = isset($all['keywords']) ? $all['keywords'] : "";
        $camp->submitter_nick_id = isset($all['nick_name']) ? $all['nick_name'] : "";
        $camp->camp_about_url = isset($all['camp_about_url']) ? $all['camp_about_url'] : "";
        $camp->camp_about_nick_id = isset($all['camp_about_nick_id']) ? $all['camp_about_nick_id'] : "";
        $camp->grace_period = 1;

        $eventtype = "CREATE";
        if (isset($all['camp_num'])) {
            $eventtype = "UPDATE";
            $camp->camp_num = $all['camp_num'];
            $camp->submitter_nick_id = $all['nick_name'];

            $message = "Camp change submitted successfully.";
            $nickNames = Nickname::personNicknameArray();

            $ifIamSingleSupporter = Support::ifIamSingleSupporter($all['topic_num'], $all['camp_num'], $nickNames);
           
            if (!$ifIamSingleSupporter) {
              //  $camp->go_live_time = strtotime(date('Y-m-d H:i:s', strtotime('+7 days')));
                $camp->go_live_time = strtotime(date('Y-m-d H:i:s', strtotime('+'.config('app.go_live_day_limit').' days')));
                $message = "Camp change submitted successfully.";
                $go_live_time = $camp->go_live_time;
            }

            if (isset($all['objection']) && $all['objection'] == 1) {
                $eventtype = "OBJECTION";
                $camp = Camp::where('id', $all['objection_id'])->first();
                $camp->objector_nick_id = $all['nick_name'];
                $camp->object_reason = $all['objection_reason'];
                $camp->object_time = time();
                $message = "Objection submitted successfully.";
            }
            if (isset($all['camp_update']) && $all['camp_update'] == 1) {
                $eventtype = "CAMP_UPDATE";
                $camp = Camp::where('id', $all['camp_id'])->first();
                $camp->topic_num = $all['topic_num'];
                $camp->parent_camp_num = isset($all['parent_camp_num']) ? $all['parent_camp_num'] : "";
                $camp->camp_name = isset($all['camp_name']) ? $all['camp_name'] : "";
                $camp->note = isset($all['note']) ? $all['note'] : "";
                $camp->key_words = isset($all['keywords']) ? $all['keywords'] : "";
                $camp->submitter_nick_id = isset($all['nick_name']) ? $all['nick_name'] : "";
                $camp->camp_about_url = isset($all['camp_about_url']) ? $all['camp_about_url'] : "";
                $camp->camp_about_nick_id = isset($all['camp_about_nick_id']) ? $all['camp_about_nick_id'] : "";

                $message = "Updation in your changed camp made successfully.";
            }
        } else {

            $message = 'Camp created successfully.';
        }

        if ($camp->save()) {
            Session::flash('test_case_success', 'true');

            if ($eventtype == "CREATE") {

                // send history link in email
                $link = 'camp/history/' . $camp->topic_num . '/' . $camp->camp_num;
                $data['type'] = "camp";

                $livecamp = Camp::getLiveCamp($camp->topic_num,$camp->camp_num);
				$data['object'] = $livecamp->topic->topic_name . " / " . $camp->camp_name;
				$data['link'] = \App\Model\Camp::getTopicCampUrl($camp->topic_num,1);
                try{

                Mail::to(Auth::user()->email)->bcc(config('app.admin_bcc'))->send(new ThankToSubmitterMail(Auth::user(), $link,$data));
                }catch(\Swift_TransportException $e){
                        throw new \Swift_TransportException($e);
                    } 
            } else if ($eventtype == "OBJECTION") {

                $user = Nickname::getUserByNickName($all['submitter']);
                $livecamp = Camp::getLiveCamp($camp->topic_num,$camp->camp_num);
                $link = 'camp/history/' . $camp->topic_num . '/' . $camp->camp_num;
                // $link = 'topic/' . $camp->topic_num . '/1';
                $nickName = Nickname::getNickName($all['nick_name']);

                $data['nick_name'] = $nickName->nick_name;
                $data['forum_link'] = 'forum/' . $camp->topic_num . '-' . $camp->camp_name . '/' . $camp->camp_num . '/threads';
                $data['subject'] = $data['nick_name'] . " has objected to your proposed change.";
                $data['topic_link'] = \App\Model\Camp::getTopicCampUrl($camp->topic_num,$camp->camp_num); 
                $data['type'] = "Camp";
                $data['object_type'] = ""; 
                $data['object'] = $livecamp->topic->topic_name."/".$livecamp->camp_name;
                $data['help_link'] = General::getDealingWithDisagreementUrl();

                //$data['type'] = 'camp'; 
                $receiver = (config('app.env') == "production" || config('app.env') == "staging") ? $user->email : config('app.admin_email');
                try{

                Mail::to($receiver)->bcc(config('app.admin_bcc'))->send(new ObjectionToSubmitterMail($user, $link, $data));
                }catch(\Swift_TransportException $e){
                        throw new \Swift_TransportException($e);
                    } 
            } else if ($eventtype == "UPDATE") {

                $directSupporter = Support::getAllDirectSupporters($camp->topic_num, $camp->camp_num);
                $link = 'camp/history/' . $camp->topic_num . '/' . $camp->camp_num;
                $data['object'] = $camp->topic->topic_name . ' / ' . $camp->camp_name;
                $data['support_camp'] = $camp->camp_name;
                $data['type'] = 'camp : ';
                $data['typeobject'] = 'camp';
                $data['go_live_time'] = $camp->go_live_time;
                $data['note'] = $camp->note;
                $nickName = Nickname::getNickName($camp->submitter_nick_id);
                $data['topic_num'] = $camp->topic_num;
                $data['nick_name'] = $nickName->nick_name;
                $data['forum_link'] = 'forum/' . $camp->topic_num . '-' . $camp->camp_name . '/' . $camp->camp_num . '/threads';
                $data['subject'] = "Proposed change to " . $camp->topic->topic_name . ' / ' . $camp->camp_name . " submitted";

                $nickNames = Nickname::personNicknameArray();
                // $ifIamSingleSupporter = Support::ifIamSingleSupporter($all['topic_num'], $all['camp_num'], $nickNames);
                // if($ifIamSingleSupporter){
                //     $subscribers = Camp::getCampSubscribers($camp->topic_num, $camp->camp_num);
                //     if(isset($subscribers) && count($subscribers) > 0){
                //         $this->mailSubscribers($subscribers, $link, $data);
                //     }
                // }
                
            }
           
            Session::flash('success', $message);
        } else {

            $message = 'Camp not added, please try again.';
        }

        if (isset($all['objection']) && $all['objection'] == 1) {
            return redirect('camp/history/' . $camp->topic_num . '/' . $camp->camp_num)->with(['success' => $message, 'go_live_time' => $go_live_time, 'objection' => $objection]);
        } else {
            return redirect('camp/history/' . $camp->topic_num . '/' . $camp->camp_num)->with(['success' => $message, 'go_live_time' => $go_live_time]);
        }
    }

    /**
     * Store submitted camp statement,objected camp statement data
     *
     * @param  varchar  $request
     * @return \Illuminate\Http\Response
     */
    public function store_statement(Request $request) {
        $all = $request->all();

        $currentTime = time();
        $messagesVal = [
            'statement.required' => 'The statement field is required.',
            'nick_name.required' => 'The nick name field is required.',
            'objection.required' => 'Objection reason is required.',
            'objection_reason.max' => 'Objection reason can not be more than 100.'
        ];
        $validator = Validator::make($request->all(), [
                    'statement' => 'required',
                    //'note' => 'required',
                    'nick_name' => 'required'
        ], $messagesVal);
        
        if (isset($all['objection']) && $all['objection'] == 1) {
            $validator = Validator::make($request->all(), [
                        'nick_name' => 'required',
                        'objection_reason' => 'required|max:100',
            ], $messagesVal);
        }
        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput($request->all());
        }

        $go_live_time = $currentTime;
        $statement = new Statement();

        $statement->value = isset($all['statement']) ? $all['statement'] : "";
        $statement->topic_num = $all['topic_num'];
        $statement->camp_num = $all['camp_num'];
        $statement->note = isset($all['note']) ? $all['note'] : "";
        $statement->submit_time = strtotime(date('Y-m-d H:i:s'));
        $statement->submitter_nick_id = $all['nick_name'];
        $statement->go_live_time = $currentTime; //strtotime(date('Y-m-d H:i:s', strtotime('+7 days')));
        $statement->language = 'English';
        $statement->grace_period = 1;

        $eventtype = "CREATE";
        $message = "Statement submitted successfully.";

        if (isset($all['camp_num'])) {
            $eventtype = "UPDATE";
		    $statement->camp_num = $all['camp_num'];
            $statement->submitter_nick_id = $all['nick_name'];

            $nickNames = Nickname::personNicknameArray();

            $ifIamSingleSupporter = Support::ifIamSingleSupporter($all['topic_num'], $all['camp_num'], $nickNames);
            if (!$ifIamSingleSupporter) {
               // $statement->go_live_time = strtotime(date('Y-m-d H:i:s', strtotime('+7 days')));
                $statement->go_live_time = strtotime(date('Y-m-d H:i:s', strtotime('+'.config('app.go_live_day_limit').' days')));
                $message = "Statement submitted successfully.";
                $go_live_time = $statement->go_live_time;
            }
            if (isset($all['objection']) && $all['objection'] == 1) {
                $message = "Objection submitted successfully.";
                $statement = Statement::where('id', $all['objection_id'])->first();
                $eventtype = "OBJECTION";
                $statement->objector_nick_id = $all['nick_name'];
                $statement->object_reason = $all['objection_reason'];
                $statement->go_live_time = $go_live_time;
                $statement->object_time = time();
            }
            if (isset($all['statement_update']) && $all['statement_update'] == 1) {
                $eventtype = "UPDATE";
                $message = "Updation in your changed statement are successful.";
                $statement = Statement::where('id', $all['statement_id'])->first();
                $eventtype = "STATEMENT_UPDATE";
                $statement->value = isset($all['statement']) ? $all['statement'] : "";
                $statement->note = isset($all['note']) ? $all['note'] : "";
                $statement->submitter_nick_id = $all['nick_name'];
            }
        } 
        $statement->save();
        if ($eventtype == "CREATE") {
           // send history link in email
            $dataObject = [];
            $link = 'statement/history/' . $statement->topic_num . '/' . $statement->camp_num;
            $livecamp = Camp::getLiveCamp($statement->topic_num,$statement->camp_num);
			$data['type'] = "statement";
			$data['object'] = $livecamp->topic->topic_name . " / " . $livecamp->camp_name;
			$data['link'] = \App\Model\Camp::getTopicCampUrl($statement->topic_num,1);
            try{

            Mail::to(Auth::user()->email)->bcc(config('app.admin_bcc'))->send(new ThankToSubmitterMail(Auth::user(), $link,$data));
            }catch(\Swift_TransportException $e){
                throw new \Swift_TransportException($e);
            } 
            // mail to direct supporters on new statement creation
            $directSupporter = Support::getAllDirectSupporters($statement->topic_num, $statement->camp_num);
            $subscribers = Camp::getCampSubscribers($statement->topic_num, $statement->camp_num);
            $dataObject['topic_num'] = $statement->topic_num;
            $dataObject['object'] = $livecamp->topic->topic_name . " / " . $livecamp->camp_name;
            $dataObject['support_camp'] = $livecamp->camp_name;
            $dataObject['go_live_time'] = $statement->go_live_time;
            $dataObject['type'] = 'statement : for camp ';
            $dataObject['typeobject'] = 'statement';
            $dataObject['note'] = $statement->note;
            $nickName = Nickname::getNickName($statement->submitter_nick_id);

            $dataObject['nick_name'] = $nickName->nick_name;
            $dataObject['forum_link'] = 'forum/' . $statement->topic_num . '-statement/' . $statement->camp_num . '/threads';
            $dataObject['subject'] = "Proposed change to statement for camp " . $livecamp->topic->topic_name . " / " . $livecamp->camp_name. " submitted";
            $this->mailSubscribersAndSupporters($directSupporter,$subscribers,$link, $dataObject);

         } else if ($eventtype == "OBJECTION") {

            $user = Nickname::getUserByNickName($all['submitter']);
            $livecamp = Camp::getLiveCamp($statement->topic_num,$statement->camp_num);
            
            //$link = 'topic/' . $statement->topic_num . '/1';
            $link = 'statement/history/' . $statement->topic_num . '/' . $statement->camp_num;
            //$data['object'] = $statement->statement;
            $nickName = Nickname::getNickName($all['nick_name']);

            $data['topic_link'] = \App\Model\Camp::getTopicCampUrl($statement->topic_num,$statement->camp_num);
            $data['type'] = "Camp";
            $data['object'] = $livecamp->topic->topic_name . " / " . $livecamp->camp_name;
            $data['object_type'] = "statement";   
            $data['nick_name'] = $nickName->nick_name;
            $data['forum_link'] = 'forum/' . $statement->topic_num . '-statement/' . $statement->camp_num . '/threads';
            $data['subject'] = $data['nick_name'] . " has objected to your proposed change.";
            $data['help_link'] = General::getDealingWithDisagreementUrl();

            $receiver = (config('app.env') == "production" || config('app.env') == "staging") ? $user->email : config('app.admin_email');
            try{
                Mail::to($receiver)->bcc(config('app.admin_bcc'))->send(new ObjectionToSubmitterMail($user, $link, $data));
            }catch(\Swift_TransportException $e){
                throw new \Swift_TransportException($e);
            } 
        } else if ($eventtype == "UPDATE") {

            $directSupporter = Support::getAllDirectSupporters($statement->topic_num, $statement->camp_num);
             $link = 'statement/history/' . $statement->topic_num . '/' . $statement->camp_num;
             $livecamp = Camp::getLiveCamp($statement->topic_num,$statement->camp_num);
             //$link = 'topic/' . $statement->topic_num . '/' . $statement->camp_num . '?asof=bydate&asofdate=' . date('Y/m/d H:i:s', $statement->go_live_time);
            $dataObject['topic_num'] = $statement->topic_num;
            $dataObject['object'] = $livecamp->topic->topic_name . " / " . $livecamp->camp_name;
            $dataObject['support_camp'] = $livecamp->camp_name;
            $dataObject['go_live_time'] = $statement->go_live_time;
            $dataObject['type'] = 'statement : for camp ';
            $dataObject['typeobject'] = 'statement';
            $dataObject['note'] = $statement->note;
            $nickName = Nickname::getNickName($statement->submitter_nick_id);
            $dataObject['nick_name'] = $nickName->nick_name;
            $dataObject['forum_link'] = 'forum/' . $statement->topic_num . '-statement/' . $statement->camp_num . '/threads';
            $dataObject['subject'] = "Proposed change to statement for camp " . $livecamp->topic->topic_name . " / " . $livecamp->camp_name. " submitted";
            $nickNames = Nickname::personNicknameArray();
            // $ifIamSingleSupporter = Support::ifIamSingleSupporter($all['topic_num'], $all['camp_num'], $nickNames);
            // if($ifIamSingleSupporter){
            //     $subscribers = Camp::getCampSubscribers($statement->topic_num, $statement->camp_num);
            //     if(isset($subscribers) && count($subscribers) > 0){
            //         $this->mailSubscribers($subscribers, $link, $dataObject);
            //     }
            // }
        }
        return redirect('statement/history/' . $statement->topic_num . '/' . $statement->camp_num)->with(['success' => $message, 'go_live_time' => $go_live_time]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

    public function usersupports(Request $request, $id) {

        $nickName = Nickname::find($id);
        $topic_num = $request->get('topicnum'); 
        $namespaces = Namespaces::all();
        $topicData = Topic::where('topic_num',$topic_num)->latest('submit_time')->get();
        $namespace_id = ($request->get('namespace')  != '' && $request->get('namespace') != null) ?  $request->get('namespace') : 0;
        if($namespace_id == 0 && isset($topicData[0])){
            $namespace_id  = $topicData[0]->namespace_id ;
        } 
        if($namespace_id ==0){
            $namespace_id = $namespaces[0]->id;
        }
        return view('user-supports', compact('nickName', 'namespaces','namespace_id','id'));
    }

    public function preview_statement(Request $request) {
        $data = $request->all();
        $data['submit_time'] = strtotime(date('Y-m-d H:i:s'));
        $data['go_live_time'] = strtotime(date('Y-m-d H:i:s', strtotime('+7 days')));
        return view('topics.statement_preview', compact('data'));
        exit;
    }

    public function statement_agreetochange(Request $request) {
        $data = $request->all();
        $changeID = '';
        $log = new ChangeAgreeLog();
        $log->camp_num = $data['camp_num'];
        $log->topic_num = $data['topic_num'];
        $log->nick_name_id = $data['nick_name_id'];
        $log->change_for = $data['change_for'];
        if (isset($data['change_for']) && $data['change_for'] == 'statement') {
            $log->change_id = $data['statement'];
            $changeID = $data['statement'];
        } else if (isset($data['change_for']) && $data['change_for'] == 'camp') {
            $log->change_id = $data['camp_id'];
            $changeID = $data['camp_id'];
        } else if (isset($data['change_for']) && $data['change_for'] == 'topic') {
            $log->change_id = $data['topic_id'];
            $changeID = $data['topic_id'];
        }
         $log->save();
        if (isset($data['change_for']) && $data['change_for'] == 'statement') {
            $statement = Statement::where('id', $data['statement'])->first();
		   if(isset($statement)) {	
            $submitterNickId = $statement->submitter_nick_id;
            $agreeCount = ChangeAgreeLog::where('topic_num', '=', $data['topic_num'])->where('camp_num', '=', $data['camp_num'])->where('change_id', '=', $changeID)->where('change_for', '=', 'statement')->count();
            $supporters = Support::getAllSupporters($data['topic_num'], $data['camp_num'], $submitterNickId);
            if ($agreeCount == $supporters) {
                //go live                
                $statement->go_live_time = strtotime(date('Y-m-d H:i:s'));
                $statement->update();
                //clear log
                ChangeAgreeLog::where('topic_num', '=', $data['topic_num'])->where('camp_num', '=', $data['camp_num'])->where('change_id', '=', $changeID)->where('change_for', '=', $data['change_for'])->delete();
            }
		  }	
          Session::flash('success', "Your agreement to statement submitted successfully");
        } else if (isset($data['change_for']) && $data['change_for'] == 'camp') {
            $camp = Camp::where('id', $changeID)->first();
			if(isset($camp)) {
            $submitterNickId = $camp->submitter_nick_id;
            $agreeCount = ChangeAgreeLog::where('topic_num', '=', $data['topic_num'])->where('camp_num', '=', $data['camp_num'])->where('change_id', '=', $changeID)->where('change_for', '=', $data['change_for'])->count();
            $supporters = Support::getAllSupporters($data['topic_num'], $data['camp_num'],$submitterNickId);
            if ($agreeCount == $supporters) {
                //go live                
                $camp->go_live_time = strtotime(date('Y-m-d H:i:s'));
                $camp->update();
                //clear log
                ChangeAgreeLog::where('topic_num', '=', $data['topic_num'])->where('camp_num', '=', $data['camp_num'])->where('change_id', '=', $changeID)->where('change_for', '=', $data['change_for'])->delete();
            }
			}	
            Session::flash('success', "Your agreement to camp submitted successfully");
        } else if (isset($data['change_for']) && $data['change_for'] == 'topic') {
            $topic = Topic::where('id', $changeID)->first();
			if(isset($topic)) { 
            $submitterNickId = $topic->submitter_nick_id;
            $agreeCount = ChangeAgreeLog::where('topic_num', '=', $data['topic_num'])->where('camp_num', '=', $data['camp_num'])->where('change_id', '=', $changeID)->where('change_for', '=', $data['change_for'])->count();
            $supporters = Support::getAllSupporters($data['topic_num'], $data['camp_num'],$submitterNickId);
            if ($agreeCount == $supporters) {
                //go live               
                $topic->go_live_time = strtotime(date('Y-m-d H:i:s'));
                $topic->update();
                //clear log
                ChangeAgreeLog::where('topic_num', '=', $data['topic_num'])->where('camp_num', '=', $data['camp_num'])->where('change_id', '=', $changeID)->where('change_for', '=', $data['change_for'])->delete();
            }
		  }	
          Session::flash('success', "Your agreement to topic submitted successfully");
        }

        return back();
    }

    public function notify_change(Request $request) {
        $all = $request->all();
        $type = $all['type'];
        $id = $all['id'];

        if ($type == 'statement') {
            $statement = Statement::where('id', '=', $id)->first();
            $statement->grace_period = 0;
            $statement->update();
            $directSupporter = Support::getAllDirectSupporters($statement->topic_num, $statement->camp_num);
            $subscribers = Camp::getCampSubscribers($statement->topic_num, $statement->camp_num);
            // $link = 'statement/history/' . $id . '/' . $statement->camp_num . '?asof=bydate&asofdate=' . date('Y/m/d H:i:s', $statement->go_live_time);
            $link = 'statement/history/' . $statement->topic_num . '/' . $statement->camp_num;
            $livecamp = Camp::getLiveCamp($statement->topic_num,$statement->camp_num);
            $data['object'] = $livecamp->topic->topic_name . " / " . $livecamp->camp_name;
            $data['support_camp'] = $livecamp->camp_name;
            $data['go_live_time'] = $statement->go_live_time;
            $data['type'] = 'statement : for camp ';
            $data['typeobject'] = 'statement';
			$data['note'] = $statement->note;
            $data['camp_num'] = $statement->camp_num;
            $nickName = Nickname::getNickName($statement->submitter_nick_id);
            $data['topic_num'] = $statement->topic_num;
            $data['nick_name'] = $nickName->nick_name;
            $data['forum_link'] = 'forum/' . $statement->topic_num . '-statement/' . $statement->camp_num . '/threads';
            $data['subject'] = "Proposed change to statement for camp " . $livecamp->topic->topic_name . " / " . $livecamp->camp_name. " submitted";
           $this->mailSubscribersAndSupporters($directSupporter,$subscribers,$link, $data);
            return response()->json(['id' => $statement->id, 'message' => 'Your change to statement has been submitted to your supporters.']);
        } else if ($type == 'camp') {
            $camp = Camp::where('id', '=', $id)->first();
            $camp->grace_period = 0;
            $camp->update();

            $directSupporter = Support::getAllDirectSupporters($camp->topic_num, $camp->camp_num);
            $subscribers = Camp::getCampSubscribers($camp->topic_num, $camp->camp_num);
            $livecamp = Camp::getLiveCamp($camp->topic_num,$camp->camp_num);
           
            //$link = 'camp/history/' . $id . '/' . $camp->camp_num . '?asof=bydate&asofdate=' . date('Y/m/d H:i:s', $camp->go_live_time);
            $link = 'camp/history/' . $livecamp->topic_num . '/' . $livecamp->camp_num;
            $data['object'] = $livecamp->topic->topic_name . ' / ' . $livecamp->camp_name;
            $data['support_camp'] = $camp->camp_name;
            $data['type'] = 'camp : ';
            $data['typeobject'] = 'camp';
            $data['go_live_time'] = $camp->go_live_time;
			$data['note'] = $camp->note;
            $data['camp_num'] = $camp->camp_num;
            $nickName = Nickname::getNickName($camp->submitter_nick_id);
            $data['topic_num'] = $camp->topic_num;
            $data['nick_name'] = $nickName->nick_name;
            $data['forum_link'] = 'forum/' . $livecamp->topic_num . '-' . $livecamp->camp_name . '/' . $livecamp->camp_num . '/threads';
            $data['subject'] = "Proposed change to " . $livecamp->topic->topic_name . ' / ' . $livecamp->camp_name . " submitted";

            //$this->mailSupporters($directSupporter, $link, $data);         //mail supporters             
            //$this->mailSubscribers($subscribers, $link, $data);         // mail subscribers  
            $this->mailSubscribersAndSupporters($directSupporter,$subscribers,$link, $data);  
            return response()->json(['id' => $camp->id, 'message' => 'Your change to camp has been submitted to your supporters.']);
        } else if ($type == 'topic') {
            $topicData = Topic::where('id', '=', $id)->first();
            $topic = Topic::getLiveTopic($topicData->topic_num);
            $topicData->grace_period = 0;
            $topicData->update();
            $directSupporter = Support::getAllDirectSupporters($topic->topic_num);          
            $subscribers = Camp::getCampSubscribers($topic->topic_num, 1);
             // $link = 'topic/' . $topic->topic_num . '/' . $topic->camp_num . '?asof=bydate&asofdate=' . date('Y/m/d H:i:s', $topic->go_live_time);
            $link = 'topic-history/' . $topic->topic_num;
            $data['object'] = $topic->topic_name;
            $data['support_camp'] = $topic->topic_name;
            $data['go_live_time'] = $topic->go_live_time;
            $data['type'] = 'topic : ';
            $data['typeobject'] = 'topic';
			$data['note'] = $topic->note;
            $data['camp_num'] = 1;
            $nickName = Nickname::getNickName($topic->submitter_nick_id);
            $data['topic_num'] = $topic->topic_num;
            $data['nick_name'] = $nickName->nick_name;
            $data['forum_link'] = 'forum/' . $topic->topic_num . '-' . $topic->topic_name . '/1/threads';
            $data['subject'] = "Proposed change to topic " . $topic->topic_name . " submitted";

           // $this->mailSupporters($directSupporter, $link, $data);         //mail supporters  
           //  $this->mailSubscribersAndSupporters($directSupporter,$subscribers,$link, $data);  
           return response()->json(['id' => $topic->id, 'message' => 'Your change to topic has been submitted to your supporters.']);
        }
    }

    private function mailSubscribersAndSupporters($directSupporter,$subscribers,$link, $dataObject){
        $alreadyMailed = [];
        $i=0;
        foreach ($directSupporter as $supporter) {            
        $supportData = $dataObject;
         $user = Nickname::getUserByNickName($supporter->nick_name_id);
         $alreadyMailed[] = $user->id;
         $topic = \App\Model\Topic::where('topic_num','=',$supportData['topic_num'])->latest('submit_time')->get();
         $topic_name_space_id = isset($topic[0]) ? $topic[0]->namespace_id:1;
         $nickName = \App\Model\Nickname::find($supporter->nick_name_id);
         $supported_camp = $nickName->getSupportCampList($topic_name_space_id,['nofilter'=>true]);
         //echo "<pre> camp data"; print_r($supported_camp);
         $supported_camp_list = $nickName->getSupportCampListNamesEmail($supported_camp,$supportData['topic_num'],$supportData['camp_num']);
         $supportData['support_list'] = $supported_camp_list; 
          $ifalsoSubscriber = Camp::checkifSubscriber($subscribers,$user);
          if($ifalsoSubscriber){
            $supportData['also_subscriber'] = 1;
            $supportData['sub_support_list'] = Camp::getSubscriptionList($user->id,$supportData['topic_num'],$supportData['camp_num']);      
         }
         
         $receiver = (config('app.env') == "production" || config('app.env') == "staging") ? $user->email : config('app.admin_email');
         try{

         Mail::to($receiver)->bcc(config('app.admin_bcc'))->send(new PurposedToSupportersMail($user, $link, $supportData));
         }catch(\Swift_TransportException $e){
                        throw new \Swift_TransportException($e);
                    } 
        }

        foreach ($subscribers as $usr) {            
            $subscriberData = $dataObject;
            $userSub = \App\User::find($usr);
            if(!in_array($userSub->id, $alreadyMailed,TRUE)){
                $alreadyMailed[] = $userSub->id;
                $subscriptions_list = Camp::getSubscriptionList($userSub->id,$subscriberData['topic_num'],$subscriberData['camp_num']);
                $subscriberData['support_list'] = $subscriptions_list; 
                $receiver = (config('app.env') == "production" || config('app.env') == "staging") ? $userSub->email : config('app.admin_email');
                $subscriberData['subscriber'] = 1;
                try{

              Mail::to($receiver)->bcc(config('app.admin_bcc'))->send(new PurposedToSupportersMail($userSub, $link, $subscriberData));
                }catch(\Swift_TransportException $e){
                        throw new \Swift_TransportException($e);
                    } 
            }
            
        }
        return;
    }

    private function mailSupporters($directSupporter, $link, $data) {
        foreach ($directSupporter as $supporter) {
            $user = Nickname::getUserByNickName($supporter->nick_name_id);
            $topic = \App\Model\Topic::where('topic_num','=',$data['topic_num'])->latest('submit_time')->get();
            $topic_name_space_id = isset($topic[0]) ? $topic[0]->namespace_id:1;
            $nickName = \App\Model\Nickname::find($supporter->nick_name_id);
            $supported_camp = $nickName->getSupportCampList($topic_name_space_id,['nofilter'=>true]);
            $supported_camp_list = $nickName->getSupportCampListNamesEmail($supported_camp,$data['topic_num'],$data['camp_num']);
            $data['support_list'] = $supported_camp_list; 
            $receiver = (config('app.env') == "production" || config('app.env') == "staging") ? $user->email : config('app.admin_email');
            try{

            Mail::to($receiver)->bcc(config('app.admin_bcc'))->send(new PurposedToSupportersMail($user, $link, $data));
            }catch(\Swift_TransportException $e){
                        throw new \Swift_TransportException($e);
                    } 
        }
        return;
    }
    private function mailSubscribers($subscribers, $link, $data) {
        foreach ($subscribers as $user) {
            $user = \App\User::find($user);
             $subscriptions_list = Camp::getSubscriptionList($user->id,$data['topic_num'],$data['camp_num']);
             $data['support_list'] = $subscriptions_list; 
                
            $receiver = (config('app.env') == "production" || config('app.env') == "staging") ? $user->email : config('app.admin_email');
            $data['subscriber'] = 1;
            try{

            Mail::to($receiver)->bcc(config('app.admin_bcc'))->send(new PurposedToSupportersMail($user, $link, $data));
            }catch(\Swift_TransportException $e){
                        throw new \Swift_TransportException($e);
                    } 
        }
        return;
    }


    public function add_camp_subscription(Request $request){
        try{

            $all = $request->all();
             $id = isset($all['id']) ? $all['id'] : null;
             if($all['checked'] == 'true'){
                $camp_subscription = new \App\Model\CampSubscription();
                 $camp_subscription->user_id = $all['userid'];
                 $camp_subscription->topic_num = $all['topic_num'];
                 $camp_subscription->camp_num = $all['camp_num'];
                 $camp_subscription->subscription_start = strtotime(date('Y-m-d H:i:s'));
                $msg = "You have successfully subscribed to this camp.";
             }else{
                if($id){
                    $camp_subs_data = \App\Model\CampSubscription::where('id','=',$id)->get();
                    $camp_subscription = $camp_subs_data[0];
                    $camp_subscription->subscription_end = strtotime(date('Y-m-d H:i:s'));
                }
                
                $msg = "You have successfully unsubscribed from this camp.";
             }
             $camp_subscription->save();
             $returnId = ($id) ? null : $camp_subscription->id;
             return response()->json(['result' => "Success", 'message' =>$msg ,'id'=>$returnId]);
        
        }catch(\Exception $e){
             return response()->json(['result' => "Error", 'message' => 'Error in subscring the camp.']);
        
        }
         
         
    }

}
