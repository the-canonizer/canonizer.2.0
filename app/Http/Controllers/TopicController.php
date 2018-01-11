<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Model\Topic;
use App\Model\Camp;
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
            $topic->save();
            DB::commit();

            Session::flash('success', "Topic created successfully. Its under review, once approved it will be visible to all");
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error', "Fail to create topic, please try later.");
        }

        return redirect()->back();
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
        $nickNames  = DB::table('nick_name')->select('nick_name_id','nick_name')->get();
        
        return view('topics.camp_create',  ['topic'=>$topic,'parentcampnum'=>$campnum,'parentcamp'=>$campWithParents,'nickNames'=>$nickNames]);
    }
    
    public function store_camp(Request $request){
        $all = $request->all();
        $validator = Validator::make($request->all(), [
            'nick_name'=>'required',
            'camp_name' => 'required',
            'title'=>'required',
            'go_live_time'=>'required',
            'note'=>'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput($request->all());
        }
        
        
        $camp = new Camp();
        $camp->topic_num = $all['topic_num'];
        $camp->parent_camp_num = $all['parent_camp_num'];
        $camp->title = $all['title'];
        $camp->camp_name = $all['camp_name'];
        $camp->go_live_time = strtotime($all['go_live_time']);
        $camp->language = $all['language'];
        $camp->nick_name_id = $all['nick_name'];
        $camp->note = $all['note'];
        
        $camp->save();
        
        return redirect()->route('home')->with(['success'=>'Camp Created Successfully.']);
        
        
        
    }

}
