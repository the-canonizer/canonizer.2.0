<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Library\General;
use App\Library\Wiky;
use App\Library\wikiparser\wikiParser;
use App\Model\Topic;
use App\Model\Camp;
use App\Model\Statement;
use App\Model\Nickname;
use DB;
use Validator;
use App\Model\Namespaces;
use App\Model\NamespaceRequest;

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
            'topic_name'=>'required',
            'namespace' => 'required',
            'create_namespace'=>'required_if:namespace,other',
            
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput($request->all());
        }
		
        DB::beginTransaction();
        
        try {
			
            $topic = new Topic();
            $topic->topic_name = $all['topic_name'];		
			
            $topic->namespace_id = $all['namespace'];
            $topic->submit_time = time();
            $topic->submitter_nick_id = $all['nick_name'];
            $topic->go_live_time = strtotime(date('Y-m-d H:i:s', strtotime('+7 days')));
            $topic->language = $all['language'];
            $topic->note = $all['note'];
						
			if(isset($all['topic_num'])) {
				
			 $topic->topic_num = $all['topic_num'];
			 
			 if(isset($all['objection']) && $all['objection']==1) {
				 $topic->objector_nick_id = $all['nick_name'];
				 $topic->submitter_nick_id = $all['submitter'];
				 $topic->object_reason = $all['object_reason'];
				 $topic->object_time = time();
			 }			 
			 
			 $message ="Topic update submitted successfully. Its under review, once approved it will be live.";
			}
			else {
			 $message ="Topic created successfully. Its under review, once approved it will be live.";	
			}

            $topic->save();
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
        
        $nickNames   = Nickname::personNickname();
		$request->merge(['namespace'=>$topic->namespace_id]);
		if(!count($topic)) return back();
        $namespaces= Namespaces::all();
		
		return view('topics.managetopic', compact('topic','objection','nickNames','namespaces'));
		
	}

    /**
     * Display the specified topic data with camps/statement.
     *
     * @param  int  $id = topic_num, $parentcampnum
     * @return \Illuminate\Http\Response
     */
    public function show($id,$parentcampnum) {
        			
		$topicnumArray  = explode("-",$id);
		$topicnum       = $topicnumArray[0];
		
		$topic      = Camp::getAgreementTopic($topicnum);
        $camp       = Camp::getLiveCamp($topicnum,$parentcampnum);
        $parentcamp = Camp::campNameWithAncestors($camp,'');
        
		$wiky=new Wiky;
		
		$WikiParser  = new wikiParser;

		
		return view('topics.view',  compact('topic','parentcampnum','parentcamp','camp','wiky','WikiParser'));
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
		
		
        $nickNames  = Nickname::personNickname();
        
        return view('topics.camp_create',  compact('topic','parentcampnum','parentcamp','nickNames'));
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
				
        $nickNames  = Nickname::personNickname();
		
        return view('topics.managecamp',  compact('objection','topic','camp','parentcampnum','parentcamp','nickNames'));
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
	
		$nickNames     = Nickname::personNickname();
       
        return view('topics.managestatement',  compact('objection','nickNames','topic','statement','parentcampnum','parentcamp'));
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
        $validator = Validator::make($request->all(), [
            'nick_name'=>'required',
            'camp_name' => 'required',
            'title'=>'required',
            'note'=>'required',
			
        ]);
		
        if ($validator->fails()) { print_r($validator->errors());
            return back()->withErrors($validator->errors())->withInput($request->all());
        }
        
        
        $camp = new Camp();
        $camp->topic_num = $all['topic_num'];
		
        $camp->parent_camp_num = $all['parent_camp_num'];
        $camp->title = $all['title'];
        $camp->camp_name = $all['camp_name'];
		$camp->submit_time = strtotime(date('Y-m-d H:i:s'));
        $camp->go_live_time = strtotime(date('Y-m-d H:i:s', strtotime('+7 days')));
        $camp->language = $all['language'];
        $camp->camp_about_nick_id = $all['nick_name'];
        $camp->note = $all['note'];
		$camp->key_words = $all['keywords'];
		$camp->submitter_nick_id = $all['nick_name'];
        $camp->camp_about_url = $all['url'];	

        if(isset($all['camp_num'])) {
		 $camp->camp_num = $all['camp_num'];
		 $camp->submitter_nick_id = $all['nick_name'];
		 if(isset($all['objection']) && $all['objection']==1) {
		 
			 $camp->objector_nick_id = $all['nick_name'];
			  $camp->submitter_nick_id = $all['submitter'];
			 $camp->object_reason = $all['object_reason'];
			 $camp->object_time = time();
		 }	 
		 $message = 'Camp update submitted Successfully.';
		} else {
			
			$message = 'Camp Created Successfully.';
		}		
        
        if($camp->save()) {
			
		  if(!isset($all['camp_num'])) {
			  $statement = new Statement();	
			  
			  $statement->value = $all['statement'];
			  $statement->topic_num = $all['topic_num'];
			  $statement->camp_num = $camp->camp_num;
			  $statement->note = $all['note'];
			  $statement->submit_time = strtotime(date('Y-m-d H:i:s'));
			  $statement->submitter_nick_id = $all['nick_name'];
			  $statement->go_live_time = strtotime(date('Y-m-d H:i:s', strtotime('+7 days')));
			  $statement->language = $all['language'];
					  
			  $statement->save();
		  }
			
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
        $validator = Validator::make($request->all(), [
            'statement'=>'required',
            'note' => 'required',
           
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
		  $statement->go_live_time = strtotime(date('Y-m-d H:i:s', strtotime('+7 days')));
		  $statement->language = $all['language'];
		  
		  if(isset($all['camp_num'])) {
			 $statement->camp_num = $all['camp_num'];
			 $statement->submitter_nick_id = $all['nick_name'];
			 if(isset($all['objection']) && $all['objection']==1) {
		 
				 $statement->objector_nick_id = $all['nick_name'];
				 $statement->submitter_nick_id = $all['submitter'];
				 $statement->object_reason = $all['object_reason'];
				 $statement->object_time = time();
			 }	
			 
			 $message = 'Camp statement update submitted successfully.';
			 
		  } else { 
		    $message = 'Camp statement submitted successfully.';
		  }	
		  
		  $statement->save();
		   
		
        
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
