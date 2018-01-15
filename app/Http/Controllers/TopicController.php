<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Library\General;
use App\Model\Topic;
use App\Model\Camp;
use App\Model\Statement;
use DB;
use Validator;

class TopicController extends Controller {
    
    public function __construct()
    {
        //$this->middleware('auth'); //->except('logout');
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('topics.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $all = $request->all();
		
        $validator = Validator::make($request->all(), [
            'topic_name'=>'required',
            'namespace' => 'required',
            'go_live_time'=>'required',
            
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput($request->all());
        }
		
        DB::beginTransaction();
        
        try {
            $topic = new Topic();
            $topic->topic_name = $all['topic_name'];		
			
            $topic->namespace = $all['namespace'];
            $topic->submit_time = time();
            $topic->submitter = Auth::user()->id;
            $topic->go_live_time = strtotime($all['go_live_time']);
            $topic->language = $all['language'];
            $topic->note = $all['note'];
						
			if(isset($all['topic_num'])) {
				
			 $topic->topic_num = $all['topic_num'];	
			 $topic->objector = Auth::user()->id;
			 $topic->submitter = $all['submitter'];
			 $topic->object_reason = $all['object_reason'];
			 $topic->object_time = time();
			 
			 $message = "Topic update submitted successfully. Its under review, once approved it will be visible to all.";
			}
			else {
				
			 $message = "Topic created successfully. Its under review, once approved it will be visible to all.";	
			}
					
			
            $topic->save();
            DB::commit();

            Session::flash('success', $message);
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error', "Fail to create topic, please try later.");
        }

        return redirect()->back();
    }
	
	public function manage_topic($id){
		
		 //			
		$topicnumArray  = explode("-",$id);
		$topicnum       = $topicnumArray[0];
		$topic = Topic::where('topic_num',$topicnum)->latest('submit_time')->first();
        
		
		return view('topics.managetopic',  ['topic'=>$topic]);
		
	}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,$campnum) {
        //			
		$topicnumArray  = explode("-",$id);
		$topicnum       = $topicnumArray[0];
		$topic = Camp::where('topic_num',$topicnum)->where('camp_name','=','Agreement')->latest('submit_time')->first();
        $camp = Camp::where('topic_num',$topicnum)->where('camp_num','=', $campnum)->latest('submit_time')->first();
        $campWithParents = Camp::campNameWithAncestors($camp,'');
        //$nickNames  = DB::table('nick_name')->select('nick_name_id','nick_name')->get();
		
		return view('topics.view',  ['topic'=>$topic,'parentcampnum'=>$campnum,'parentcamp'=>$campWithParents,'camp'=>$camp]);
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
    
    public function create_camp(Request $request, $topicnum,$campnum){
        $topic = Camp::where('topic_num',$topicnum)->where('camp_name','=','Agreement')->latest('submit_time')->first();
        $camp = Camp::where('topic_num',$topicnum)->where('camp_num','=', $campnum)->latest('submit_time')->first();
        $campWithParents = Camp::campNameWithAncestors($camp,'');
		
		$id = Auth::user()->id; 
        $encode = General::canon_encode($id);
		
        $nickNames  = DB::table('nick_name')->select('nick_name_id','nick_name')->where('owner_code',$encode)->get();
        
        return view('topics.camp_create',  ['topic'=>$topic,'parentcampnum'=>$campnum,'parentcamp'=>$campWithParents,'nickNames'=>$nickNames]);
    }
	public function manage_camp($id,$campnum){
		
		$topicnumArray  = explode("-",$id);
		$topicnum       = $topicnumArray[0];
		
        $topic = Camp::where('topic_num',$topicnum)->where('camp_name','=','Agreement')->latest('submit_time')->first();
        $camp = Camp::where('topic_num',$topicnum)->where('camp_num','=', $campnum)->latest('submit_time')->first();
        $campWithParents = Camp::campNameWithAncestors($camp,'');
		
		$id = Auth::user()->id; 
        $encode = General::canon_encode($id);
		
        $nickNames  = DB::table('nick_name')->select('nick_name_id','nick_name')->where('owner_code',$encode)->get();
		$statement = Statement::where('topic_num',$topicnum)->where('camp_num',$campnum)->latest('submit_time')->first();
        
        return view('topics.managecamp',  ['statement'=>$statement,'topic'=>$topic,'camp'=>$camp,'parentcampnum'=>$camp->parent_camp_num,'parentcamp'=>$campWithParents,'nickNames'=>$nickNames]);
    }
    
    public function store_camp(Request $request){
        $all = $request->all();
        $validator = Validator::make($request->all(), [
            'nick_name'=>'required',
            'camp_name' => 'required',
            'title'=>'required',
            'go_live_time'=>'required',
            'note'=>'required',
			'statement'=>'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput($request->all());
        }
        
        
        $camp = new Camp();
        $camp->topic_num = $all['topic_num'];
		
        $camp->parent_camp_num = $all['parent_camp_num'];
        $camp->title = $all['title'];
        $camp->camp_name = $all['camp_name'];
		$camp->submit_time = strtotime(date('Y-m-d H:i:s'));
        $camp->go_live_time = strtotime($all['go_live_time']);
        $camp->language = $all['language'];
        $camp->nick_name_id = $all['nick_name'];
        $camp->note = $all['note'];
		$camp->key_words = $all['keywords'];
		$camp->submitter = Auth::user()->id;
        $camp->url = $all['url'];	

        if(isset($all['camp_num'])) {
		 $camp->camp_num = $all['camp_num'];
		 $camp->objector = Auth::user()->id;
		 $camp->submitter = $all['submitter'];
		 $camp->object_reason = $all['object_reason'];
		 $camp->object_time = time();
		 
		}		
        
        if($camp->save()) {
			
		  $statement = new Statement();	
		  
		  $statement->value = $all['statement'];
		  $statement->topic_num = $all['topic_num'];
		  $statement->camp_num = $camp->camp_num;
		  $statement->note = $all['note'];
		  $statement->submit_time = strtotime(date('Y-m-d H:i:s'));
		  $statement->submitter = Auth::user()->id;
		  $statement->go_live_time = strtotime($all['go_live_time']);
		  $statement->language = $all['language'];
		  
		  if(isset($all['camp_num'])) {
			 $statement->camp_num = $all['camp_num'];
			 $statement->objector = Auth::user()->id;
			 $statement->submitter = $all['submitter'];
			 $statement->object_reason = $all['object_reason'];
			 $statement->object_time = time();
			 
			 $message = 'Camp update submitted Successfully.';
			 
		  } else { 
		    $message = 'Camp Created Successfully.';
		  }	
		  
		  $statement->save();
		  
			
		} else {
			
		  $message = 'Camp not added, please try again.';	
		}
        
        return redirect()->route('home')->with(['success'=>$message]);
                
        
    }

}
