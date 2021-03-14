<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Model\Camp;
use Illuminate\Support\Facades\Auth;

class Topic extends Model {

    protected $table = 'topic';
    public $timestamps = false;

    public static function boot() {
        static::created(function ($model) {
            // while creating topic for very first time
            // this will not run when updating 
            if ($model->topic_num == '' || $model->topic_num == null) {
                $nextTopicNum = DB::table('topic')->max('topic_num');
                $nextTopicNum++;
                $model->topic_num = $nextTopicNum;
                $model->update();

                //create agreement 
                $camp = new Camp();
                $camp->topic_num = $model->topic_num;
                $camp->parent_camp_num = null;
                $camp->key_words ='';
                $camp->language= $model->language;
                $camp->note = $model->note;
                $camp->submit_time = time();
                $camp->submitter_nick_id = $model->submitter_nick_id;
                $camp->go_live_time = $model->go_live_time;
                $camp->title = $model->topic_name;
                $camp->camp_name = Camp::AGREEMENT_CAMP;
                
                $camp->save();
                
            }
        });
        parent::boot();
    }

    public function camps() {
        return $this->hasMany('App\Model\Camp', 'topic_num', 'topic_num');
    }
    
    public function camps1() {
        return $this->hasMany('App\Model\Camp', 'topic_num', 'topic_num')->groupBy('camp_num');
    }
	public function topic() {
        return $this->hasOne('App\Model\Topic', 'topic_num', 'topic_num')->orderBy('go_live_time','DESC');
    }
	public function supports() {
        return $this->hasMany('App\Model\Support', 'topic_num', 'topic_num')->orderBy('support_order','ASC');
    }
	public function objectornickname() {
        return $this->hasOne('App\Model\Nickname', 'id', 'objector_nick_id');
    }
	public function submitternickname() {
        return $this->hasOne('App\Model\Nickname', 'id', 'submitter_nick_id');
    }
	public function topicnamespace() {
        return $this->hasOne('App\Model\Namespaces', 'id', 'namespace_id');
    }
	
	public function scopeGetsupports($query,$topic_num,$userNickname=null) {
		$as_of_time=time()+100;
		return $supports = Support::where('topic_num',$topic_num)		                    
							//->where('delegate_nick_name_id',0)
							->whereIn('nick_name_id',$userNickname)
							->whereRaw("(start < $as_of_time) and ((end = 0) or (end > $as_of_time))")
							->orderBy('support_order','ASC')
                            ->groupBy('camp_num')							
							->get();
		
	}
	
	public static function getHistory($topicnum,$filter=array()) {
		
		return self::where('topic_num',$topicnum)->latest('submit_time')->get();
	}

      public static function getLiveTopic($topicnum, $filter = array()) {
        if ((!isset($_REQUEST['asof']) && !session()->has('asofDefault')) || (isset($_REQUEST['asof']) && $_REQUEST['asof'] == "default")  || (session()->has('asofDefault') && session('asofDefault') == 'default' && !isset($_REQUEST['asof']))) {

            return self::where('topic_num', $topicnum)
                            ->where('objector_nick_id', '=', NULL)
                            ->where('go_live_time', '<=', time())
                            ->latest('submit_time')->first();
        } else {

            if ((isset($_REQUEST['asof']) && $_REQUEST['asof'] == "review") || (session('asofDefault')=="review" && !isset($_REQUEST['asof']))) {

                return self::where('topic_num', $topicnum)
                                ->where('objector_nick_id', '=', NULL)
                                ->latest('submit_time')->first();
            } else if ((isset($_REQUEST['asof']) && $_REQUEST['asof'] == "bydate")  || (session()->has('asofDefault') && session('asofDefault') == 'bydate' && !isset($_REQUEST['asof']))) {
                if(isset($_REQUEST['asof']) && $_REQUEST['asof'] == "bydate"){
                    $asofdate = strtotime(date('Y-m-d H:i:s', strtotime($_REQUEST['asofdate'])));
                }else if(session()->has('asofdateDefault') && session('asofdateDefault') && !isset($_REQUEST['asof'])){
                    $asofdate = strtotime(session('asofdateDefault'));
                }
                if(isset($filter['nofilter']) && $filter['nofilter']){
                    $asofdate  = time();
                }
                
                return self::where('topic_num', $topicnum)
                                ->where('objector_nick_id', '=', NULL)
                                ->where('go_live_time', '<=', $asofdate)
                                ->latest('submit_time')->first();
            }
        }
    }
	

}
