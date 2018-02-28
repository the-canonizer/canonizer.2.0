<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Model\Nickname;
use App\Model\Algorithm;

class TopicSupport extends Model {

    protected $table = 'topic_support';
    public $timestamps = false;    
    protected static $tempArray = [];

   
    public static function boot() {
        
    }   
	
	public function nickname() {
        return $this->hasOne('App\Model\Nickname', 'id', 'nick_name_id');
    }
	public function camp() {
        return $this->hasOne('App\Model\Camp', 'camp_num', 'camp_num');
    }
	public function topic() {
        return $this->hasOne('App\Model\Topic', 'topic_num', 'topic_num');
    }
	
	public function delegatednickname() {
        return $this->hasOne('App\Model\Nickname', 'id', 'delegate_nick_id');
    }
	public function campsupport() {
        return $this->hasMany('App\Model\SupportInstance', 'topic_support_id', 'id')->groupBy('camp_num')->orderBy('support_order','ASC')->orderBy('submit_time','DESC');
    }

    public static function reducedSum($array){
        $sum = $array['score'];
        try{
		  if(isset($array['children']) && is_array($array['children'])) {	
			foreach($array['children'] as $arr){
					$sum=$sum + self::reducedSum($arr);
			}
		  }
        }catch(\Exception $e){
            return $sum;
        }
        return $sum;
    }

    /*1 Person :: 1 Vote Nicknames*/
    public static function traverseTree($topicnum,$campnum,$delegateNickId=0){
        
        $as_of_time = time();
		if(isset($_REQUEST['asof']) && $_REQUEST['asof']=='date'){
			$as_of_time = strtotime($_REQUEST['asofdate']);
		}

        $supports = Support::where('topic_num','=',$topicnum)
            ->where('delegate_nick_name_id',$delegateNickId)
            ->whereRaw("(start < $as_of_time) and ((end = 0) or (end > $as_of_time))")
            ->where('camp_num',$campnum)
            ->orderBy('start','DESC')
            //->select(['support_order','camp_num'])
            ->get();
        
        $array = [];
        foreach($supports as $support){
            $nickNameSupports = Support::where('topic_num','=',$topicnum)
				->where('nick_name_id',$support->nick_name_id)
				->whereRaw("(start < $as_of_time) and ((end = 0) or (end > $as_of_time))")
				->orderBy('start','DESC')
				->select(['support_order','camp_num'])
				->get();

            $supportPoint = Algorithm::{session('defaultAlgo')}($support->nick_name_id);
			$currentCampSupport =  $nickNameSupports->filter(function ($item) use($campnum)
			{
				return $item->camp_num == $campnum; /* Current camp support */
			})->first();
            $array[$support->nick_name_id]['score'] = 0;
			if($currentCampSupport){
				if($nickNameSupports->count() > 1){
					$array[$support->nick_name_id]['score']=round($supportPoint / (2 ** ($currentCampSupport->support_order+1)),2);
				}else if($nickNameSupports->count() == 1){
					$array[$support->nick_name_id]['score']=$supportPoint;
				}
			}

            $array[$support->nick_name_id]['index']=$support->nick_name_id;
            $array[$support->nick_name_id]['children'] = self::traverseTree($topicnum,$campnum,$support->nick_name_id);
        }

        return $array;
        /*
        
        $supports = TopicSupport::join('support_instance','support_instance.topic_support_id','=','topic_support.id')
        ->where('topic_num', '=', $topicnum)
        ->where('support_instance.camp_num',$campnum)
        ->where('delegate_nick_id',$delegateNickId)
        ->orderBy('topic_support.submit_time','DESC')
        ->select('topic_support.*')
        ->get();

        $array = [];
        foreach($supports as $support){
            $array[$support->nick_name_id]=[];
            $campsupports = $support->campsupport;
            $supportCount  =  $campsupports->count(); 
            $supportPoint = $delegateNickId ? .5 : 1;
            if($supportCount > 1 ){
                $campSupport =  $campsupports->where('camp_num',$campnum)->first();
                $array[$support->nick_name_id]['score']=round($supportPoint / (2 ** ($campSupport->support_order)),2);
            }else if($supportCount == 1){
                $array[$support->nick_name_id]['score']=$supportPoint;
            }
            $array[$support->nick_name_id]['index']=$support->nick_name_id;
            $array[$support->nick_name_id]['children'] = self::traverseTree($topicnum,$campnum,$support->nick_name_id);
        }
        return $array;*/
    }

    public static function buildTree($topicnum,$campnum,$traversedTreeArray,$parentNode=false){
        
        $html= "";
        foreach($traversedTreeArray as $array){
            $nickName = Nickname::where('id',$array['index'])->first();
            if($parentNode){
                $html.= "<li class='main-parent'><a href='#'>{$nickName->nick_name}</a><div class='badge'>".$array['score']."</div>";
                $html.='<a href="'.url('support/'.$topicnum.'/'.$campnum.'-'.$array['index']).'" class="btn btn-info">Delegate Your Support</a>';
            
            }else{
                $html.= "<li><a href='#'>{$nickName->nick_name}</a><div class='badge'>".$array['score']."</div> ";
                $html.='<a href="'.url('support/'.$topicnum.'/'.$campnum.'-'.$array['index']).'" class="btn btn-info">Delegate Your Support</a>';
            }
            $html.="<ul>";
            $html.=self::buildTree($topicnum,$campnum,$array['children'],false);
            $html.="</ul></li>";
            
        
        }
        return $html;
    }

    public static function sumTranversedArraySupportCount($traversedTreeArray=array()){
       if(isset($traversedTreeArray) && is_array($traversedTreeArray)) {
        foreach($traversedTreeArray as $key => $array){
           $traversedTreeArray[$key]['score']=self::reducedSum($array);
           $traversedTreeArray[$key]['children']=self::sumTranversedArraySupportCount($array['children']);
        }
	   }	

       uasort($traversedTreeArray, function($a, $b) {
            return $a['score'] < $b['score'];
       });
	  
        return $traversedTreeArray;

    }

    public static function sortTraversedSupportCountTreeArray($traversedTreeArray){
        $array = array_values($traversedTreeArray);
        usort($array,'self::sortByOrder');
        return $array;
    }

    public static function sortByOrder($a, $b)
	{
        $a = $a['score'];
        $b = $b['score'];

        if ($a == $b) return 0;
        return ($a > $b) ? -1 : 1;
	}

   

    public static function topicSupportTree($topicnum,$campnum){
        //return 11;
        $traversedSupportCountTreeArray = self::sortTraversedSupportCountTreeArray(self::sumTranversedArraySupportCount(self::traverseTree($topicnum,$campnum)));
        return self::buildTree($topicnum,$campnum,$traversedSupportCountTreeArray,true);
    }

   
}
