<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Topic;
use App\Model\Camp;
use App\Model\Support;
use App\Model\TopicSupport;
use App\Model\SupportInstance;
use DB;

class HomeController extends Controller {

    public function index() {

      
        $topics = Camp::getAllAgreementTopic(10,$_REQUEST);
        
        return view('welcome', ['topics' => $topics]);
    }
	
	public function loadtopic(Request $request){
		
		$output = '';
        $id = $request->id;
		$topics = Camp::getAllLoadMoreTopic($request->offset,$_REQUEST,$id);
		
		
		
		  foreach($topics as $k=>$topicdata) {
			  
			  $nicknames = $topicdata->GetSupportedNicknames($topicdata->topic_num);
						 
			 $supportDataset = $topicdata->getCampSupport($topicdata->topic_num,$topicdata->camp_num,$nicknames);
			 
			 $count = 0;
			 foreach($supportDataset as  $s) {
				  
				 $count = $count + $s;
			 }
			 $supportDataset[1] = $supportDataset[1] + $count; 
			   
			   $childs = $topicdata->childrens($topicdata->topic_num,$topicdata->camp_num);
			   $title      = preg_replace('/[^A-Za-z0-9\-]/', '-', $topicdata->title);
			   $supportCount = $supportDataset[1];			  
			   $topic_id  = $topicdata->topic_num."-".$title;
			   $url       = url("topic/".$topic_id."/".$topicdata->camp_num);
			   $camproute = route('camp.create',['topicnum'=>$topicdata->topic_num,'campnum'=>$topicdata->camp_num]);			 
                      $output .='<li><span class="';
				
				     
					   $output .='"><i class="fa fa-arrow-right"></i></span> <div class="tp-title"><a href="'.$url.'">'.$topicdata->title.'</a><div class="badge">'.$supportCount.'</div></div>';
						 
                        if(count($childs) > 0){ 
                            $output .= $topicdata->campTree($topicdata->topic_num,$topicdata->camp_num,null,null,$supportDataset);
						   	
                        }else{
                            $output .= '<li class="create-new-li"><span><a href="'.$camproute.'">< Create A New Camp ></a></span></li>';
                        }
						$output .='</li>';
						
		  }
		  isset($topicdata) ? $output .='<a id="btn-more" class="remove-row" data-id="'.$topicdata->id.'"></a>' : '';
      echo $output;		  
		
	}
	public function browse() {

      
        $topics = Camp::getBrowseTopic();
        
        return view('browse', ['topics' => $topics]);
    }
    
    public function recusriveCampDisp($childs){ 
        foreach($childs as $child){
            echo "child --" . $child->title . "<br/>";
            if(count($child->childrens($child->topic_num,$child->camp_num))>0){
                $this->recusriveCampDisp($child->childrens($child->topic_num,$child->camp_num));
            }
        }
    }
	public function supportmigration() {

     if(!isset($_REQUEST['instance'])) {        
		$counter = 0;
		 $stored = array();
        $topics = Support::where('delegate_nick_name_id','!=',0)->groupBy('topic_num','nick_name_id')->orderBy('start','DESC')->get();
		
		$topics1 = Support::groupBy('topic_num','nick_name_id')->orderBy('start','DESC')->get();
		
        $alreadyMigrated = TopicSupport::get();
	     //echo "total topic migrating is :".count($topics)." </br>";
		 
		if(count($alreadyMigrated)==0) { 
	    foreach($topics as $key=>$topic) {
			
			$topicSupport = new TopicSupport();
			
			$topicSupport->topic_num = $topic->topic_num;
			$topicSupport->nick_name_id = $topic->nick_name_id;
			$topicSupport->delegate_nick_id = $topic->delegate_nick_name_id;
			$topicSupport->submit_time = $topic->start;
			
			$topicSupport->save();
			
			$stored[] = $topic->topic_num.'-'.$topic->nick_name_id;
			
			$counter++;
			
		}
		
		foreach($topics1 as $key=>$topic) {
			
			
			if(!in_array($topic->topic_num.'-'.$topic->nick_name_id,$stored)) {
			$topicSupport = new TopicSupport();
			
			$topicSupport->topic_num = $topic->topic_num;
			$topicSupport->nick_name_id = $topic->nick_name_id;
			$topicSupport->delegate_nick_id = $topic->delegate_nick_name_id;
			$topicSupport->submit_time = $topic->start;
			
			$topicSupport->save();
			
			
			 $counter++;
			}	
			
		}
		echo "total ".$counter." topic migrated."; exit;
	  }	else { echo "Data already migrated."; exit;}
	 } else {
		 
		$topic = TopicSupport::orderBy('id','ASC')->get();
        
		$alreadyMigrated = SupportInstance::get();
		
		if(count($topic) > 0 && count($alreadyMigrated) == 0 ) {
		
        foreach($topic as $data) {
			
			$supportCamp = Support::where('topic_num',$data->topic_num)->where('nick_name_id',$data->nick_name_id)->orderBy('support_order','ASC')->get();
			
			foreach($supportCamp as $d) {
				
				$sinstance = new SupportInstance();
				
				$sinstance->topic_support_id = $data->id;
				$sinstance->camp_num = $d->camp_num;
				$sinstance->support_order = $d->support_order + 1;
				$sinstance->submit_time  = $d->start;
				$sinstance->status = 0;
				
				$sinstance->save();
				
			}
			
		}	

        echo "Data migrated successfully....";	exit;
		} else {
			
			echo "There is no topic available to migrate or data already migrated."; exit;
		}		
		 
	 }	
		
        
    }

}
