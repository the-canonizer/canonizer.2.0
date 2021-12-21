<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use DB;
use Validator;
use App\Model\NewsFeed;
use App\Model\Camp;
class NewsFeedController extends Controller
{
   
    public function create($topic,$campnum){
        $topicnumArray = explode("-", $topic);
        $topicnum = $topicnumArray[0];
        $campnumArray = explode("-", $campnum);
        $camp_num = $campnumArray[0];
        return view('news.create', compact('topicnum', 'campnum','camp_num','topic'));        
    }
    
    public function store(Request $request){
        $all = $request->all();
        $regex = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';
       
        $validatorArray = [
            'display_text'=>'required|max:256|regex:/^[a-zA-Z0-9.\s]+$/',
            "link" => array("required", "regex:".$regex)

        ];
        $messages = [];
        $messages["display_text.required"]='Display text is required.';
        $messages["display_text.regex"]='Display text can only contain space, full stop (.) and alphanumeric characters.';
        $messages["display_text.max"]='Display text may not be greater than 256 characters.';
        $messages["link.regex"]='Link is invalid. (Example: https://www.example.com?post=1234)';
        $messages["link.required"]='Link is required.';
        
        $validator = Validator::make($request->all(), $validatorArray,$messages);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput($request->all());
        }
         $campnum = explode("-", $all['camp_num'])[0];
       $topicnum = explode("-",$all['topic_num'])[0];
        $news = new NewsFeed();
        $news->topic_num = $topicnum;
        $news->camp_num = $campnum;
        $news->display_text = $all['display_text'];
        $news->link = $all['link'];        
        $news->available_for_child = isset($all['available_for_child']) ? $all['available_for_child'] : 0 ;
        $news->submit_time = strtotime(date('Y-m-d H:i:s'));
        $news->save();
        return redirect(\App\Model\Camp::getTopicCampUrl($topicnum,$campnum))->with(['success' => "News added successfully"]);
    }
    
    public function edit($topic,$campnum){
        $topicnumArray = explode("-", $topic);
        $topicnum = $topicnumArray[0];
        $campnumArray = explode("-", $campnum);
        $camp_num = $campnumArray[0];
        $news = NewsFeed::where('topic_num','=',$topicnum)->where('camp_num','=',$camp_num)->where('end_time','=',null)->orderBy('order_id','ASC')->get();
        
        return view('news.edit', compact('topicnum', 'campnum','camp_num','topic','news'));        
        
    }
    
    public function update(Request $request){
       $all = $request->all();
       $regex = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';
        $validatorArray = [
            'display_text.*'=>'required|max:256|regex:/^[a-zA-Z0-9.\s]+$/',
            "link.*" => array("required", "regex:".$regex)
        ];
        foreach($all['news_order'] as $key=>$order){
            $messages["display_text.".$key .".required"]='Display text is required.';
            $messages["display_text.".$key.".regex"]='Display text can only contain space, full stop (.) and alphanumeric characters.';
			$messages["display_text.".$key .".max"]='Display text may not be greater than 256 characters.';
            $messages["link.".$key .".regex"]='Link is invalid. (Example: https://www.example.com?post=1234)';
            $messages["link.".$key .".required"]='Link is required.';
        }
        $validator = Validator::make($request->all(), $validatorArray,$messages);

        if ($validator->fails()) {
            //echo "<pre>"; print_r($validator->errors()); exit;
            return back()->withErrors($validator->errors())->withInput($request->all());
        }
       $campnum = explode("-", $all['camp_num'])[0];
       $topicnum = explode("-",$all['topic_num'])[0];
       $submittime = strtotime(date('Y-m-d H:i:s'));
       //deactive previous
       DB::table('news_feed')->where('camp_num','=',$campnum)
               ->where('topic_num','=',$topicnum)
               ->where('end_time','=',null)
               ->update(['end_time'=> $submittime]);
       
       //New entery
       foreach($all['news_order'] as $key=>$order){
            $news = new NewsFeed();
            $news->topic_num = $topicnum;
            $news->camp_num =$campnum;
            $news->display_text = $all['display_text'][$key];
            $news->link = $all['link'][$key];       
            $news->available_for_child = isset($all['available_for_child'][$key]) ? $all['available_for_child'][$key] : 0 ;
            $news->submit_time = strtotime(date('Y-m-d H:i:s'));
            $news->save();
       }
       return redirect(\App\Model\Camp::getTopicCampUrl($topicnum,$campnum))->with(['success' => "News updated successfully"]);
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if($id){
            $newsData = NewsFeed::where('id','=',$id)->first();
            $topicnum = $newsData->topic_num;
            $campnum = $newsData->camp_num;
            NewsFeed::find($id)->delete();
            return redirect(\App\Model\Camp::getTopicCampUrl($topicnum,$campnum))->with(['success' => "News deleted successfully"]);
        }
    }
    
}
