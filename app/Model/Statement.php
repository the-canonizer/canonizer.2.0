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
        return $this->hasOne('App\Model\Nickname', 'id', 'objector_nick_id');
    }
	public function submitternickname() {
        return $this->hasOne('App\Model\Nickname', 'id', 'submitter_nick_id');
    }
   
    public static function getHistory($topicnum,$campnum,$filter=array()) {
		
		return self::where('topic_num',$topicnum)->where('camp_num',$campnum)->latest('submit_time')->get();
	}
	public static function getLiveStatement($topicnum,$campnum,$filter=array()) {
		
		return self::where('topic_num',$topicnum)
		             ->where('camp_num',$campnum)
					 ->where('objector_nick_id', '=', NULL)
                     ->where('go_live_time','<=',time())
					 ->latest('submit_time')->first();
	}
	
}
