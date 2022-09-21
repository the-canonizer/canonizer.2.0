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

    public static function getHistory($topicnum, $campnum, $filter = array()) {

        return self::where('topic_num', $topicnum)->where('camp_num', $campnum)->latest('submit_time')->get();
    }

    public static function getCampStatements($topicnum, $campnum){
       $statements = self ::where('topic_num', $topicnum)
                            ->where('camp_num', $campnum)
                            ->where('objector_nick_id', '=', NULL)
                            ->orderBy('submit_time', 'desc')
                            ->first();
        return count($statements) ? 1 : 0 ;
    }
    public static function getLiveStatement($topicnum, $campnum, $filter = array()) {

        if ((isset($_REQUEST['asof']) && $_REQUEST['asof'] == "default")) {

            return self::where('topic_num', $topicnum)
                            ->where('camp_num', $campnum)
                            ->where('objector_nick_id', '=', NULL)
                            ->where('go_live_time', '<=', time())
                            ->orderBy('submit_time', 'desc')
                            ->first();
        } else {

            if (session('asofDefault')=="review" && !isset($_REQUEST['asof'])) {

                return self::where('topic_num', $topicnum)
                                ->where('camp_num', $campnum)
                                ->where('objector_nick_id', '=', NULL)
                                ->where('grace_period', 0) // ticket 1219 Muhammad Ahmad
                                ->orderBy('submit_time', 'desc')
                                ->first();
            }
			
			else if (isset($_REQUEST['asof']) && $_REQUEST['asof'] == "review") {

                return self::where('topic_num', $topicnum)
                                ->where('camp_num', $campnum)
                                ->where('objector_nick_id', '=', NULL)
                                ->where('grace_period', 0) // ticket 1219 Muhammad Ahmad
                                ->orderBy('go_live_time', 'desc') // ticket 1219 Muhammad Ahmad
                                ->first();
            } else if (isset($_REQUEST['asof']) && $_REQUEST['asof'] == "bydate") {

                $asofdate = strtotime(date('Y-m-d H:i:s', strtotime($_REQUEST['asofdate'])));
                return self::where('topic_num', $topicnum)
                                ->where('camp_num', $campnum)
                                ->where('go_live_time', '<=', $asofdate)
                                ->orderBy('go_live_time', 'desc')
                                ->first();
            } else {
				
				return self::where('topic_num', $topicnum)
                            ->where('camp_num', $campnum)
                            ->where('objector_nick_id', '=', NULL)
                            ->where('go_live_time', '<=', time())
                            ->orderBy('go_live_time', 'desc') // ticket 1219 Muhammad Ahmad
                            ->first();
			}
        }
    }
	public static function getAnyStatement($topicnum, $campnum, $filter = array()) {

       
            return self::where('topic_num', $topicnum)
                            ->where('camp_num', $campnum)->get();
       
    }

    public static function inReviewOrLiveStatementCount($topicnum, $campnum) {
        $currentTime = time();
        return self::where('topic_num', $topicnum)
                    ->where('camp_num', $campnum)
                    ->where(function($q) use ($currentTime) {
                        $q->where('grace_period', 0)
                        ->orWhere('go_live_time', '<=', $currentTime)
                        ->orWhere('submit_time', '<=', ($currentTime - 3600));
                    })->count();
    } 

}
