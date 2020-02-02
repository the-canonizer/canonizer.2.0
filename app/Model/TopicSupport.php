<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Model\Nickname;
use App\Model\Algorithm;
use Illuminate\Database\Eloquent\Collection;

class TopicSupport extends Model {

    protected $table = 'topic_support';
    public $timestamps = false;    
    protected static $tempArray = [];

    protected static $supports = [];

   
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

    public static function traverseChildTree($algorithm,$topicnum,$campnum,$delegateNickId,$parent_support_order,$multiSupport){

        /*Delegated Support */

        $delegatedSupports =  session("topic-support-tree-$topicnum")->filter(function($item) use ($delegateNickId){
                return $item->delegate_nick_name_id == $delegateNickId;
        });
        
        $array = [];
        foreach($delegatedSupports as $support){
            
            $supportPoint = Algorithm::{$algorithm}($support->nick_name_id); 
            $array[$support->nick_name_id]['index']=$support->nick_name_id;
             //dd($array);
            if($multiSupport){
                $array[$support->nick_name_id]['score'] = round($supportPoint / (2 ** ($parent_support_order)),2);
            }else{
                $array[$support->nick_name_id]['score'] = $supportPoint;
            }
            $array[$support->nick_name_id]['children'] = self::traverseChildTree($algorithm,$topicnum,$campnum,$support->nick_name_id,$parent_support_order,$multiSupport);
        }

        return $array;
    }
    /*1 Person :: 1 Vote Nicknames*/
    public static function traverseTree($algorithm,$topicnum,$campnum,$delegateNickId=0){
        
        $as_of_time = time();
		if(isset($_REQUEST['asof']) && $_REQUEST['asof']=='bydate'){
			$as_of_time = strtotime($_REQUEST['asofdate']);
		}

        /*Fetching direct support to camp*/

        $supports = session("topic-support-tree-$topicnum")->filter(function($item) use ($campnum,$delegateNickId){
                return $item->delegate_nick_name_id == 0 && $item->camp_num == $campnum;
        });
        
        
        $array = [];
        foreach($supports as $support){
            $nickNameSupports =  session("topic-support-tree-$topicnum")->filter(function($item) use($support) {
                return $item->nick_name_id == $support->nick_name_id;
            });

            $supportPoint = Algorithm::{$algorithm}($support->nick_name_id);
			$currentCampSupport =  $nickNameSupports->filter(function ($item) use($campnum)
			{
				return $item->camp_num == $campnum; /* Current camp support */
			})->first();


            $array[$support->nick_name_id]['score'] = 0;
            $array[$support->nick_name_id]['children'] = [];

            $multiSupport = false;
			// echo $currentCampSupport->support_order;
			if($currentCampSupport){
				if($nickNameSupports->count() > 1){
                    $multiSupport = true;
					
					$array[$support->nick_name_id]['score']=round($supportPoint / (2 ** ($currentCampSupport->support_order)),2);
						
				}else if($nickNameSupports->count() == 1){
					
					
					 $array[$support->nick_name_id]['score']=$supportPoint;
					
				}
			
            
            $array[$support->nick_name_id]['index']=$support->nick_name_id;
            $array[$support->nick_name_id]['children'] = self::traverseChildTree($algorithm,$topicnum,$campnum,$support->nick_name_id,$currentCampSupport->support_order,$multiSupport);
            }
        }

        return $array;
        
    }

    public static function getSupportNumber($topicnum,$campnum,$supports){
        $i = 0;
        $flag = false;
        if($supports && sizeof($supports) > 0){
            foreach($supports as $key => $spp){
                $j = 0;
                  if(isset($spp['array']) && $key == $topicnum){
                    ksort($spp['array']);
                    foreach($spp['array'] as $k => $support_order){
                        foreach($support_order as $support){
                          if($campnum == $support['camp_num']){
                                 $i = $k;
                                 $flag = true;
                                  break;
                                }
                            } 
                    }
                    if($flag){
                        break;
                    }                            
                }else{
                    $j++;
                    $i = $j;
                }
            }
        }
        return $i;
    }
    public static function buildTree($topicnum,$campnum,$traversedTreeArray,$parentNode=false){
        
        $html= "";
        foreach($traversedTreeArray as $array){
            $nickName = Nickname::where('id',$array['index'])->first();
            $topicData = Topic::where('topic_num',$topicnum)->latest('submit_time')->get();
            $namespace_id = (isset($topicData[0])) ? $topicData[0]->namespace_id:1;
            $supports = $nickName->getSupportCampList($namespace_id);
            $support_number = self::getSupportNumber($topicnum,$campnum,$supports);
            $support_txt = ($support_number) ? $support_number.":": '';
             if($parentNode){
                $html.= "<li class='main-parent'><a href='".route('user_supports',$nickName->id)."?topicnum=".$topicnum."&campnum=".$campnum."&namespace=".$namespace_id."#camp_".$topicnum."_".$campnum."'>{$support_txt}{$nickName->nick_name}</a><div class='badge'>".round($array['score'],2)."</div>";
                $html.='<a href="'.url('support/'.$topicnum.'/'.$campnum.'-'.$array['index']).'" class="btn btn-info">Delegate Your Support</a>';
            
            }else{
                $html.= "<li><a href='".route('user_supports',$nickName->id)."?topicnum=".$topicnum."&campnum=".$campnum."&namespace=".$namespace_id."#camp_".$topicnum."_".$campnum."'>{$nickName->nick_name}</a><div class='badge'>".round($array['score'],2)."</div> ";
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
      if(is_array($traversedTreeArray)) {
       uasort($traversedTreeArray, function($a, $b) {
            return $a['score'] < $b['score'];
       });
	  }
	  
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

   

    public static function topicSupportTree($algorithm,$topicnum,$campnum){
        
        $as_of_time = time();
		if(isset($_REQUEST['asof']) && $_REQUEST['asof']=='bydate'){
           $as_of_time = strtotime($_REQUEST['asofdate']);
		}
        if(!session("topic-support-tree-$topicnum")){
            $data = Support::where('topic_num','=',$topicnum)
            //->where('delegate_nick_name_id',$delegateNickId)
            ->whereRaw("(start <= $as_of_time) and ((end = 0) or (end >= $as_of_time))")
            //->where('camp_num',$campnum)
            ->orderBy('start','DESC')
            ->select(['support_order','camp_num','topic_num','nick_name_id','delegate_nick_name_id'])
            //->select(['support_order','camp_num'])
            ->get();
            session(["topic-support-tree-$topicnum"=>$data]);
        }
       
        $traversedSupportCountTreeArray = self::sortTraversedSupportCountTreeArray(self::sumTranversedArraySupportCount(self::traverseTree($algorithm,$topicnum,$campnum)));
         return self::buildTree($topicnum,$campnum,$traversedSupportCountTreeArray,true);
    }

   
}
