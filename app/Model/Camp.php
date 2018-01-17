<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
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

    public function scopeChildrens($query, $topicnum, $parentcamp,$campnum=null) {
        
		 if($campnum !=null)
			$query->where('camp_num', '=', $campnum);
		
		$childs = $query->where('topic_num', '=', $topicnum)
                ->where('parent_camp_num', '=', $parentcamp)
                ->where('camp_name', '!=', 'Agreement')                
                ->orderBy('submit_time', 'desc')
                ->get()->unique('camp_num','topic_num');
				
        return $childs;
    }
	public function scopeStatement($query, $topicnum, $campnum) {
        $statement = Statement::where('topic_num', '=', $topicnum)
                ->where('camp_num', '=', $campnum)
                ->latest('submit_time')->first();
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
				$campname = $camp->camp_name . ' / ' . $campname;
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
	

}
