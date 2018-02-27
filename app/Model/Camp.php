<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Nickname;
use DB;
use App\Model\Algorithm;
use App\Model\TopicSupport;		

class Camp extends Model {

    protected $table = 'camp';
    public $timestamps = false;
    public $support_order = 0;
    protected static $tempArray = [];
	protected static $childtempArray = [];
	protected static $chilcampArray = [];
	protected static $traversetempArray = [];
	
    const AGREEMENT_CAMP = "Agreement";
	
    public function topic() {
        return $this->hasOne('App\Model\Topic', 'topic_num', 'topic_num');
    }
	public function nickname() {
        return $this->hasOne('App\Model\Nickname', 'id', 'camp_about_nick_id');
    }
	public function objectornickname() {
        return $this->hasOne('App\Model\Nickname', 'id', 'objector_nick_id');
    }
	public function submitternickname() {
        return $this->hasOne('App\Model\Nickname', 'id', 'submitter_nick_id');
    }
    public static function boot() {
        static::created(function ($model) {
            if ($model->camp_num == '' || $model->camp_num == null) {
                $nextCampNum = DB::table('camp')->where('topic_num','=',$model->topic_num)->max('camp_num');
                $nextCampNum++;
                $model->camp_num = $nextCampNum;
                $model->update();
            }
        });
    }

    public function children() {
        return $this->hasMany('App\Model\Camp', 'parent_camp_num', 'camp_num');
    }

    public function scopeChildrens($query, $topicnum, $parentcamp,$campnum=null,$filter=array()) {
        
		 if($campnum !=null)
			$query->where('camp_num', '=', $campnum);
			
		if(!isset($_REQUEST['asof']) || (isset($_REQUEST['asof']) && $_REQUEST['asof']=="default")) {
		
		 $childs = $query->where('topic_num', '=', $topicnum)
                ->where('parent_camp_num', '=', $parentcamp)
                ->where('camp_name', '!=', 'Agreement')  
                ->where('objector_nick_id', '=', NULL)
                ->whereRaw('go_live_time in (select max(go_live_time) from camp where topic_num='.$topicnum.' and objector_nick_id is null and go_live_time < "'.time().'" group by camp_num)')				
                ->where('go_live_time','<',time())
                ->groupBy('camp_num')				
				->orderBy('submit_time', 'desc')
                ->get();
		} else {
			
			if(isset($_REQUEST['asof']) && $_REQUEST['asof']=="review") {
			
			 $childs = $query->where('topic_num', '=', $topicnum)
                ->where('parent_camp_num', '=', $parentcamp)
                ->where('camp_name', '!=', 'Agreement')  
				->whereRaw('go_live_time in (select max(go_live_time) from camp where topic_num='.$topicnum.' and objector_nick_id is null group by camp_num)')				
                
                ->orderBy('submit_time', 'desc')
				->groupBy('camp_num')
                ->get();	
				
			} else if(isset($_REQUEST['asof']) && $_REQUEST['asof']=="bydate") {
				
				$asofdate =  strtotime(date('Y-m-d H:i:s', strtotime($_REQUEST['asofdate'])));
			
			  $childs = $query->where('topic_num', '=', $topicnum)
                ->where('parent_camp_num', '=', $parentcamp)
                ->where('camp_name', '!=', 'Agreement')  
                ->where('objector_nick_id', '=', NULL)
				->whereRaw('go_live_time in (select max(go_live_time) from camp where topic_num='.$topicnum.' and objector_nick_id is null group by camp_num)')				
                ->where('go_live_time','<',$asofdate) 				
                ->orderBy('submit_time', 'desc')
				->groupBy('camp_num')
                ->get();//->unique('camp_num','topic_num');
			} 
		}		
				
        return $childs;
    }
	public function scopeStatement($query, $topicnum, $campnum) { 
		
        $statement = Statement::getLiveStatement($topicnum,$campnum);
								
        return $statement;
    }
	public function scopeGetSupportedNicknames($query, $topicnum,$campnum=null) {
        $query = TopicSupport::where('topic_num', '=', $topicnum)
		             ->groupBy('nick_name_id');
					 
	    if($campnum!=null) {
		
        $query = TopicSupport::join('support_instance','support_instance.topic_support_id','=','topic_support.id')
		                       ->where('support_instance.camp_num','=',$campnum)
							   ->where('topic_num', '=', $topicnum)
		                       ->groupBy('nick_name_id');		
		}				 
					 
        return $nicknames = $query->get();
    }

	public function scopeGetSupportByNickname($query, $topicnum,$nicknameId) {
        $support = TopicSupport::where('topic_num', '=', $topicnum)
		             ->where('nick_name_id', '=', $nicknameId)
					 ->groupBy('topic_num')
					 ->orderBy('submit_time','DESC')
		             ->first();
        return $support;
    }

    public function scopeCampNameWithAncestors($query, $camp, $campname = '') {
        
		if(!empty($camp)) {
			if ($campname != '') {
				$url = url('topic/'.$camp->topic_num.'/'.$camp->camp_num);
				$campname = "<a href='".$url."'>".$camp->camp_name . '</a> / ' . $campname;
			} else {
				$campname = $camp->camp_name;
			}
			if ($camp->parent_camp_num) {
				$pcamp = Camp::where('topic_num', $camp->topic_num)->where('camp_num', $camp->parent_camp_num)->groupBy('camp_num')->orderBy('submit_time', 'desc')->first();
				return self::campNameWithAncestors($pcamp, $campname);
			}
		}
        return $campname;
    }
		
	// function to get camps support count
	
    public function getNicknameSupport($nickname,$campnum) {
		
		 $campSupport[$campnum] = 0;
						
		 if(!empty($nickname)) {
			 
						$result = $this->GetSupportByNickname($nickname->topic_num,$nickname->nick_name_id);
						
						$support = $result->campsupport;
						
						$supportCount = count($support);
					
						
                          $assignment = 0;
                          $deduction = 0;
						  $remainder = 1;	  
                          foreach($support as $skey=>$sdata) {
							  
							  if($supportCount ==1) {
							
								 $campSupport[$sdata->camp_num] = isset($campSupport[$sdata->camp_num]) ? $campSupport[$sdata->camp_num] + 1 : 1;
								 $remainder = 0;
								
							  } 
							  /*else if($supportCount ==1){
								  
								  $campSupport[$sdata->camp_num] = isset($campSupport[$sdata->camp_num]) ? $campSupport[$sdata->camp_num] + 1 : 1;
							  } */
							  else {
							  
							   if($skey==0 && $supportCount > 1) {
								   
								
								  $campSupport[$sdata->camp_num] = isset($campSupport[$sdata->camp_num]) ? $campSupport[$sdata->camp_num] + 0.5 : 0.5;
                                  $deduction = 1;  	
								  $remainder = $remainder - 0.5;
								   
							   }
							   else if($skey==0 && $supportCount == 1) {
								   
								  $campSupport[$sdata->camp_num] = isset($campSupport[$sdata->camp_num]) ? $campSupport[$sdata->camp_num] + 1 : 1;
								  $remainder = 0;
								  $deduction = 1;			  
								   
							   }
							   else {  
								   
								  $campSupport[$sdata->camp_num] = isset($campSupport[$sdata->camp_num]) ? $campSupport[$sdata->camp_num] + $assignment : $assignment;
								   
							   }
							   
							   $newCounter  =  $supportCount - $deduction;
							    
                                if($newCounter == 1 )								
								  $assignment  =  $remainder;
							    else if($newCounter > 1 ) {
									
								   $assignment  = round($remainder / 2 , 2);
                                   $newCounter  = $newCounter - 1;
                                   $remainder   = $remainder - $assignment;								   
								}
							 }  
							  
						  }  						  
						
					}		
		
      return $campSupport;		
		
	}
	
	public function getCampSupport($topicnum,$campnum,$nicknames=array()) {
			
		
					$campSupport[$campnum] = 0;
					//echo count($nicknames); die;
					if(count($nicknames) > 0 ) {
					foreach($nicknames as $key=>$nickname) {
						
						$result = $this->GetSupportByNickname($topicnum,$nickname->nick_name_id);
						//echo $topicnum."</br>";
						//echo $nickname->nick_name_id."<br/>";
						$support = $result->campsupport;
						//echo count($support); die;
						$supportCount = count($support);
					 
						
                          $assignment = 0;
                          $deduction = 0;
						  $remainder = 1;	  
                          foreach($support as $skey=>$sdata) {
							  
							 // $sdata->parent_camp_num = ($sdata->parent_camp_num==NULL) ? 1 : 0;
							 // echo $sdata->camp->parent_camp_num; die;
							  if($supportCount ==1) {
							
								//$campSupport[$sdata->camp_num] = isset($campSupport[$sdata->camp_num]) ? $campSupport[$sdata->camp_num] + 1 : 1;
							    
								 //$remainder = 0;
								
								$campSupport[$sdata->camp_num] = isset($campSupport[$sdata->camp_num]) ? $campSupport[$sdata->camp_num] + 1 : 1;	
								//$campSupport[$sdata->camp->parent_camp_num] = isset($campSupport[$sdata->camp->parent_camp_num]) ? $campSupport[$sdata->camp->parent_camp_num] + 1 : 1;
							  } 
							 
							  else {
								  
								  
							    $campSupport[$sdata->camp_num] = round(1 / (2 ** ($sdata->support_order)),2); 
							   
							  
							  //$campSupport[$sdata->camp->parent_camp_num] = isset($campSupport[$sdata->camp->parent_camp_num]) ? $campSupport[$sdata->camp->parent_camp_num] + round(1 / (2*($sdata->support_order)),2) : round(1 / (2*($sdata->support_order)),2);
							   /*if($skey==0 && $supportCount > 1) {
								   
								
								  $campSupport[$sdata->camp_num] = isset($campSupport[$sdata->camp_num]) ? $campSupport[$sdata->camp_num] + 0.5 : 0.5;
                                 // $campSupport[$sdata->parent_camp_num] = isset($campSupport[$sdata->parent_camp_num]) ? $campSupport[$sdata->parent_camp_num] + 0.5 : 0.5;
								  $deduction = 1;  	
								  $remainder = $remainder - 0.5;
								   
							   }
							   else if($skey==0 && $supportCount == 1) {
								   
								  $campSupport[$sdata->camp_num] = isset($campSupport[$sdata->camp_num]) ? $campSupport[$sdata->camp_num] + 1 : 1;
								// $campSupport[$sdata->parent_camp_num] = isset($campSupport[$sdata->parent_camp_num]) ? $campSupport[$sdata->parent_camp_num] + 1 : 1;
								  $remainder = 0;
								  $deduction = 1;			  
								   
							   }
							   else {  
								   
								  $campSupport[$sdata->camp_num] = isset($campSupport[$sdata->camp_num]) ? $campSupport[$sdata->camp_num] + $assignment : $assignment;
                                 //$campSupport[$sdata->parent_camp_num] = isset($campSupport[$sdata->parent_camp_num]) ? $campSupport[$sdata->parent_camp_num] + $assignment : $assignment;
 
                                 								  
							   }
							   
							   $newCounter  =  $supportCount - $deduction;
							    
                                if($newCounter == 1 )								
								  $assignment  =  $remainder;
							    else if($newCounter > 1 ) {
									
								   $assignment  = round($remainder / 2 , 2);
                                   $newCounter  = $newCounter - 1;
                                   $remainder   = $remainder - $assignment;								   
								} */
							 }  
							  
						  } //print_r($campSupport); echo "<br/>";  						  
						
					}
				} 
		return $campSupport;
		
	}
	
	public static function getBrowseTopic(){
		
		return Topic::select('topic.topic_name','topic.namespace','topic.topic_num','camp.title','camp.camp_num')->join('camp','topic.topic_num','=','camp.topic_num')->where('camp_name','=','Agreement')
		             ->where('camp.objector_nick_id', '=', NULL)
                     ->where('camp.go_live_time','<=',time())
					 ->where('topic.topic_name','<>',"")
					 ->orderBy('topic.topic_name','ASC')->groupBy('topic_num')->get();
	}
	public static function getAgreementTopic($topicnum,$filter=array()){
		
		return self::select('topic.topic_name','camp.*')->join('topic','topic.topic_num','=','camp.topic_num')->where('camp.topic_num',$topicnum)->where('camp_name','=','Agreement')
		             ->where('camp.objector_nick_id', '=', NULL)
                     ->where('camp.go_live_time','<=',time())
					 ->latest('topic.submit_time')->first();
	}
	public static function getAllAgreementTopic($limit=10,$filter=array()){
		
		if(!isset($filter['asof']) || (isset($filter['asof']) && $filter['asof']=="default")) {
		
		 return self::select(DB::raw('(select count(topic_support.id) from topic_support where topic_support.topic_num=camp.topic_num) as support, camp.*'))
		             //->leftJoin('topic_support','camp.topic_num','=','topic_support.topic_num')
					 //->leftJoin('support_instance','support_instance.topic_support_id','=','topic_support.id')
		             ->where('camp_name','=','Agreement')
		             ->where('objector_nick_id', '=', NULL)
                     ->where('camp.go_live_time','<',time())					 
					 ->latest('support')->get()->unique('topic_num')->take($limit);
		} else {
			
			if(isset($filter['asof']) && $filter['asof']=="review") {
			
			  return self::where('camp_name','=','Agreement')->latest('submit_time')->get()->take($limit);	
				
			} else if(isset($filter['asof']) && $filter['asof']=="bydate") {
				
				$asofdate =  strtotime(date('Y-m-d H:i:s', strtotime($filter['asofdate'])));
			
			  return self::where('camp_name','=','Agreement')->where('go_live_time','<=',$asofdate)->latest('submit_time')->get()->take($limit);	
				
			} 
		}			 
	}
	public static function getAllLoadMoreTopic($offset=10,$filter=array(),$id){
		
		if(!isset($filter['asof']) || (isset($filter['asof']) && $filter['asof']=="default")) {
		
		 return self::select(DB::raw('(select count(topic_support.id) from topic_support where topic_support.topic_num=camp.topic_num) as support, camp.*'))
		             ->where('camp_name','=','Agreement')
		             //->where('id','<',$id)
		             ->where('objector_nick_id', '=', NULL)
                     ->where('go_live_time','<',time())
					 ->latest('support')->take(10)->offset($offset)->get()->unique('topic_num');
		} else {
			
			if(isset($filter['asof']) && $filter['asof']=="review") {
			
			  return self::where('camp_name','=','Agreement')->where('id','<',$id)->latest('submit_time')->get()->take($limit);	
				
			} else if(isset($filter['asof']) && $filter['asof']=="bydate") {
				
				$asofdate =  strtotime(date('Y-m-d H:i:s', strtotime($filter['asofdate'])));
			
			  return self::where('camp_name','=','Agreement')->where('id','<',$id)->where('go_live_time','<=',$asofdate)->latest('submit_time')->get()->take($limit);	
				
			} 
		}			 
	}
	public static function getLiveCamp($topicnum,$campnum,$filter=array()){
		
		return self::where('topic_num',$topicnum)
		            ->where('camp_num','=', $campnum)
					->where('objector_nick_id', '=', NULL)
                    ->where('go_live_time','<',time())
					->latest('submit_time')->first();
	}
	public static function getCampHistory($topicnum,$campnum,$filter=array()){
		
		return self::where('topic_num',$topicnum)->where('camp_num','=', $campnum)->latest('submit_time')->get();
	}
	public static function getAllParent($camp, $camparray = array()) {
        
		if(!empty($camp)) {
			if ($camp->parent_camp_num) {
				$camparray[] = $camp->parent_camp_num;
			
				$pcamp = Camp::where('topic_num', $camp->topic_num)->where('camp_num', $camp->parent_camp_num)->groupBy('camp_num')->orderBy('submit_time', 'desc')->first();
				return self::getAllParent($pcamp, $camparray);
			}
		}
        return $camparray;
    }
	
	public function getAllChild($topicnum, $parentcamp,$lastparent=null,$campArray=array()){
        
        $key = $topicnum.'-'.$parentcamp.'-'.$lastparent;
        if(in_array($key,Camp::$childtempArray)){
           // dd($key,Camp::$childtempArray);
		   //dd($key,Camp::$childtempArray);
			return; /** Skip repeated recursions**/
        }
        Camp::$childtempArray[]=$key; 
        $childs = $this->campChild($topicnum,$parentcamp);
		
		
		//Camp::$chilcampArray = Camp::$chilcampArray + $campArray;
		$result = array();
		
		foreach($childs as $key=> $child){
                $childCount  = count($child->campChild($child->topic_num,$child->camp_num));
				$_SESSION['childs'][$lastparent][] = $child->camp_num;
			
				if($childCount > 0){
                 // echo $child->camp_num."<br/>";
                  $this->getAllChild($child->topic_num,$child->camp_num,$parentcamp,$campArray);  
                // print_r($response); die;				 
				   //if(count($result))
				   //$campArray = $campArray + $result;
                }
        }
		//echo "<pre>"; print_r($_SESSION['childs']); die;
        if(isset($_SESSION['childs'][$lastparent]))
	     $result = array_unique($_SESSION['childs'][$lastparent]);
		
        return $result;
    }
	
	
	public function campChild($topicnum,$parentcamp){
		
		 $childsData = Camp::where('topic_num', '=', $topicnum)
                ->where('parent_camp_num', '=', $parentcamp)
                ->where('camp_name', '!=', 'Agreement')  
               // ->where('objector_nick_id', '=', NULL)
                //->where('go_live_time','<=',time()) 				
                //->orderBy('submit_time', 'desc')
                ->get()->unique('camp_num');
		return $childsData;
	}
	
	public static function validateParentsupport($topic_num,$camp_num,$userNicknames){
		
		$onecamp        = self::getLiveCamp($topic_num,$camp_num);
	    $parentcamps    = self::getAllParent($onecamp);
		
		$mysupports     = Support::where('topic_num',$topic_num)->whereIn('camp_num',$parentcamps)->whereIn('nick_name_id',$userNicknames)->groupBy('topic_num')->orderBy('support_order','ASC')->first();
		
		if(count($mysupports))
			return true;
		else
			return false;
		
	}
	public function sortByOrder($a, $b)
	{
							$a = $a['support_order'];
							$b = $b['support_order'];

							if ($a == $b) return 0;
							return ($a > $b) ? -1 : 1;
	}
	public function getSortedTree($topics) {
		
		
		$sortedTopic = array();
		foreach($topics as $key=>$topicdata) {
			
		 $nicknames = $topicdata->GetSupportedNicknames($topicdata->topic_num);
		 
		 $supportDataset = $topicdata->getCampSupport($topicdata->topic_num,$topicdata->camp_num,$nicknames);
		 
		 $count = 0;
		 foreach($supportDataset as  $s) {
			  
			 $count = $count + $s;
		 }
		 
		 $sortedTopic[$key]['topic'] = $topicdata;
		 
		 $supportDataset[1] = isset($supportDataset[1]) ? $supportDataset[1] + $count : $count; 
		 
		   $sortedTopic[$key]['support_order'] =	isset($supportDataset[$topicdata->camp_num]	)	? $supportDataset[$topicdata->camp_num] : 0;			 
			
		}
		
		usort($sortedTopic,array($this, "sortByOrder"));
		
		return array('key'=>$topicdata->id,'sortedTree'=>$sortedTopic);
		
	}

	public function getCamptSupportCount($topicnum,$campnum){
		$supports = TopicSupport::join('support_instance','support_instance.topic_support_id','=','topic_support.id')
        ->where('topic_num', '=', $topicnum)
        ->where('support_instance.camp_num',$campnum)
		->where('topic_support.submit_time','<',time())
        //->where('delegate_nick_id',$delegateNickId)
        ->orderBy('topic_support.submit_time','DESC')
        ->select('topic_support.*')
        ->get();
		
        $supportCountTotal = 0;
        foreach($supports as $support){
            $campsupports = $support->campsupport;
            $supportCount  =  $campsupports->count(); 
			$supportPoint = Algorithm::{session('defaultAlgo')}($support->nick_name_id);
            //$supportPoint = $support->delegate_nick_id ? 0.5 : 1;
            if($supportCount > 1 ){
                $campSupport =  $campsupports->where('camp_num',$campnum)->first();
               	$supportCountTotal+=round($supportPoint / (2 ** ($campSupport->support_order)),2);
            }else if($supportCount == 1){
                $supportCountTotal+=$supportPoint;
			}
        }
		
		return $supportCountTotal;
	}

	public function buildCampTree($traversedTreeArray,$currentCamp =null, $activeCamp = null){
		$html ='<ul>';
		if($currentCamp == $activeCamp) {
			$html = '<ul><li class="create-new-li"><span><a href="'.route('camp.create',[$this->topic_num,$currentCamp]).'">&lt;Start new supporting camp here&gt;</a></span></li>';
        }
		
		if(is_array($traversedTreeArray)){
			foreach($traversedTreeArray as $campnum => $array){
				$filter = isset($_REQUEST['filter']) && is_numeric($_REQUEST['filter']) ? $_REQUEST['filter'] : 0.001;
				if($array['score'] < $filter){
						continue;
				}
				$childCount = is_array($array['children']) ? count($array['children']) : 0;
				$class= is_array($array['children']) && count($array['children']) > 0  ? 'parent' : '';
				$icon = '<i class="fa fa-arrow-right"></i>';
				$html.='<li>';
				//$selected = '';
				$selected =  ($campnum == $activeCamp) ? "color:#08b608; font-weight:bold" : "";
				$html.='<span class="'.$class.'">'.$icon.'</span><div class="tp-title"><a style="'.$selected.'" href="'.$array['link'].'">'.$array['title'].'</a> <div class="badge">'.$array['score'].'</div></div>';
				$html.=$this->buildCampTree($array['children'],$campnum,$activeCamp);
				$html.='</li>';
			}
		}
		$html .='</ul>';
        return $html;
	}

	public  function traverseCampTree($topicnum,$parentcamp,$lastparent=null){
		$key = $topicnum.'-'.$parentcamp.'-'.$lastparent;
        if(in_array($key,Camp::$traversetempArray)){
            return; /** Skip repeated recursions**/
        }
        Camp::$traversetempArray[]=$key;
        $childs = $this->Childrens($topicnum,$parentcamp);
		$array=[];
		foreach($childs as $key=> $child){ 
			//$childCount  = count($child->children($child->topic_num,$child->camp_num));
			$title = preg_replace('/[^A-Za-z0-9\-]/', '-', $child->title);
			$topic_id  = $child->topic_num."-".$title;
			$array[$child->camp_num]['title'] = $title;
			$array[$child->camp_num]['link'] = url('topic/'.$topic_id.'/'.$child->camp_num);
			$array[$child->camp_num]['score'] = $this->getCamptSupportCount($child->topic_num,$child->camp_num);
			$children =$this->traverseCampTree($child->topic_num,$child->camp_num,$child->parent_camp_num);
			$array[$child->camp_num]['children'] = is_array($children) ? $children : [];
        }
		return $array;
	}


	public function campTree($activeAcamp = null){

		$title = preg_replace('/[^A-Za-z0-9\-]/', '-', $this->title); 
		$topic_id = $this->topic_num."-".$title;
		$tree = [];
		$tree[$this->camp_num]['title'] = $this->title;
		$tree[$this->camp_num]['link'] = url('topic/'.$topic_id.'/'.$this->camp_num);
		$tree[$this->camp_num]['score'] = $this->getCamptSupportCount($this->topic_num,$this->camp_num);
		$tree[$this->camp_num]['children'] = $this->traverseCampTree($this->topic_num,$this->camp_num);
		$reducedTree = TopicSupport::sumTranversedArraySupportCount($tree);
		$filter = isset($_REQUEST['filter']) && is_numeric($_REQUEST['filter']) ? $_REQUEST['filter'] : 0.001;
		if($reducedTree[$this->camp_num]['score'] < $filter){
				return;
		}
		$selected =  ($this->camp_num == $activeAcamp) ? "color:#08b608; font-weight:bold" : "";
		//dd($reducedTree,$reducedTree[$this->camp_num]['score'], $reducedTree[$this->camp_num]['score'] == $_REQUEST['filter'] );
		$html = "<li>";
		$parentClass = is_array($reducedTree[$this->camp_num]['children']) && count($reducedTree[$this->camp_num]['children']) > 0 ? 'parent' : '';
		$html.='<span class="'.$parentClass.'"><i class="fa fa-arrow-right"></i> </span>';
		$html.= '<div class="tp-title"><a style="'.$selected.'" href="'.$reducedTree[$this->camp_num]['link'].'">'.$reducedTree[$this->camp_num]['title'].'</a><div class="badge">'.$reducedTree[$this->camp_num]['score'].'</div></div>';
		$html.=$this->buildCampTree($reducedTree[$this->camp_num]['children'],$this->camp_num,$activeAcamp);
		$html.= "</li>";
		return $html;

	}

}
