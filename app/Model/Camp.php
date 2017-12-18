<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Camp extends Model {

    protected $table = 'camp';
    public $timestamps = false;

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

}
