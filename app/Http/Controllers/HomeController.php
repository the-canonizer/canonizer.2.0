<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Topic;
use App\Model\Camp;
use DB;

class HomeController extends Controller {

    public function index() {

      
        $topics = Camp::where('camp_name', '=', 'Agreement')
                ->orderBy('submit_time', 'desc')
                ->get()->unique('topic_num');
        
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
