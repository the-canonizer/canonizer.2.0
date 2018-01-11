<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Camp extends Model {

    protected $table = 'camp';
    public $timestamps = false;

    protected static $tempArray = [];

    const AGREEMENT_CAMP = "Agreement";

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

    public function scopeChildrens($query, $topicnum, $parentcamp) {
        $childs = $query->where('topic_num', '=', $topicnum)
                ->where('parent_camp_num', '=', $parentcamp)
                ->where('camp_name', '!=', 'Agreement')
                ->groupBy('camp_num')
                ->orderBy('submit_time', 'desc')
                ->get();
        return $childs;
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

    public function champTree($topicnum, $parentcamp,$lastparent=null){
        
        $key = $topicnum.'-'.$parentcamp.'-'.$lastparent;
        if(in_array($key,Camp::$tempArray)){
            return; /** Skip repeated recursions**/
        }
        Camp::$tempArray[]=$key;
        $childs = $this->childrens($topicnum,$parentcamp);
        $html= '<ul><li class="create-new-li"><span><a href="'.route('camp.create',['topicnum'=>$topicnum,'campnum'=>$parentcamp]).'">&lt;Create A New Camp &gt;</a></span></li>';
        foreach($childs as $child){
                $childCount  = count($child->childrens($child->topic_num,$child->camp_num));
                $class= $childCount > 0  ? 'parent' : '';
                $icon = '<i class="fa fa-arrow-right"></i>';
                $html.='<li>';        
                $html.='<span class="'.$class.'">'.$icon.'</span><div class="tp-title"><a href="#">'.$child->title.'</a> <div class="badge">48.25</div></div>';
                if($childCount > 0){
                    $html.=$this->champTree($child->topic_num,$child->camp_num,$child->parent_camp_num);
                }else{
                    $html.='<ul><li class="create-new-li"><span><a href="'.route('camp.create',['topicnum'=>$child->topic_num,'campnum'=>$child->camp_num]).'">&lt; Create A New Camp &gt;</a></span></li></ul>';
                }
                $html.='</li>';
        }
        $html.= '</ul>';
        return $html;
    }

}
