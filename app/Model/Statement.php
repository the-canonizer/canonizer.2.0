<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Statement extends Model {

    protected $table = 'statement';
    public $timestamps = false;

    protected static $tempArray = [];

    const AGREEMENT_CAMP = "Agreement";

    public static function boot() {
        
    }   
	
	public function objectornickname() {
        return $this->hasOne('App\Model\Nickname', 'nick_name_id', 'objector');
    }
	public function submitternickname() {
        return $this->hasOne('App\Model\Nickname', 'nick_name_id', 'submitter');
    }
   
    public static function getHistory($topicnum,$campnum,$filter=array()) {
		
		return self::where('topic_num',$topicnum)->where('camp_num',$campnum)->latest('submit_time')->get();
	}
	public static function getLiveStatement($topicnum,$campnum,$filter=array()) {
		
		return self::where('topic_num',$topicnum)
		             ->where('camp_num',$campnum)
					 ->where('objector', '=', NULL)
                     ->where('go_live_time','<=',time())
					 ->latest('submit_time')->first();
	}
	
}
