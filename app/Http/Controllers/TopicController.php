<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Library\General;
use App\Library\Wiky;
//use App\Library\Wikiparser\wikiParser;
use App\Model\Topic;
use App\Model\Camp;
use App\Model\Statement;
use App\Model\Nickname;
use App\Model\Support;
use DB;
use Validator;
use App\Model\Namespaces;
use App\Model\NamespaceRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\ThankToSubmitterMail;
use App\Mail\PurposedToSupportersMail;
use App\Mail\ObjectionToSubmitterMail;
use App\Mail\NewDelegatedSupporterMail;


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
    
    public function __construct()
    {
        parent::__construct();
        //$this->middleware('auth'); //->except('logout');
    }

    
    /**
     * Show the form for creating a new topic.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $namespaces= Namespaces::all();
        $nickNames   = Nickname::personNickname();
        return view('topics.create',compact('namespaces','nickNames'));
    }

    /**
     * Store a newly created topic,objected topic in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $all = $request->all();
		
        $validator = Validator::make($request->all(), [
            'topic_name'=>'required|max:30',
            'namespace' => 'required',
            'create_namespace'=>'required_if:namespace,other',
			'nick_name'=>'required',
			'note'=>'required'
            
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput($request->all());
        }
		
        DB::beginTransaction();
        
        try {
			$current_time = time();
			$eventtype ="CREATE";
            $topic = new Topic();
            $topic->topic_name = $all['topic_name'];		
			
            $topic->namespace_id = $all['namespace'];
            $topic->submit_time = $current_time;
            $topic->submitter_nick_id = $all['nick_name'];
            $topic->go_live_time = $current_time;//strtotime(date('Y-m-d H:i:s', strtotime('+7 days')));
            $topic->language = 'English';
            $topic->note = $all['note'];
						
			if(isset($all['topic_num'])) {
				
			 $topic->topic_num = $all['topic_num'];
			 $eventtype  = "UPDATE";
			 $message ="Topic update submitted successfully. It's live now.";
			 $nickNames   = Nickname::personNicknameArray();
			 
			 $ifIamSingleSupporter = Support::ifIamSingleSupporter($all['topic_num'],0,$nickNames);
			 
			 if(!$ifIamSingleSupporter) {
			   $topic->go_live_time = strtotime(date('Y-m-d H:i:s', strtotime('+7 days')));
			   $message ="Topic update submitted successfully. Its under review, once approved it will be live.";
			 } 
			 
			 if(isset($all['objection']) && $all['objection']==1) {
				 $topic->objector_nick_id = $all['nick_name'];
				 //$topic->submitter_nick_id = $all['submitter'];
				 $topic->object_reason = $all['object_reason'];
				 $topic->object_time = $current_time;
				 $eventtype = "OBJECTION";
			 }			 
			 
			 
			}
			else {
			 $message ="Topic created successfully. It's live now.";	
			}

			/* If topic is created then add default support to that topic */ 
            if($topic->save()) {
				
				$supportTopic  = new Support();
				$supportTopic->topic_num    = $topic->topic_num;
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
			
			
            if($all['namespace'] == 'other'){ /*Create new namespace request */
                $namespace_request = new NamespaceRequest;
                $namespace_request->user_id = Auth::user()->id;
                $namespace_request->name = $all['create_namespace'];
                $namespace_request->topic_num = $topic->topic_num;
                $namespace_request->save();
                $topic->namespace_id  = 1;
                $topic->save();
             }
            DB::commit();
            
            Session::flash('success', $message);
			
			if($eventtype=="CREATE") {
				
				// send history link in email
				$link = 'topic-history/'.$topic->topic_num;
				
				Mail::to(Auth::user()->email)->send(new ThankToSubmitterMail(Auth::user(),$link));
				
			} else if($eventtype=="OBJECTION") {
				
				$user = Nickname::getUserByNickName($all['submitter']);
				
				$link = 'topic-history/'.$topic->topic_num;
				$data['object'] = $topic->topic_name;
				$nickName = Nickname::getNickName($all['nick_name']);
				$data['type'] = 'topic';
				$data['nick_name'] = $nickName->nick_name;
				$data['forum_link'] = 'forum/'.$topic->topic_num.'/1/threads';
				$data['subject'] = $data['nick_name']." has objected to your proposed change.";
				
				$receiver = (config('app.env')=="production") ? $user->email : config('app.admin_email');
				Mail::to($receiver)->send(new ObjectionToSubmitterMail($user,$link,$data));
			} else if($eventtype=="UPDATE") { 
			    
				$directSupporter = Support::getDirectSupporter($topic->topic_num);
			    
				$link = 'topic/'.$topic->topic_num.'/'.$topic->camp_num.'?asof=bydate&asofdate='.date('Y/m/d H:i:s',$topic->go_live_time);
				$data['object'] = $topic->topic_name;
				$data['go_live_time'] = date('Y-m-d H:i:s', strtotime('+7 days'));
				$data['type'] = 'topic';
				$nickName = Nickname::getNickName($all['nick_name']);
				
				$data['nick_name'] = $nickName->nick_name;
				$data['forum_link'] = 'forum/'.$topic->topic_num.'/1/threads';
				$data['subject'] = "Proposed change to ".$topic->topic_name." submitted";

			  foreach($directSupporter as $supporter) { 
			    
				$user = Nickname::getUserByNickName($supporter->nick_name_id);
						
				
				$receiver = (config('app.env')=="production") ? $user->email : config('app.admin_email');
				Mail::to($receiver)->send(new PurposedToSupportersMail($user,$link,$data));
				
			  }
			
			}
			
        } catch (Exception $e) {
            
            DB::rollback();
            Session::flash('error', "Fail to create topic, please try later.");
        }

        return redirect('topic-history/'.$topic->topic_num)->with(['success'=>$message]);
    }
	
	 /**
     * Show form to submit / object a topic.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	
	public function manage_topic(Request $request,$id){
		
		$paramArray  = explode("-",$id);
		$id          = $paramArray[0];
		$objection   = isset($paramArray[1]) ? $paramArray[1] : null;
		
		$topic       = Topic::where('id',$id)->first();
        
		$request->merge(['namespace'=>$topic->namespace_id]);
		if(!count($topic)) return back();
        $namespaces= Namespaces::all();
		
		$nickNames = Nickname::topicNicknameUsed($topic->topic_num);
        
		return view('topics.managetopic', compact('topic','objection','nickNames','namespaces'));
		
	}

    /**
     * Display the specified topic data with camps/statement.
     *
     * @param  int  $id = topic_num, $parentcampnum
     * @return \Illuminate\Http\Response
     */
    public function show($id,$parentcampnum=1) {
        			
		$topicnumArray  = explode("-",$id);
		$topicnum       = $topicnumArray[0];
		
		$topic      = Camp::getAgreementTopic($topicnum,$_REQUEST);
		
        $camp       = Camp::getLiveCamp($topicnum,$parentcampnum);
		
		
        $parentcamp = Camp::campNameWithAncestors($camp,'');
        
		$wiky=new Wiky;
		
		//$WikiParser  = new wikiParser;
        if(count($topic) <= 0) {
			
			Session::flash('error', "Topic does not exist.");
			return back();
		}
		if(count($camp) <= 0) {
			
			Session::flash('error', "Camp does not exist.");
			return back();
		}
		
		return view('topics.view',  compact('topic','parentcampnum','parentcamp','camp','wiky'));
    }

    
    /**
     * Create new camp, object a camp, submit update to camp.
     *
     * @param  int  $topic_num,$camp_num
     * @return \Illuminate\Http\Response
     */
    public function create_camp(Request $request, $topicnum,$parentcampnum){
        
		$topic      = Camp::getAgreementTopic($topicnum);
        
		$camp       = Camp::getLiveCamp($topicnum,$parentcampnum);
        
		$parentcamp = Camp::campNameWithAncestors($camp,'');
		
		
        $nickNames  = Nickname::topicNicknameUsed($topicnum);
        $allNicknames = Nickname::orderBy('nick_name','ASC')->get();
        return view('topics.camp_create',  compact('topic','parentcampnum','parentcamp','nickNames','allNicknames'));
    }
	
	/**
     * Show the form for submiting update to camp,object a camp.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
	
	public function manage_camp($id){
		
		$paramArray  = explode("-",$id);
		$id       = $paramArray[0];
		$objection = isset($paramArray[1]) ? $paramArray[1] : null;
		
		$camp = Camp::where('id',$id)->first();
		
		if(!count($camp)) return back();
        $parentcampnum = $camp->parent_camp_num;
		
		$topic      = Camp::getAgreementTopic($camp->topic_num);

        $parentcamp = Camp::campNameWithAncestors($camp,'');
				
        $nickNames  = Nickname::topicNicknameUsed($camp->topic_num);
		
		$allNicknames = Nickname::orderBy('nick_name','ASC')->get();
		
        return view('topics.managecamp',  compact('objection','topic','camp','parentcampnum','parentcamp','nickNames','allNicknames'));
    }
	
	/**
     * Show the form for submiting update to camp statement,object a camp statement.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
	 
	public function manage_statement($id){
		
		$paramArray  = explode("-",$id);
		$id          = $paramArray[0];
		$objection   = isset($paramArray[1]) ? $paramArray[1] : null;
			
		$statement   = Statement::where('id',$id)->first();
		
		if(!count($statement)) return back();
		
        $topic         = Camp::getAgreementTopic($statement->topic_num);
        
		$camp          = Camp::getLiveCamp($statement->topic_num,$statement->camp_num);
        
		$parentcampnum = isset($camp->parent_camp_num) ? $camp->parent_camp_num : 0;
		
		$parentcamp    = Camp::campNameWithAncestors($camp,'');
	
		$nickNames     = Nickname::topicNicknameUsed($statement->topic_num);
       
        return view('topics.managestatement',  compact('objection','nickNames','topic','statement','parentcampnum','parentcamp'));
    }
	
	/**
     * Create new camp statement.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
	 
	public function create_statement($topic_num,$camp_num){
		
			

        $topic         = Camp::getAgreementTopic($topic_num);
        
		$camp          = Camp::getLiveCamp($topic_num,$camp_num);
        
		$parentcampnum = isset($camp->parent_camp_num) ? $camp->parent_camp_num : 0;
		
		$parentcamp    = Camp::campNameWithAncestors($camp,'');
	
		$nickNames     = Nickname::topicNicknameUsed($topic_num);
       
        return view('topics.createstatement',  compact('objection','camp','nickNames','topic','parentcampnum','parentcamp'));
    }
	
	/**
     * Show camp history.
     *
     * @param  varchar  $id = topic_num,int $campnum
     * @return \Illuminate\Http\Response
     */
	
	public function camp_history($id,$campnum){
		
		$topicnumArray  = explode("-",$id);
		$topicnum       = $topicnumArray[0];
		
        $topic          = Camp::getAgreementTopic($topicnum);
        $onecamp        = Camp::getLiveCamp($topicnum,$campnum);
        $parentcamp     = (count($onecamp)) ? Camp::campNameWithAncestors($onecamp,'') : "n/a";
		
		$camps          = Camp::getCampHistory($topicnum,$campnum);
       
		$parentcampnum  = (isset($onecamp->parent_camp_num)) ? $onecamp->parent_camp_num : 0;
		
	    //if(!count($onecamp)) return back();
		$wiky=new Wiky;
		
        return view('topics.camphistory',  compact('topic','camps','parentcampnum','onecamp','parentcamp','wiky'));
    }
	/**
     * Show camp statement history.
     *
     * @param  varchar  $id = topic_num,int $campnum
     * @return \Illuminate\Http\Response
     */
	public function statement_history($id,$campnum){
		
		$topicnumArray  = explode("-",$id);
		$topicnum       = $topicnumArray[0];
		
        $topic          = Camp::getAgreementTopic($topicnum);
        $onecamp        = Camp::getLiveCamp($topicnum,$campnum);
        $parentcamp     = Camp::campNameWithAncestors($onecamp,'');
		
	    if(!count($onecamp)) return back();
		
		$parentcampnum  = isset($onecamp->parent_camp_num) ? $onecamp->parent_camp_num : 0;
		
		$statement      = Statement::getHistory($topicnum,$campnum);
        $wiky           = new Wiky;
		
        return view('topics.statementhistory',  compact('topic','statement','parentcampnum','onecamp','parentcamp','wiky'));
    }
	
	/**
     * Show Topic history.
     *
     * @param  varchar  $id = topic_num
     * @return \Illuminate\Http\Response
     */
	
	public function topic_history($id){
		
		$topicnumArray  = explode("-",$id);
		$topicnum       = $topicnumArray[0];
		
        $topics = Topic::getHistory($topicnum);
        
		 
	    if(!count($topics)) { return back();}
		
		$wiky  =  new Wiky;
		
        return view('topics.topichistory',  compact('topics','wiky'));
    }
	
	/**
     * Store submitted camp,objected camp data
     *
     * @param  varchar  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store_camp(Request $request){
        $all = $request->all();
		$currentTime  = time();
        $validator = Validator::make($request->all(), [
            'nick_name'=>'required',
            'camp_name' => 'required|max:30',
            
            'note'=>'required',
			
        ]);
		
        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput($request->all());
        }
        $message = null;
        
        $camp = new Camp();
        $camp->topic_num = $all['topic_num'];
		
        $camp->parent_camp_num = $all['parent_camp_num'];
        //$camp->title = $all['title'];
        $camp->camp_name = $all['camp_name'];
		$camp->submit_time = strtotime(date('Y-m-d H:i:s'));
        $camp->go_live_time = $currentTime; //strtotime(date('Y-m-d H:i:s', strtotime('+7 days')));
        $camp->language = 'English';
       
        $camp->note = $all['note'];
		$camp->key_words = $all['keywords'];
		$camp->submitter_nick_id = $all['nick_name'];
        $camp->camp_about_url = $all['camp_about_url'];	
		$camp->camp_about_nick_id = $all['camp_about_nick_id'];
        $eventtype = "CREATE";
        if(isset($all['camp_num'])) {
		 $eventtype = "UPDATE";	
		 $camp->camp_num = $all['camp_num'];
		 $camp->submitter_nick_id = $all['nick_name'];
		 
		     $message ="Camp update submitted Successfully. It's live now.";
			 $nickNames   = Nickname::personNicknameArray();
			 
			 $ifIamSingleSupporter = Support::ifIamSingleSupporter($all['topic_num'],$all['camp_num'],$nickNames);
			 
			 if(!$ifIamSingleSupporter) {
			   $camp->go_live_time = strtotime(date('Y-m-d H:i:s', strtotime('+7 days')));
			   $message ="Camp update submitted Successfully. Its under review, once approved it will be live.";
			 }
		 
		 if(isset($all['objection']) && $all['objection']==1) {
		     $eventtype ="OBJECTION";
			 $camp->objector_nick_id = $all['nick_name'];			 
			 $camp->object_reason = $all['object_reason'];
			 $camp->object_time = time();
		 }	 
		 
		} else {
			
			$message = 'Camp Created Successfully.';
		}		
        
        if($camp->save()) {
			
		  if($eventtype=="CREATE") {
				
				// send history link in email
				$link = 'camp-history/'.$camp->topic_num.'/'.$camp->camp_num;
				
				Mail::to(Auth::user()->email)->send(new ThankToSubmitterMail(Auth::user(),$link));
				
			} else if($eventtype=="OBJECTION") {
				
				$user = Nickname::getUserByNickName($all['submitter']);
				
				$link = 'camp-history/'.$camp->topic_num.'/'.$camp->camp_num;
				$data['object'] = $camp->topic->topic_name." : ".$camp->camp_name;
				$nickName = Nickname::getNickName($all['nick_name']);
				
				$data['nick_name'] = $nickName->nick_name;
				$data['forum_link'] = 'forum/'.$camp->topic_num.'/'.$camp->camp_num.'/threads';
				$data['subject'] = $data['nick_name']." has objected to your proposed change.";
				$data['type'] = 'camp';
				$receiver = (config('app.env')=="production") ? $user->email : config('app.admin_email');
				Mail::to($receiver)->send(new ObjectionToSubmitterMail($user,$link,$data));
			} else if($eventtype=="UPDATE") { 
			
			    $directSupporter = Support::getDirectSupporter($camp->topic_num,$camp->camp_num);
			    
				$link = 'topic/'.$camp->topic_num.'/'.$camp->camp_num.'?asof=bydate&asofdate='.date('Y/m/d H:i:s',$camp->go_live_time);
				$data['object'] = $camp->topic->topic_name.' : '.$camp->camp_name;
				$data['type'] = 'camp';
				$data['go_live_time'] = date('Y-m-d H:i:s', strtotime('+7 days'));
				$nickName = Nickname::getNickName($all['nick_name']);
				
				$data['nick_name'] = $nickName->nick_name;
				$data['forum_link'] = 'forum/'.$camp->topic_num.'/'.$camp->camp_num.'/threads';
				$data['subject'] = "Proposed change to ".$camp->camp_name." submitted";

			  foreach($directSupporter as $supporter) { 
			    
				$user = Nickname::getUserByNickName($supporter->nick_name_id);
						
				
				$receiver = (config('app.env')=="production") ? $user->email : config('app.admin_email');
				Mail::to($receiver)->send(new PurposedToSupportersMail($user,$link,$data));
				
			  }
			
			}
          Session::flash('success', $message);
			
		} else {
			
		  $message = 'Camp not added, please try again.';	
		}
        
		return redirect('camp/history/'.$camp->topic_num.'/'.$camp->camp_num)->with(['success'=>$message]);
                
        
    }
	
	/**
     * Store submitted camp statement,objected camp statement data
     *
     * @param  varchar  $request
     * @return \Illuminate\Http\Response
     */
	 
	public function store_statement(Request $request){
        $all = $request->all();
		$currentTime  = time();
        $validator = Validator::make($request->all(), [
            'statement'=>'required',
            'note' => 'required',
			'nick_name'=>'required'
           
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput($request->all());
        }
        
        	
		  $statement = new Statement();	
		  
		  $statement->value = $all['statement'];
		  $statement->topic_num = $all['topic_num'];
		  $statement->camp_num = $all['camp_num'];
		  $statement->note = $all['note'];
		  $statement->submit_time = strtotime(date('Y-m-d H:i:s'));
		  $statement->submitter_nick_id = $all['nick_name'];
		  $statement->go_live_time = $currentTime;//strtotime(date('Y-m-d H:i:s', strtotime('+7 days')));
		  $statement->language = 'English';
		  $eventtype = "CREATE";
		  if(isset($all['camp_num'])) {
			 $eventtype = "UPDATE"; 
			 $statement->camp_num = $all['camp_num'];
			 $statement->submitter_nick_id = $all['nick_name'];
			 
			 $message ="Statement update submitted Successfully. It's live now.";
			 $nickNames   = Nickname::personNicknameArray();
			 
			 $ifIamSingleSupporter = Support::ifIamSingleSupporter($all['topic_num'],$all['camp_num'],$nickNames);
			 
			 if(!$ifIamSingleSupporter) {
			   $statement->go_live_time = strtotime(date('Y-m-d H:i:s', strtotime('+7 days')));
			   $message ="Statement update submitted Successfully. Its under review, once approved it will be live.";
			 }
			 
			 if(isset($all['objection']) && $all['objection']==1) {
		         $eventtype = "OBJECTION";
				 $statement->objector_nick_id = $all['nick_name'];
				 $statement->object_reason = $all['object_reason'];
				 $statement->object_time = time();
			 }	
			 
			 
		  } else { 
		    $message = 'Camp statement submitted successfully.';
		  }	
		  
		  $statement->save();
		  if($eventtype=="CREATE") {
				
				// send history link in email
				$link = 'statement-history/'.$statement->topic_num.'/'.$statement->camp_num;
				
				Mail::to(Auth::user()->email)->send(new ThankToSubmitterMail(Auth::user(),$link));
				
			} else if($eventtype=="OBJECTION") {
				
				$user = Nickname::getUserByNickName($all['submitter']);
				
				$link = 'statement-history/'.$statement->topic_num.'/'.$statement->camp_num;
				$data['object'] = "#".$statement->id;
				$nickName = Nickname::getNickName($all['nick_name']);
				$data['type'] = 'statement';
				$data['nick_name'] = $nickName->nick_name;
				$data['forum_link'] = 'forum/'.$statement->topic_num.'/'.$statement->camp_num.'/threads';
				$data['subject'] = $data['nick_name']." has objected to your proposed change.";
				
				$receiver = (config('app.env')=="production") ? $user->email : config('app.admin_email');
				Mail::to($receiver)->send(new ObjectionToSubmitterMail($user,$link,$data));
			} else if($eventtype=="UPDATE") { 
			
			   $directSupporter = Support::getDirectSupporter($statement->topic_num,$statement->camp_num);
			    
				$link = 'topic/'.$statement->topic_num.'/'.$statement->camp_num.'?asof=bydate&asofdate='.date('Y/m/d H:i:s',$statement->go_live_time);
				$data['object'] = "#".$statement->id;
				$data['go_live_time'] = date('Y-m-d H:i:s', strtotime('+7 days'));
				$data['type'] = 'statement';
				$nickName = Nickname::getNickName($all['nick_name']);
				
				$data['nick_name'] = $nickName->nick_name;
				$data['forum_link'] = 'forum/'.$statement->topic_num.'/'.$statement->camp_num.'/threads';
				$data['subject'] = "Proposed change to camp statement #".$statement->id." submitted";

			  foreach($directSupporter as $supporter) { 
			    
				$user = Nickname::getUserByNickName($supporter->nick_name_id);
						
				
				$receiver = (config('app.env')=="production") ? $user->email : config('app.admin_email');
				Mail::to($receiver)->send(new PurposedToSupportersMail($user,$link,$data));
				
			  }
			} 
		
        
        return redirect('statement/history/'.$statement->topic_num.'/'.$statement->camp_num)->with(['success'=>$message]);
                
        
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


    public function usersupports(Request $request,$id){
    
        $nickName = Nickname::find($id);
        $namespaces= Namespaces::all();
        return view('user-supports',compact('nickName','namespaces'));
    }

}