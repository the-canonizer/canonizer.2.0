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
                $nextCampNum = DB::table('camp')->max('camp_num');
                $nextCampNum++;
                $model->camp_num = $nextCampNum;
                $model->update();
            }
        });
    }

    public function children() {
        return $this->hasMany('App\Model\Camp', 'parent_camp_num','camp_num');
    }
    
    public function scopeChildrens($query, $topicnum,$parentcamp)
    {
        return $query->where('topic_num','=', $topicnum)->where('parent_camp_num','=',$parentcamp)->get();
    }

}
