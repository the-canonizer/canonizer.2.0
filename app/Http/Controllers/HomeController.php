<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Topic;
use App\Model\Camp;
use DB;

class HomeController extends Controller {

    public function index() {

      
        $topics = Camp::getAllAgreementTopic(10,$_REQUEST);
        
        return view('welcome', ['topics' => $topics]);
    }
	
	public function loadtopic(Request $request){
		
		$output = '';
        $id = $request->id;
		$topics = Camp::getAllLoadMoreTopic(10,$_REQUEST,$id);
		
		
		  foreach($topics as $k=>$topic) {
			   $childs = $topic->childrens($topic->topic_num,$topic->camp_num);
			   $title      = preg_replace('/[^A-Za-z0-9\-]/', '-', $topic->title);
						  
			   $topic_id  = $topic->topic_num."-".$title;
			   $url       = url("topic/".$topic_id."/".$topic->camp_num);
			   $camproute = route('camp.create',['topicnum'=>$topic->topic_num,'campnum'=>$topic->camp_num]);			 
                      $output .='<li><span class="';
				
				     
					   $output .='"><i class="fa fa-arrow-right"></i></span> <div class="tp-title"><a href="'.$url.'">'.$topic->title.'</a><div class="badge">48.25</div></div>';
						 
                        if(count($childs) > 0){ 
                            $output .= $topic->campTree($topic->topic_num,$topic->camp_num);
						   	
                        }else{
                            $output .= '<li class="create-new-li"><span><a href="'.$camproute.'">< Create A New Camp ></a></span></li>';
                        }
						$output .='</li>';
						
		  }
		  $output .='<a id="btn-more" class="remove-row" data-id="'.$topic->id.'"></a>';
      echo $output;		  
		
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
