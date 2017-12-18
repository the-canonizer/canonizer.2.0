<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Topic;
use App\Model\Camp;
use DB;

class HomeController extends Controller {

    public function index() {

        // $topics = Topic::all();
//        $topics = Topic::with(['camps' => function($q){
//                        // Query the name field in status table
//                        $q->where('camp_num', '=', 1); // '=' is optional
//                        $q->where(function($q1){
//                             $q1->where('parent_camp_num' , '=','');
//                             $q1->orWhere('parent_camp_num','=',0);
//                             $q1->orWhere('parent_camp_num','=',null);
//                         })->orderBy('submit_time', 'desc');
//                    }])
//                ->groupBy('topic_num')
//                ->orderBy('submit_time', 'desc')
//                ->get();
        // Agreement is copy of topic
        $topics = Camp::where('camp_name', '=', 'Agreement')
                ->groupBy('topic_num')
                ->orderBy('submit_time', 'desc')
                ->get();
        
        
//        foreach($topics as $k=>$topic){
//            
//            echo $topic->title . "<br/>";
//            if(count($topic->childrens($topic->topic_num,$topic->camp_num))>0){
//                $this->recusriveCampDisp($topic->childrens($topic->topic_num,$topic->camp_num));
//            }
//        }
//        exit;
        
       
       

        return view('welcome', ['topics' => $topics]);
    }
    
    public function recusriveCampDisp($childs){ 
        foreach($childs as $child){
            echo "child --" . $child->title . "<br/>";
            if(count($child->childrens($child->topic_num,$child->camp_num))>0){
                $this->recusriveCampDisp($child->childrens($child->topic_num,$child->camp_num));
            }
        }
    }

}
