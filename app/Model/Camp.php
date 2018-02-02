<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Nickname;
use DB;

class Camp extends Model {

    protected $table = 'camp';
    public $timestamps = false;

    protected static $tempArray = [];

    const AGREEMENT_CAMP = "Agreement";
	
    public function topic() {
        return $this->hasOne('App\Model\Topic', 'topic_num', 'topic_num');
    }
	public function nickname() {
        return $this->hasOne('App\Model\Nickname', 'nick_name_id', 'nick_name_id');
    }
	public function objectornickname() {
        return $this->hasOne('App\Model\Nickname', 'nick_name_id', 'objector');
    }
	public function submitternickname() {
        return $this->hasOne('App\Model\Nickname', 'nick_name_id', 'submitter');
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
                ->where('objector', '=', NULL)
                ->where('go_live_time','<=',time()) 				
                ->orderBy('submit_time', 'desc')
                ->get()->unique('camp_num','topic_num');
		} else {
			
			if(isset($_REQUEST['asof']) && $_REQUEST['asof']=="review") {
			
			 $childs = $query->where('topic_num', '=', $topicnum)
                ->where('parent_camp_num', '=', $parentcamp)
                ->where('camp_name', '!=', 'Agreement')  
                ->orderBy('submit_time', 'desc')
                ->get();	
				
			} else if(isset($_REQUEST['asof']) && $_REQUEST['asof']=="bydate") {
				
				$asofdate =  strtotime(date('Y-m-d H:i:s', strtotime($_REQUEST['asofdate'])));
			
			  $childs = $query->where('topic_num', '=', $topicnum)
                ->where('parent_camp_num', '=', $parentcamp)
                ->where('camp_name', '!=', 'Agreement')  
                ->where('objector', '=', NULL)
                ->where('go_live_time','<=',$asofdate) 				
                ->orderBy('submit_time', 'desc')
                ->get()->unique('camp_num','topic_num');
			} 
		}		
				
        return $childs;
    }
	public function scopeStatement($query, $topicnum, $campnum) {
		
        $statement = Statement::getLiveStatement($topicnum,$campnum);
								
        return $statement;
    }
	public function scopeGetSupportedNicknames($query, $topicnum,$campnum=null) {
        $query = Support::where('topic_num', '=', $topicnum)
		             ->groupBy('nick_name_id');
		if($campnum !=null)			 
			$query->where('camp_num', '=', $campnum);		 
					 
        return $nicknames = $query->get();
    }
	public function scopeGetSupportByNickname($query, $topicnum,$nicknameId) {
        $support = Support::where('topic_num', '=', $topicnum)
		             ->where('nick_name_id', '=', $nicknameId)
					 ->orderBy('support_order', 'asc')
		             ->get();
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

    public function campTree($topicnum, $parentcamp,$lastparent=null,$campnum=null){
        
        $key = $topicnum.'-'.$parentcamp.'-'.$lastparent;
        if(in_array($key,Camp::$tempArray)){
            return; /** Skip repeated recursions**/
        }
        Camp::$tempArray[]=$key;
        $childs = $this->childrens($topicnum,$parentcamp);
		
		$camp_support_count = 0;
        $html= '<ul><li class="create-new-li"><span><a href="'.route('camp.create',['topicnum'=>$topicnum,'campnum'=>$parentcamp]).'">&lt;Create A New Camp &gt;</a></span></li>';
        foreach($childs as $child){
                $childCount  = count($child->childrens($child->topic_num,$child->camp_num));
				$thisCampCount = $this->getCampSupport($child->topic_num,$child->camp_num);
				$camp_support_count = $camp_support_count + $thisCampCount;						
			    $title      = preg_replace('/[^A-Za-z0-9\-]/', '-', $child->title);
			 
			    $topic_id  = $child->topic_num."-".$title;
						
                $class= $childCount > 0  ? 'parent' : '';
                $icon = '<i class="fa fa-arrow-right"></i>';
                $html.='<li>';
                $selected =  ($campnum==$child->camp_num) ? "color:#08b608; font-weight:bold" : "";	
                $html.='<span class="'.$class.'">'.$icon.'</span><div class="tp-title"><a style="'.$selected.'" href="'.url('topic/'.$topic_id.'/'.$child->camp_num).'">'.$child->title.'</a> <div class="badge">'.$thisCampCount.'</div></div>';
                if($childCount > 0){
                    $html.=$this->campTree($child->topic_num,$child->camp_num,$child->parent_camp_num);
                }else{
                    $html.='<ul><li class="create-new-li"><span><a href="'.route('camp.create',['topicnum'=>$child->topic_num,'campnum'=>$child->camp_num]).'">&lt; Create A New Camp &gt;</a></span></li></ul>';
                }
                $html.='</li>';
        }
        $html.= '</ul>';
        return $html;
    }
	
	public function getNicknameSupport($nickname,$campnum) {
		
		 $campSupport[$campnum] = 0;
						
		 if(!empty($nickname)) {
			 
						$support = $this->GetSupportByNickname($nickname->topic_num,$nickname->nick_name_id);
						$supportCount = count($support);
						
						if($supportCount ==1 && $nickname->camp_num== $campnum) {
							
						 $campSupport[$campnum] = $campSupport[$campnum] + 1;
						
						} 
                        else if($supportCount == 1) {
						  $campSupport[$nickname->camp_num]	= 1;
						  
					    }
					    else {
						
                          $assignment = 0;
                           
                          foreach($support as $skey=>$sdata) {
							  
							  $deduction = 0;
							  
							   if($skey==0 && $sdata->camp_num == $campnum && $supportCount > 1) {
								   
								
								  $campSupport[$campnum] = $campSupport[$campnum] + 0.5;
                                  $deduction = 1;  								  
								   
							   }
							   else if($skey==0 && $sdata->camp_num == $campnum && $supportCount == 1) {
								   
								  
								  $campSupport[$campnum] = $campSupport[$campnum] + 1;
                                  							  
								   
							   }
							   else if($skey==0 & $supportCount == 1) {
								  
                                   $campSupport[$sdata->camp_num]  = 1;
                                   								   
								   
							   } else {  
								   
								   if(isset($campSupport[$sdata->camp_num])) 
								    $campSupport[$sdata->camp_num] = $campSupport[$sdata->camp_num] + $assignment;
								   else
									$campSupport[$sdata->camp_num] = $assignment;   
							   }
							   
							   $newCounter  =  $supportCount - $deduction;
							   $assignment  =  round(0.5 / $newCounter,2);
							  
							  
						  }  
						
						
					}
		
		}
      return $campSupport;		
		
	}
	
	// function to get camps support count
	
	public function getCampSupport($topicnum,$campnum) {
		
		
		$nicknames = $this->GetSupportedNicknames($topicnum);
					
					
					$campSupport[$campnum] = 0;
					foreach($nicknames as $key=>$nickname) {
						
						$support = $this->GetSupportByNickname($topicnum,$nickname->nick_name_id);
						$supportCount = count($support);
						
						if($supportCount ==1 && $nickname->camp_num== $campnum) {
							
						 $campSupport[$campnum] = $campSupport[$campnum] + 1;
						
						} 
                        else if($supportCount == 1) {
						  $campSupport[$nickname->camp_num]	= 1;
						  
					    }
					    else {
						
                          $assignment = 0;
                           
                          foreach($support as $skey=>$sdata) {
							  
							  $deduction = 0;
							  
							   if($skey==0 && $sdata->camp_num == $campnum && $supportCount > 1) {
								   
								
								  $campSupport[$campnum] = $campSupport[$campnum] + 0.5;
                                  $deduction = 1;  								  
								   
							   }
							   else if($skey==0 && $sdata->camp_num == $campnum && $supportCount == 1) {
								   
								  
								  $campSupport[$campnum] = $campSupport[$campnum] + 1;
                                  							  
								   
							   }
							   else if($skey==0 & $supportCount == 1) {
								  
                                   $campSupport[$sdata->camp_num]  = 1;
                                   								   
								   
							   } else {  
								   
								   if(isset($campSupport[$sdata->camp_num])) 
								    $campSupport[$sdata->camp_num] = $campSupport[$sdata->camp_num] + $assignment;
								   else
									$campSupport[$sdata->camp_num] = $assignment;   
							   }
							   
							   $newCounter  =  $supportCount - $deduction;
							   $assignment  =  round(0.5 / $newCounter,2);
							  
							  
						  }  						  
							
							
						}
						
						
					}
		return $campSupport[$campnum];
		
	}
	
	public static function getAgreementTopic($topicnum,$filter=array()){
		
		return self::where('topic_num',$topicnum)->where('camp_name','=','Agreement')
		             ->where('objector', '=', NULL)
                     ->where('go_live_time','<=',time())
					 ->latest('submit_time')->first();
	}
	public static function getAllAgreementTopic($limit=10,$filter=array()){
		
		if(!isset($filter['asof']) || (isset($filter['asof']) && $filter['asof']=="default")) {
		
		 return self::where('camp_name','=','Agreement')
		             ->where('objector', '=', NULL)
                     ->where('go_live_time','<=',time())
					 ->latest('submit_time')->get()->unique('topic_num')->take($limit);
		} else {
			
			if(isset($filter['asof']) && $filter['asof']=="review") {
			
			  return self::where('camp_name','=','Agreement')->latest('submit_time')->get()->take($limit);	
				
			} else if(isset($filter['asof']) && $filter['asof']=="bydate") {
				
				$asofdate =  strtotime(date('Y-m-d H:i:s', strtotime($filter['asofdate'])));
			
			  return self::where('camp_name','=','Agreement')->where('go_live_time','<=',$asofdate)->latest('submit_time')->get()->take($limit);	
				
			} 
		}			 
	}
	public static function getAllLoadMoreTopic($limit=10,$filter=array(),$id){
		
		if(!isset($filter['asof']) || (isset($filter['asof']) && $filter['asof']=="default")) {
		
		 return self::where('camp_name','=','Agreement')
		             ->where('id','<',$id)
		             ->where('objector', '=', NULL)
                     ->where('go_live_time','<=',time())
					 ->latest('submit_time')->get()->unique('topic_num')->take($limit);
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
					->where('objector', '=', NULL)
                    ->where('go_live_time','<=',time())
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
	
	public static function validateParentsupport($topic_num,$camp_num,$userNicknames){
		
		$onecamp        = self::getLiveCamp($topic_num,$camp_num);
	    $parentcamps    = self::getAllParent($onecamp);
		
		$mysupports     = Support::where('topic_num',$topic_num)->whereIn('camp_num',$parentcamps)->whereIn('nick_name_id',$userNicknames)->groupBy('topic_num')->orderBy('support_order','ASC')->first();
		
		if(count($mysupports))
			return true;
		else
			return false;
		
	}

}
