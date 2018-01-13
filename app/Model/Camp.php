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
	public function scopeGetSupportedNicknames($query, $topicnum) {
        $nicknames = Support::where('topic_num', '=', $topicnum)
		             ->groupBy('nick_name_id')
					 ->get();
        return $nicknames;
    }
	public function scopeGetSupportByNickname($query, $topicnum,$nicknameId) {
        $support = Support::where('topic_num', '=', $topicnum)
		             ->where('nick_name_id', '=', $nicknameId)
					 ->orderBy('support_order', 'asc')
		             ->get();
        return $support;
    }

    public function scopeCampNameWithAncestors($query, $camp, $campname = '') {
        if ($campname != '') {
            $campname = $camp->camp_name . ' / ' . $campname;
        } else {
            $campname = $camp->camp_name;
        }
        if ($camp->parent_camp_num) {
            $pcamp = Camp::where('topic_num', $camp->topic_num)->where('camp_num', $camp->parent_camp_num)->groupBy('camp_num')->orderBy('submit_time', 'desc')->first();
            return self::campNameWithAncestors($pcamp, $campname);
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
        $html= '<ul><li class="create-new-li"><span><a href="'.route('camp.create',['topicnum'=>$topicnum,'campnum'=>$parentcamp]).'">&lt;Create A New Camp &gt;</a></span></li>';
        foreach($childs as $child){
                $childCount  = count($child->childrens($child->topic_num,$child->camp_num));
										
			    $title      = preg_replace('/[^A-Za-z0-9\-]/', '-', $child->title);
			 
			    $topic_id  = $child->topic_num."-".$title;
						
                $class= $childCount > 0  ? 'parent' : '';
                $icon = '<i class="fa fa-arrow-right"></i>';
                $html.='<li>';
                $selected =  ($campnum==$child->camp_num) ? "color:#08b608; font-weight:bold" : "";	
                $html.='<span class="'.$class.'">'.$icon.'</span><div class="tp-title"><a style="'.$selected.'" href="'.url('topic/'.$topic_id.'/'.$child->camp_num).'">'.$child->title.'</a> <div class="badge">48.25</div></div>';
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

}
