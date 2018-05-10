<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Nickname;
use DB;
use App\Model\Algorithm;
use App\Model\TopicSupport;	
use Illuminate\Database\Eloquent\Collection;	

class Camp extends Model {

    protected $table = 'camp';
    public $timestamps = false;
    public $support_order = 0;
    protected static $tempArray = [];
	protected static $childtempArray = [];
	
	protected static $chilcampArray = [];
	protected static $traversetempArray = [];

	protected static $campChildren = [] ;
	protected  static $totalSupports = [];
	protected  static $totalNickNameSupports = [];
	
    const AGREEMENT_CAMP = "Agreement";
	
    public function topic() {
		
			$as_of_time = time();
			if(isset($_REQUEST['asof']) && $_REQUEST['asof']=='bydate'){
				$as_of_time = strtotime($_REQUEST['asofdate']);
			}
		
			
			if(isset($_REQUEST['asof']) && $_REQUEST['asof']=="review") {
			
			    return $this->hasOne('App\Model\Topic', 'topic_num', 'topic_num')->orderBy('submit_time','DESC'); 
				
			
				
			} else { 
			     return $this->hasOne('App\Model\Topic', 'topic_num', 'topic_num')
			                 ->where('go_live_time','<=',$as_of_time)
						     ->orderBy('go_live_time','DESC');
			}			
		

        
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
        
		$childs = session("topic-child-{$topicnum}")->filter(function($item) use($parentcamp,$campnum){
			if($campnum){
				return $item->parent_camp_num == $parentcamp && $item->camp_num == $campnum;
			}else{
				return $item->parent_camp_num == $parentcamp;
			}
		});
		
		 /*if($campnum !=null)
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
		}		*/
				
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
        $asofdate  = time();
		if(!empty($camp)) {
			if ($campname != '') {
				$url = url('topic/'.$camp->topic_num.'/'.$camp->camp_num);
				$campname = "<a href='".$url."'>".$camp->camp_name . '</a> / ' . $campname;
			} else {
				$url = url('topic/'.$camp->topic_num.'/'.$camp->camp_num);
				$campname = "<a href='".$url."'>".$camp->camp_name. '</a>';
			}
			if ($camp->parent_camp_num) {
				
				
				$pcamp = Camp::where('topic_num', $camp->topic_num)
				          ->where('camp_num', $camp->parent_camp_num)
						  //->where('camp_name', '!=', 'Agreement')  
						  ->where('objector_nick_id', '=', NULL)
						  ->whereRaw('go_live_time in (select max(go_live_time) from camp where topic_num='.$camp->topic_num.' and objector_nick_id is null group by camp_num)')				
						  //->where('go_live_time','<',$asofdate)
						  ->groupBy('camp_num')->orderBy('submit_time', 'desc')->first();
				return self::campNameWithAncestors($pcamp, $campname);
			}
		}
        return $campname;
    }
	
	
	public static function getBrowseTopic(){
		
		$as_of_time = time();
			if(isset($_REQUEST['asof']) && $_REQUEST['asof']=='bydate'){
				$as_of_time = strtotime($_REQUEST['asofdate']);
			}
		
		$query = Topic::select('topic.topic_name','namespace.name as namespace','namespace.label','topic.topic_num','camp.title','camp.camp_num')
		             ->join('camp','topic.topic_num','=','camp.topic_num')
					 ->join('namespace','topic.namespace_id','=','namespace.id')
					 ->where('camp_name','=','Agreement')
		             ->where('camp.objector_nick_id', '=', NULL)
                     ->where('camp.go_live_time','<=',$as_of_time)
					 ->where('topic.topic_name','<>',"");
		
		if(isset($_REQUEST['namespace']) && (!empty($_REQUEST['namespace']) || $_REQUEST['namespace'] != 0)){
			$query->where('namespace_id',$_REQUEST['namespace']);
		}

		return $query->orderBy('topic.namespace','ASC')->orderBy('topic.topic_name','ASC')->orderBy('topic.go_live_time','DESC')->groupBy('topic_num')->get();
	}
	public static function getAgreementTopic($topicnum,$filter=array()){
		
	  if(!isset($filter['asof']) || (isset($filter['asof']) && $filter['asof']=="default")) {	
		return self::select('topic.topic_name','camp.*','namespace.name as namespace_name','namespace.label')
		             ->join('topic','topic.topic_num','=','camp.topic_num')
					 ->join('namespace','topic.namespace_id','=','namespace.id')
					 ->where('topic.topic_num',$topicnum)->where('camp_name','=','Agreement')
		             ->where('camp.objector_nick_id', '=', NULL)
                     ->where('camp.go_live_time','<=',time())
					 ->orderBy('topic.go_live_time','DESC')->first();
	  }	else {
			
			if(isset($filter['asof']) && $filter['asof']=="review") {
             return self::select('topic.topic_name','camp.*','namespace.name as namespace_name,namespace.label')
			         ->join('topic','topic.topic_num','=','camp.topic_num')
					 ->join('namespace','topic.namespace_id','=','namespace.id')
					 ->where('camp.topic_num',$topicnum)->where('camp_name','=','Agreement')
		             ->where('camp.objector_nick_id', '=', NULL)                     
					 ->latest('topic.submit_time')->first();
            } else if(isset($filter['asof']) && $filter['asof']=="bydate") {
				$asofdate =  strtotime(date('Y-m-d H:i:s', strtotime($filter['asofdate'])));
				return self::select('topic.topic_name','camp.*','namespace.name as namespace_name,namespace.label')
				     ->join('topic','topic.topic_num','=','camp.topic_num')
					 ->join('namespace','topic.namespace_id','=','namespace.id')
					 ->where('camp.topic_num',$topicnum)->where('camp_name','=','Agreement')
		             ->where('camp.objector_nick_id', '=', NULL)
                     ->where('camp.go_live_time','<=',$asofdate)
					 ->latest('topic.submit_time')->first();
			}	
      }  			
	}
	public static function getAllAgreementTopic($limit=10,$filter=array()){
		
		

		if(!isset($filter['asof']) || (isset($filter['asof']) && $filter['asof']=="default")) {
		
		 return self::select(DB::raw('(select count(topic_support.id) from topic_support where topic_support.topic_num=camp.topic_num) as support, camp.*'))
		             ->join('topic','topic.topic_num','=','camp.topic_num')
					 //->leftJoin('topic_support','camp.topic_num','=','topic_support.topic_num')
					 //->leftJoin('support_instance','support_instance.topic_support_id','=','topic_support.id')
		             ->where('camp_name','=','Agreement')
		             ->where('camp.objector_nick_id', '=', NULL)
					 ->whereIn('namespace_id',explode(',',session('defaultNamespaceId',1)))
                     ->where('camp.go_live_time','<=',time())
                     ->orderBy('topic.go_live_time','DESC')					 
					 ->latest('support')->get()->unique('topic_num')->take($limit);
					 
		} else {
			
			if(isset($filter['asof']) && $filter['asof']=="review") {
			
			  return self::where('camp_name','=','Agreement')->join('topic','topic.topic_num','=','camp.topic_num')->whereIn('namespace_id',explode(',',session('defaultNamespaceId')))->latest('camp.submit_time')->get()->take($limit);	
				
			} else if(isset($filter['asof']) && $filter['asof']=="bydate") {
				$asofdate =  strtotime(date('Y-m-d H:i:s', strtotime($filter['asofdate'])));
				
			
			  return self::where('camp_name','=','Agreement')->join('topic','topic.topic_num','=','camp.topic_num')->whereIn('namespace_id',explode(',',session('defaultNamespaceId')))->where('camp.go_live_time','<=',$asofdate)->latest('camp.submit_time')->get()->take($limit);	
				
			} 
		}			 
	}
	public static function getAllLoadMoreTopic($offset=10,$filter=array(),$id){
		
		if(!isset($filter['asof']) || (isset($filter['asof']) && $filter['asof']=="default")) {
		
		 return self::select(DB::raw('(select count(topic_support.id) from topic_support where topic_support.topic_num=camp.topic_num) as support, camp.*'))
		             ->where('camp_name','=','Agreement')
					 ->join('topic','topic.topic_num','=','camp.topic_num')
		             //->where('id','<',$id)
		             ->where('camp.objector_nick_id', '=', NULL)
					 ->whereIn('namespace_id',explode(',',session('defaultNamespaceId',1)))
                     ->where('camp.go_live_time','<=',time())
					 ->latest('support')->take(10)->offset($offset)->get()->unique('topic_num');
		} else {
			
			if(isset($filter['asof']) && $filter['asof']=="review") {
			
			  return self::where('camp_name','=','Agreement')->join('topic','topic.topic_num','=','camp.topic_num')->whereIn('namespace_id',explode(',',session('defaultNamespaceId')))->latest('camp.submit_time')->take(10)->offset($offset)->get();	
				
			} else if(isset($filter['asof']) && $filter['asof']=="bydate") {
				
				$asofdate =  strtotime(date('Y-m-d H:i:s', strtotime($filter['asofdate'])));
			
			  return self::where('camp_name','=','Agreement')->join('topic','topic.topic_num','=','camp.topic_num')->whereIn('namespace_id',explode(',',session('defaultNamespaceId')))->where('camp.go_live_time','<=',$asofdate)->latest('camp.submit_time')->take(10)->offset($offset)->get();	
				
			} 
		}			 
	}
	public static function getLiveCamp($topicnum,$campnum,$filter=array()){
	    if(!isset($_REQUEST['asof']) || (isset($_REQUEST['asof']) && $_REQUEST['asof']=="default")) {
			
			return self::where('topic_num',$topicnum)
						->where('camp_num','=', $campnum)
						->where('objector_nick_id', '=', NULL)
						->where('go_live_time','<=',time())
						->latest('submit_time')->first();
		} else {
			
			if(isset($_REQUEST['asof']) && $_REQUEST['asof']=="review") {
				
				return self::where('topic_num',$topicnum)
						->where('camp_num','=', $campnum)
						->where('objector_nick_id', '=', NULL)						
						->latest('submit_time')->first();

            }else if(isset($_REQUEST['asof']) && $_REQUEST['asof']=="bydate") {
				
				$asofdate =  strtotime(date('Y-m-d H:i:s', strtotime($_REQUEST['asofdate'])));
                return self::where('topic_num',$topicnum)
						->where('camp_num','=', $campnum)
						->where('objector_nick_id', '=', NULL)
						->where('go_live_time','<=',$asofdate)
						->latest('submit_time')->first();
            }
		}			
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
	public static function getAllChildCamps($camp) {
        
		
		$camparray = [];
		if($camp){
			$key = $camp->topic_num.'-'.$camp->camp_num.'-'.$camp->parent_camp_num;
			
			if(in_array($key,Camp::$chilcampArray)){
				return []; /** Skip repeated recursions**/
			}
			Camp::$chilcampArray[]=$key; 
			$camparray[] = $camp->camp_num;

			$childCamps = Camp::where('topic_num', $camp->topic_num)->where('parent_camp_num', $camp->camp_num)->groupBy('camp_num')->orderBy('submit_time', 'desc')->get();
			
			foreach($childCamps as $child){
				$camparray=array_merge($camparray,self::getAllChildCamps($child));	
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
                 $this->getAllChild($child->topic_num,$child->camp_num,$parentcamp,$campArray);  
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
	
	public static function validateParentsupport($topic_num,$camp_num,$userNicknames,$confirm_support){
		
		$onecamp        = self::getLiveCamp($topic_num,$camp_num);
		
		if(count($onecamp) <=0 ) { 
		  return 'notlive';
		} 
	    
		$parentcamps    = self::getAllParent($onecamp);
		
		$mysupports     = Support::where('topic_num',$topic_num)->whereIn('camp_num',$parentcamps)->whereIn('nick_name_id',$userNicknames)->where('end','=',0)->groupBy('topic_num')->orderBy('support_order','ASC')->first();
		
		if($confirm_support && count($mysupports)) {
		  
		 $mysupports->end = time();
		 $mysupports->update();
	
         $mysupports     = Support::where('topic_num',$topic_num)->whereIn('camp_num',$parentcamps)->whereIn('nick_name_id',$userNicknames)->where('end','=',0)->groupBy('topic_num')->orderBy('support_order','ASC')->first();
				  
		}
		
		
		if(count($mysupports))
			return true;
		else
			return false;
		
	}

	public static function validateChildsupport($topic_num,$camp_num,$userNicknames,$confirm_support){
		
		$onecamp        = self::getLiveCamp($topic_num,$camp_num);
		
	    $childCamps    = array_unique(self::getAllChildCamps($onecamp));
		
		$mysupports     = Support::where('topic_num',$topic_num)->whereIn('camp_num',$childCamps)->whereIn('nick_name_id',$userNicknames)->where('end','=',0)->groupBy('topic_num')->orderBy('support_order','ASC')->first();
		
		if($confirm_support && count($mysupports)) {
		  
		 $mysupports->end = time();
		 $mysupports->update();
	
         $mysupports     = Support::where('topic_num',$topic_num)->whereIn('camp_num',$childCamps)->whereIn('nick_name_id',$userNicknames)->where('end','=',0)->groupBy('topic_num')->orderBy('support_order','ASC')->first();
				  
		}
		
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

	public function getDeletegatedSupportCount($algorithm,$topicnum,$campnum,$delegateNickId,$parent_support_order,$multiSupport){

		/*Delegated Support */

        $delegatedSupports =  session("topic-support-{$topicnum}")->filter(function($item) use ($delegateNickId){
                return $item->delegate_nick_name_id == $delegateNickId;
        });
        
		$score = 0;
		
        foreach($delegatedSupports as $support){           
            $supportPoint = Algorithm::{$algorithm}($support->nick_name_id); 
			
            if($multiSupport){
               $score+= round($supportPoint / (2 ** ($parent_support_order)),2);
            }else{
               $score+= $supportPoint;
            }
			
            $score+= $this->getDeletegatedSupportCount($algorithm,$topicnum,$campnum,$support->nick_name_id,$parent_support_order,$multiSupport);
        }
		
		return $score;

	}

	public function getCamptSupportCount($algorithm,$topicnum,$campnum){

		$as_of_time = time();
		if(isset($_REQUEST['asof']) && $_REQUEST['asof']=='bydate'){
			$as_of_time = strtotime($_REQUEST['asofdate']);
		}
		
		$supportCountTotal = 0;
		try{
		foreach(session("topic-support-nickname-$topicnum") as $supported){
			
			$nickNameSupports = session("topic-support-{$topicnum}")->filter(function ($item) use($supported)
			{
				 return $item->nick_name_id == $supported->nick_name_id; /* Current camp support */
			});
			
			$supportPoint = Algorithm::{$algorithm}($supported->nick_name_id);
			$currentCampSupport =  $nickNameSupports->filter(function ($item) use($campnum)
			{
				return $item->camp_num == $campnum; /* Current camp support */
			})->first();

			if($currentCampSupport){
				$multiSupport = false;
				if($nickNameSupports->count() > 1){
					$multiSupport = true;
					$supportCountTotal+=round($supportPoint / (2 ** ($currentCampSupport->support_order)),2);
				}else if($nickNameSupports->count() == 1){
					$supportCountTotal+=$supportPoint;
				}
				$supportCountTotal+=$this->getDeletegatedSupportCount($algorithm,$topicnum,$campnum,$supported->nick_name_id,$currentCampSupport->support_order,$multiSupport);
			}
		}}catch(\Exception $e){
			echo "topic-support-nickname-$topicnum".$e->getMessage();
		}
		
		return $supportCountTotal;
	}

	public function buildCampTree($traversedTreeArray,$currentCamp =null, $activeCamp = null,$activeCampDefault=false){
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
				$html.="<li id='tree_".$this->topic_num."_".$currentCamp."_".$campnum."'>";
				//$selected = '';
				$selected =  ($campnum == $activeCamp) && $activeCampDefault ? "color:#08b608; font-weight:bold" : "";
				if(($campnum == $activeCamp) && $activeCampDefault){
					session(['supportCountTotal'=>$array['score']]);
				}

				$html.='<span class="'.$class.'">'.$icon.'</span><div class="tp-title"><a style="'.$selected.'" href="'.$array['link'].'">'.$array['title'].'</a> <div class="badge">'.$array['score'].'</div></div>';
				$html.=$this->buildCampTree($array['children'],$campnum,$activeCamp,$activeCampDefault);
				$html.='</li>';
			}
		}
		$html .='</ul>';
        return $html;
	}

	public  function traverseCampTree($algorithm,$topicnum,$parentcamp,$lastparent=null){
		$key = $topicnum.'-'.$parentcamp.'-'.$lastparent;
        if(in_array($key,Camp::$traversetempArray)){
            return; /** Skip repeated recursions**/
        }
        Camp::$traversetempArray[]=$key;
        $childs = $this->Childrens($topicnum,$parentcamp);
		$array=[];
		foreach($childs as $key=> $child){ 
			//$childCount  = count($child->children($child->topic_num,$child->camp_num));
			$title = preg_replace('/[^A-Za-z0-9\-]/', '-', $child->camp_name);
			$topic_id  = $child->topic_num."-".$title;
			$array[$child->camp_num]['title'] = $child->camp_name;
			$array[$child->camp_num]['link'] = url('topic/'.$topic_id.'/'.$child->camp_num.'?'.app('request')->getQueryString());
			$array[$child->camp_num]['score'] = $this->getCamptSupportCount($algorithm,$child->topic_num,$child->camp_num);
			$children =$this->traverseCampTree($algorithm,$child->topic_num,$child->camp_num,$child->parent_camp_num);
			
			$array[$child->camp_num]['children'] = is_array($children) ? $children : [];
        }
		return $array;
	}


	public function campTree($algorithm,$activeAcamp = null,$supportCampCount=0,$needSelected=0){
		//return '';
		//session()->flush();dd(1);

		$as_of_time = time();
		if(isset($_REQUEST['asof']) && $_REQUEST['asof']=='bydate'){
			$as_of_time = strtotime($_REQUEST['asofdate']);
		}
		if(!session("topic-support-nickname-{$this->topic_num}")){
			session(["topic-support-nickname-{$this->topic_num}" => Support::where('topic_num','=',$this->topic_num)
                        ->where('delegate_nick_name_id',0)
                        ->whereRaw("(start < $as_of_time) and ((end = 0) or (end > $as_of_time))")
                        ->orderBy('start','DESC')
                        ->groupBy('nick_name_id')
						->select(['nick_name_id','delegate_nick_name_id','support_order','topic_num','camp_num'])
                        ->get()]);
		}
		

		if(!session("topic-support-{$this->topic_num}")){
			session(["topic-support-{$this->topic_num}" =>Support::where('topic_num','=',$this->topic_num)
				//->where('nick_name_id',$supported->nick_name_id)
				->whereRaw("(start < $as_of_time) and ((end = 0) or (end > $as_of_time))")
				->orderBy('start','DESC')
				->select(['support_order','camp_num','nick_name_id','delegate_nick_name_id','topic_num'])
				->get()]);
		}

		
	

		if(!isset($_REQUEST['asof']) || (isset($_REQUEST['asof']) && $_REQUEST['asof']=="default")) {
			
				session(["topic-child-{$this->topic_num}" => self::where('topic_num', '=', $this->topic_num)
						//->where('parent_camp_num', '=', $parentcamp)
						->where('camp_name', '!=', 'Agreement')  
						->where('objector_nick_id', '=', NULL)
						->whereRaw('go_live_time in (select max(go_live_time) from camp where topic_num='.$this->topic_num.' and objector_nick_id is null and go_live_time < "'.time().'" group by camp_num)')				
						->where('go_live_time','<=',time())
						->groupBy('camp_num')				
						->orderBy('submit_time', 'desc')
						->get()]);
			
		} else {
			
			if(isset($_REQUEST['asof']) && $_REQUEST['asof']=="review") {
			
			
				session(["topic-child-{$this->topic_num}" => self::where('topic_num', '=', $this->topic_num)
                //->where('parent_camp_num', '=', $parentcamp)
                ->where('camp_name', '!=', 'Agreement')  
				->whereRaw('go_live_time in (select max(go_live_time) from camp where topic_num='.$this->topic_num.' and objector_nick_id is null group by camp_num)')				
                ->orderBy('submit_time', 'desc')
				->groupBy('camp_num')
                ->get()]);
			
				
			} else if(isset($_REQUEST['asof']) && $_REQUEST['asof']=="bydate") {
				
				$asofdate =  strtotime(date('Y-m-d H:i:s', strtotime($_REQUEST['asofdate'])));
			
			  	
				session(["topic-child-{$this->topic_num}" => self::where('topic_num', '=',$this->topic_num)
                ->where('camp_name', '!=', 'Agreement')  
                ->where('objector_nick_id', '=', NULL)
				->whereRaw('go_live_time in (select max(go_live_time) from camp where topic_num='.$this->topic_num.' and objector_nick_id is null group by camp_num)')				
                ->where('go_live_time','<=',$asofdate) 				
                ->orderBy('submit_time', 'desc')
				->groupBy('camp_num')
                ->get()]);
			
			}	
		}
		

		$title = preg_replace('/[^A-Za-z0-9\-]/', '-', $this->topic->topic_name); 
		$topic_id = $this->topic_num."-".$title;
		$tree = [];
		$tree[$this->camp_num]['title'] = $this->topic->topic_name;
		$tree[$this->camp_num]['link'] = url('topic/'.$topic_id.'/'.$this->camp_num);
		$tree[$this->camp_num]['score'] = $this->getCamptSupportCount($algorithm,$this->topic_num,$this->camp_num);
		$tree[$this->camp_num]['children'] = $this->traverseCampTree($algorithm,$this->topic_num,$this->camp_num);
		
		return $reducedTree = TopicSupport::sumTranversedArraySupportCount($tree);
		

	}

	public function campTreeHtml($activeCamp = null,$activeCampDefault=false){
		
		$reducedTree= $this->campTree(session('defaultAlgo','blind_popularity'),$activeAcamp = null,$supportCampCount=0,$needSelected=0);

		$filter = isset($_REQUEST['filter']) && is_numeric($_REQUEST['filter']) ? $_REQUEST['filter'] : 0.001;
		
		if($reducedTree[$this->camp_num]['score'] < $filter){
				return;
		}
		
		$selected =  ($this->camp_num == $activeCamp) && $activeCampDefault ? "color:#08b608; font-weight:bold" : "";
		if(($this->camp_num == $activeCamp)){
					session(['supportCountTotal'=>$reducedTree[$this->camp_num]['score']]);
		}
		
		$html = "<li id='tree_".$this->topic_num."_".$activeCamp."_".$this->camp_num."'>";
		$parentClass = is_array($reducedTree[$this->camp_num]['children']) && count($reducedTree[$this->camp_num]['children']) > 0 ? 'parent' : '';
		$html.='<span class="'.$parentClass.'"><i class="fa fa-arrow-right"></i> </span>';
		$html.= '<div class="tp-title"><a style="'.$selected.'" href="'.$reducedTree[$this->camp_num]['link'].'">'.$reducedTree[$this->camp_num]['title'].'</a><div class="badge">'.round($reducedTree[$this->camp_num]['score'],2).'</div></div>';
		$html.=$this->buildCampTree($reducedTree[$this->camp_num]['children'],$this->camp_num,$activeCamp,$activeCampDefault);
		$html.= "</li>";
		return $html;
	}
	
	/* Following methods are for Web Service for camp outline */
	
	public function campTreeHtmlApi($activeCamp = null,$activeCampDefault=false){
		
		$reducedTree= $this->campTree(session('defaultAlgo','blind_popularity'),$activeAcamp = null,$supportCampCount=0,$needSelected=0);

		$filter = isset($_REQUEST['filter']) && is_numeric($_REQUEST['filter']) ? $_REQUEST['filter'] : 0.001;
		
		if($reducedTree[$this->camp_num]['score'] < $filter){
				return;
		}
		
		$selected =  ($this->camp_num == $activeCamp) && $activeCampDefault ? "color:#08b608; font-weight:bold" : "";
		if(($this->camp_num == $activeCamp)){
					session(['supportCountTotal'=>$reducedTree[$this->camp_num]['score']]);
		}
		
		$html = "<li id='tree_".$this->topic_num."_".$activeCamp."_".$this->camp_num."'>";
		$parentClass = is_array($reducedTree[$this->camp_num]['children']) && count($reducedTree[$this->camp_num]['children']) > 0 ? 'parent' : '';
		$html.='<span class="'.$parentClass.'"><i class="fa fa-arrow-right"></i> </span>';
		$html.= '<div class="tp-title"><a style="'.$selected.'" href="'.$reducedTree[$this->camp_num]['link'].'">'.$reducedTree[$this->camp_num]['title'].'</a><div class="badge">'.round($reducedTree[$this->camp_num]['score'],2).'</div></div>';
		$html.=$this->buildCampTree($reducedTree[$this->camp_num]['children'],$this->camp_num,$activeCamp,$activeCampDefault);
		$html.= "</li>";
		return $html;
	}
	

}
