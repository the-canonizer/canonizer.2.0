<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Nickname;
use DB;
use App\Model\Algorithm;
use App\Model\TopicSupport;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Collection;
use App\Facades\Util;

class Camp extends Model {

    protected $table = 'camp';
    public $timestamps = false;
    public $support_order = 0;
    protected static $tempArray = [];
    protected static $childtempArray = [];
    protected static $chilcampArray = [];
    protected static $traversetempArray = [];
    protected static $campChildren = [];
    protected static $totalSupports = [];
    protected static $totalNickNameSupports = [];

    const AGREEMENT_CAMP = "Agreement";

    public function topic() {
        $as_of_time = time();
        if (isset($_REQUEST['asof']) && $_REQUEST['asof'] == 'bydate') {
            $as_of_time = strtotime($_REQUEST['asofdate']);
        }else if(session()->has('asofDefault') && session('asofDefault') == 'bydate' && !isset($_REQUEST['asof'])){
            $as_of_time = strtotime(session('asofdateDefault'));
        }

        if ((isset($_REQUEST['asof']) && $_REQUEST['asof'] == "review") || (session('asofDefault')=="review" && !isset($_REQUEST['asof']))) {
            return $this->hasOne('App\Model\Topic', 'topic_num', 'topic_num')->where('objector_nick_id', '=', NULL)->orderBy('submit_time', 'DESC');
        } else {
            return $this->hasOne('App\Model\Topic', 'topic_num', 'topic_num')
                ->where('go_live_time', '<=', $as_of_time)
                ->where('objector_nick_id', '=', NULL)
                ->orderBy('go_live_time', 'DESC');
        }
    }

    public function nickname() {
        return $this->hasOne('App\Model\Nickname', 'id', 'camp_about_nick_id');
    }

    public function objectornickname() {
        return $this->hasOne('App\Model\Nickname', 'id', 'objector_nick_id');
    }

    public function submitternickname() {
        return $this->hasOne('App\Model\Nickname', 'id', 'submitter_nick_id');
    }

    public static function boot() {
        static::created(function ($model) {
            if ($model->camp_num == '' || $model->camp_num == null) {
                $nextCampNum = DB::table('camp')->where('topic_num', '=', $model->topic_num)->max('camp_num');
                $nextCampNum++;
                $model->camp_num = $nextCampNum;
                $model->update();
            }
        });
    }

    public function children() {
        return $this->hasMany('App\Model\Camp', 'parent_camp_num', 'camp_num');
    }

    public function scopeChildrens($query, $topicnum, $parentcamp, $campnum = null, $filter = array()) {
        $childs = session("topic-child-{$topicnum}")->filter(function($item) use($parentcamp, $campnum) {
            if ($campnum) {
                return $item->parent_camp_num == $parentcamp && $item->camp_num == $campnum;
            } else {
                return $item->parent_camp_num == $parentcamp;
            }
        });
        return $childs;
    }

    public function scopeStatement($query, $topicnum, $campnum) {
        $statement = Statement::getLiveStatement($topicnum, $campnum);
        return $statement;
    }

	public function scopeAnystatement($query, $topicnum, $campnum) {

        $statement = Statement::getAnyStatement($topicnum, $campnum);
     
        return $statement;
    }

    public static function checkifSubscriber($subscribers,$user){
        $flag = false;
        foreach($subscribers as $sub){
            if($sub == $user->id){
                $flag = true;
                break;
            }
        }
        return $flag;
    }

    public static function checkifDirectSupporter($directSupporter,$nick_id){
        $flag =false;
        foreach($directSupporter as $sup){
            if($sup->nick_name_id == $nick_id){
                $flag = true;
                break;
            }
        }
        return $flag;
    }

    public function scopeGetSupportedNicknames($query, $topicnum, $campnum = null) {
        $query = TopicSupport::where('topic_num', '=', $topicnum)
                ->groupBy('nick_name_id');

        if ($campnum != null) {

            $query = TopicSupport::join('support_instance', 'support_instance.topic_support_id', '=', 'topic_support.id')
                    ->where('support_instance.camp_num', '=', $campnum)
                    ->where('topic_num', '=', $topicnum)
                    ->groupBy('nick_name_id');
        }

        return $nicknames = $query->get();
    }

    public function scopeGetSupportByNickname($query, $topicnum, $nicknameId) {
        $support = TopicSupport::where('topic_num', '=', $topicnum)
                ->where('nick_name_id', '=', $nicknameId)
                ->groupBy('topic_num')
                ->orderBy('submit_time', 'DESC')
                ->first();
        return $support;
    }

    public function scopeCampNameWithAncestors($query, $camp, $campname = '',$title = '', $breadcrum = false) {
        $as_of_time = time();
        if (isset($_REQUEST['asof']) && $_REQUEST['asof'] == 'bydate') {
            $as_of_time = strtotime($_REQUEST['asofdate']);
        }
        if (!empty($camp)) {
            if ($campname != '') { 
                $url = self::getTopicCampUrl($camp->topic_num,$camp->camp_num);
                if($breadcrum){
                    $campname = "<a href='" . $url . "'>" . ($title) . '</a> / ' . ($campname);
                }else
                if (isset($_REQUEST['asof']) && $_REQUEST['asof'] == 'review') {
                    $mode_camp = self::getLiveCamp($camp->topic_num,$camp->camp_num,['nofilter'=>true]);
                    $campname = "<a href='" . $url . "'>" . ($mode_camp->camp_name) . '</a> / ' . ($campname);
                } else {
                    $campname = "<a href='" . $url . "'>" . ($camp->camp_name) . '</a> / ' . ($campname);
                }
            } else { 
                $url = self::getTopicCampUrl($camp->topic_num,$camp->camp_num);
                if($breadcrum){
                    $campname = "<a href='" . $url . "'>" . ($camp->camp_name) . '</a>';
                }else
                $campname = "<a href='" . $url . "'>" . ($camp->camp_name) . '</a>';
            }
            
            if (isset($camp) && $camp->parent_camp_num) {
                /**
                 * Fetch objected ones as well for topic history
                 * ticket 1219 Muhammad Ahmad
                 */
                $pcamp = Camp::where('topic_num', $camp->topic_num)
                    ->where('camp_num', $camp->parent_camp_num)
                    // ->where('camp_name', '!=', 'Agreement')   
                    //->where('objector_nick_id', '=', NULL)
                    //->whereRaw('go_live_time in (select max(go_live_time) from camp where topic_num=' . $camp->topic_num . ' and objector_nick_id is null group by camp_num)')
                    ->where('go_live_time', '<=', $as_of_time)
                    ->orderBy('go_live_time', 'DESC')->first();

                return self::campNameWithAncestors($pcamp, $campname,$title,$breadcrum);
            }
        }
        return $campname;
    }

    public static function getBrowseTopic() {

        $as_of_time = time();
        $nicknameIds = Nickname::personNicknameIds();
        if (isset($_REQUEST['asof']) && $_REQUEST['asof'] == 'bydate') {
            $as_of_time = strtotime($_REQUEST['asofdate']);
        }else if(session()->has('asofDefault') && session('asofDefault') == 'bydate' && !isset($_REQUEST['asof']) ){
            $as_of_time = strtotime(session('asofdateDefault'));
        }
        $query = Topic::select('topic.go_live_time', 'topic.topic_name', 'topic.namespace_id','namespace.name as namespace', 'namespace.name', 'topic.topic_num', 'camp.title', 'camp.camp_num')
                ->join('camp', 'topic.topic_num', '=', 'camp.topic_num')
                ->join('namespace', 'topic.namespace_id', '=', 'namespace.id')
                ->where('camp_name', '=', 'Agreement')
                ->where('camp.objector_nick_id', '=', NULL)
                ->where('topic.topic_name', '<>', "");
        if (isset($_REQUEST['namespace']) && (!empty($_REQUEST['namespace']) || $_REQUEST['namespace'] != 0)) {
            $query->where('namespace_id', $_REQUEST['namespace']);
        }else if( null !== session('defaultNamespaceId') && !empty(session('defaultNamespaceId'))){
            $query->whereIn('namespace_id',explode(',', session('defaultNamespaceId', 1)));
        }

        if (isset($_REQUEST['asof']) && $_REQUEST['asof'] == 'review') {
            $query->whereRaw('topic.go_live_time in (select max(topic.go_live_time) from topic where topic.topic_num=topic.topic_num and topic.objector_nick_id is null group by topic.topic_num)');
          
        }else{
            $query->where('camp.go_live_time', '<=', $as_of_time);
            $query->whereRaw('topic.go_live_time in (select max(topic.go_live_time) from topic where topic.topic_num=topic.topic_num and topic.objector_nick_id is null and topic.go_live_time <=' . $as_of_time . ' group by topic.topic_num)');

        }
        
        if(isset($_REQUEST['my']) && $_REQUEST['my'] == $_REQUEST['namespace']){
            $query->whereIn('camp.submitter_nick_id', $nicknameIds);
        }
        return $query->orderBy('namespace.name', 'ASC')->orderBy('topic.topic_name', 'ASC')->orderBy('topic.go_live_time', 'DESC')->groupBy('topic_num')->get();
    }

    public static function getAgreementTopic($topicnum, $filter = array()) {
        if ((!isset($filter['asof']) && !session()->has('asofDefault')) || (isset($filter['asof']) && $filter['asof'] == "default") || (session()->has('asofDefault') && session('asofDefault') == 'default' && !isset($filter['asof']))) {
            return self::select('topic.topic_name','topic.namespace_id', 'camp.*', 'namespace.name as namespace_name', 'namespace.name')
                            ->join('topic', 'topic.topic_num', '=', 'camp.topic_num')
                            ->join('namespace', 'topic.namespace_id', '=', 'namespace.id')
                            ->where('topic.topic_num', $topicnum)->where('camp_name', '=', 'Agreement')
                            ->where('camp.objector_nick_id', '=', NULL)
                            ->where('topic.objector_nick_id', '=', NULL)
                            ->where('camp.go_live_time', '<=', time())
							->where('topic.go_live_time', '<=', time())
                            ->latest('topic.submit_time')->first();
        } else {

            if ((isset($filter['asof']) && $filter['asof'] == "review") || (session('asofDefault')=="review" && !isset($filter['asof']))) {
                /**
                 * Fetch topic with grace period 0 as need to fetch reveiw topic only with change commited 
                 * If not topic found with change committed, fetch live topic
                 * ticket 1219 Muhammad Ahmad
                 */
                $query = self::select('topic.topic_name', 'topic.namespace_id','camp.*', 'namespace.name as namespace_name','namespace.name')
                                ->join('topic', 'topic.topic_num', '=', 'camp.topic_num')
                                ->join('namespace', 'topic.namespace_id', '=', 'namespace.id')
                                ->where('camp.topic_num', $topicnum)->where('camp_name', '=', 'Agreement')
                                ->where('camp.objector_nick_id', '=', NULL)
                                ->where('topic.objector_nick_id', '=', NULL)
                                ->where('topic.grace_period', 0)
                                ->latest('topic.go_live_time')->first();

                if(!$query) {
                    $query = self::select('topic.topic_name','topic.namespace_id', 'camp.*', 'namespace.name as namespace_name', 'namespace.name')
                                ->join('topic', 'topic.topic_num', '=', 'camp.topic_num')
                                ->join('namespace', 'topic.namespace_id', '=', 'namespace.id')
                                ->where('topic.topic_num', $topicnum)->where('camp_name', '=', 'Agreement')
                                ->where('camp.objector_nick_id', '=', NULL)
                                ->where('topic.objector_nick_id', '=', NULL)
                                ->where('camp.go_live_time', '<=', time())
                                ->where('topic.go_live_time', '<=', time())
                                ->latest('topic.submit_time')->first();
                }

                return $query;
            } else if (isset($filter['asof']) && $filter['asof'] == "bydate" || (session()->has('asofDefault') && session('asofDefault') == 'bydate' && !isset($filter['asof']))) {
                if(isset($filter['asof']) && $filter['asof'] == "bydate"){
                    $asofdate = strtotime(date('Y-m-d H:i:s', strtotime($filter['asofdate'])));   
                    
                }else if(session('asofDefault') == 'bydate' && !isset($filter['asof'])){
                    $asofdate = strtotime(session('asofdateDefault'));
                }
                if(isset($filter['nofilter']) && $filter['nofilter']){
                    $asofdate  = time();
                }
                /**
                 * Fetch objected topics as well to show topic history for rejected changes
                 * ticket 1219 Muhammad Ahmad
                 */
                return self::select('topic.topic_name','topic.namespace_id', 'camp.*', 'namespace.name as namespace_name','namespace.name')
                                ->join('topic', 'topic.topic_num', '=', 'camp.topic_num')
                                ->join('namespace', 'topic.namespace_id', '=', 'namespace.id')
                                ->where('camp.topic_num', $topicnum)->where('camp_name', '=', 'Agreement')
                                // ->where('camp.objector_nick_id', '=', NULL)
                                // ->where('topic.objector_nick_id', '=', NULL)
                                ->where('topic.go_live_time', '<=', $asofdate)
                                ->latest('topic.go_live_time')->first();
                
            }
        }
    }

    public static function getAllAgreementTopic($limit = 10, $filter = array()) {

        $as_of_time = time();
        $returnTopics = [];

        if ((!isset($filter['asof']) && !session('asofDefault')) || (isset($filter['asof']) && $filter['asof'] == "default") || (session()->has('asofDefault') && session('asofDefault') == 'default' && !isset($filter['asof']))) {
            
            $returnTopics = DB::table('camp')->select(DB::raw('(select count(topic_support.id) from topic_support where topic_support.topic_num=camp.topic_num) as support, camp.*'))
                            ->join('topic', 'topic.topic_num', '=', 'camp.topic_num')
                            ->where('camp_name', '=', 'Agreement')
                            ->where('topic.objector_nick_id', '=', NULL)
                            ->whereIn('namespace_id', explode(',', session('defaultNamespaceId', 1)))
                            ->where('camp.go_live_time', '<=', $as_of_time)
                            ->whereRaw('topic.go_live_time in (select max(topic.go_live_time) from topic where topic.objector_nick_id is null and topic.go_live_time <=' . $as_of_time . ' group by topic.topic_num)')
                            ->latest('support')->groupBy('topic.topic_num')->orderBy('topic.topic_name', 'DESC')->paginate($limit,['camp.topic_num']);

        } else {
            if ((isset($filter['asof']) && $filter['asof'] == "review") || (session('asofDefault')=="review" && !isset($filter['asof']))) {
                $returnTopics =  DB::table('camp')->select(DB::raw('(select count(topic_support.id) from topic_support where topic_support.topic_num=camp.topic_num) as support, camp.*'))
                            ->join('topic', 'topic.topic_num', '=', 'camp.topic_num')
                            ->where('camp_name', '=', 'Agreement')
                            ->where('topic.objector_nick_id', '=', NULL)
                            ->whereIn('namespace_id', explode(',', session('defaultNamespaceId', 1)))
                            ->whereRaw('topic.go_live_time in (select max(topic.go_live_time) from topic where topic.objector_nick_id is null group by topic.topic_num)')
                            ->latest('support')->groupBy('topic.topic_num')->orderBy('topic.topic_name', 'DESC')->paginate($limit,['camp.topic_num']);

			} else if ((isset($filter['asof']) && $filter['asof'] == "bydate") || (session()->has('asofDefault') && session('asofDefault') == 'bydate' && !isset($filter['asof']))) {
                 if(isset($filter['asof']) && $filter['asof'] == "bydate"){                    
                  $asofdate = strtotime(date('Y-m-d H:i:s', strtotime($filter['asofdate']))); 
                 }else if(session('asofdateDefault')!='' && !isset($filter['asof'])){    
                     $asofdate = strtotime(date('Y-m-d H:i:s', strtotime(session('asofdateDefault'))));
                 }

                 $returnTopics =  DB::table('camp')->select(DB::raw('(select count(topic_support.id) from topic_support where topic_support.topic_num=camp.topic_num) as support, camp.*'))
                     ->join('topic', 'topic.topic_num', '=', 'camp.topic_num')
                     ->where('camp_name', '=', 'Agreement')                   
                    ->whereIn('namespace_id', explode(',', session('defaultNamespaceId',1)))
                    ->where('topic.objector_nick_id', '=', NULL)
                    ->where('camp.go_live_time', '<=', $asofdate)
                    ->whereRaw('topic.go_live_time in (select max(topic.go_live_time) from topic where topic.objector_nick_id is null and topic.go_live_time <=' . $asofdate . ' group by topic.topic_num)')
                    ->latest('support')->groupBy('topic.topic_num')->orderBy('topic.topic_name', 'DESC')->paginate($limit,['camp.topic_num']);
            }
        }
        
        return $returnTopics;
    }

    public static function getAllLoadMoreTopic($offset = 10, $filter = array(), $id) {
         $as_of_time = time();
        if ((!isset($filter['asof']) && !session()->has('asofDefault')) || (isset($filter['asof']) && $filter['asof'] == "default") || (session()->has('asofDefault') && session('asofDefault') == 'default' && !isset($filter['asof']))) {
            return self::select(DB::raw('(select count(topic_support.id) from topic_support where topic_support.topic_num=camp.topic_num) as support, camp.*'))
                            ->join('topic', 'topic.topic_num', '=', 'camp.topic_num')
                            ->where('camp_name', '=', 'Agreement')
                            ->where('topic.objector_nick_id', '=', NULL)
                            ->whereIn('namespace_id', explode(',', session('defaultNamespaceId', 1)))
                            ->where('camp.go_live_time', '<=', $as_of_time)
                            ->whereRaw('topic.go_live_time in (select max(topic.go_live_time) from topic where topic.topic_num=topic.topic_num and topic.objector_nick_id is null and topic.go_live_time <=' . $as_of_time . ' group by topic.topic_num)')
                           
                            ->where('topic.go_live_time', '<=', time())->latest('camp.submit_time')->take(10000)->offset(18)->get()->unique('topic_num');

                          //  ->where('topic.go_live_time', '<=', time())->latest('camp.submit_time')->offset($offset)->take(10000)->get()->unique('topic_num');

        } else {

            if ((isset($filter['asof']) && $filter['asof'] == "review") || (session('asofDefault')=="review" && !isset($filter['asof']))) {

            return self::where('camp_name', '=', 'Agreement')->join('topic', 'topic.topic_num', '=', 'camp.topic_num')->whereIn('namespace_id', explode(',', session('defaultNamespaceId',1)))->latest('camp.submit_time')->offset($offset)->take(10000)->offset($offset)->get();
            } else if ((isset($filter['asof']) && $filter['asof'] == "bydate") || (session()->has('asofDefault') && session('asofDefault') == 'bydate' && !isset($filter['asof']))) {

                if(isset($filter['asof']) && $filter['asof'] == "bydate"){
                    $asofdate = strtotime(date('Y-m-d H:i:s', strtotime($filter['asofdate'])));
                }else if(session()->has('asofdateDefault') && session('asofdateDefault')!='' && !isset($filter['asof'])){
                        $asofdate = strtotime(session('asofdateDefault'));
                }
                

                return self::where('camp_name', '=', 'Agreement')
				       ->join('topic', 'topic.topic_num', '=', 'camp.topic_num')
					   ->where('topic.objector_nick_id', '=', NULL)
					   ->whereIn('namespace_id', explode(',', session('defaultNamespaceId', 1)))
					   ->where('camp.go_live_time', '<=', $asofdate)
					   ->whereRaw('topic.go_live_time in (select max(topic.go_live_time) from topic where topic.topic_num=topic.topic_num and topic.objector_nick_id is null and topic.go_live_time <=' . $asofdate . ' group by topic.topic_num)')
                           
					   ->latest('camp.submit_time')->offset($offset)->take(10000)->get()->unique('topic_num');
            }
        }
    }

    public static function getLiveCamp($topicnum, $campnum, $filter = array()) {
        if ((!isset($_REQUEST['asof']) && !session()->has('asofDefault')) || (isset($_REQUEST['asof']) && $_REQUEST['asof'] == "default")  || (session()->has('asofDefault') && session('asofDefault') == 'default' && !isset($_REQUEST['asof']))) {

            return self::where('topic_num', $topicnum)
                            ->where('camp_num', '=', $campnum)
                            ->where('objector_nick_id', '=', NULL)
                            ->where('go_live_time', '<=', time())
                            ->orderBy('go_live_time','desc')->first();
        } else {

            if ((isset($_REQUEST['asof']) && $_REQUEST['asof'] == "review") || (session('asofDefault')=="review" && !isset($_REQUEST['asof']))) {

                return self::where('topic_num', $topicnum)
                                ->where('camp_num', '=', $campnum)
                                ->where('objector_nick_id', '=', NULL)
                                ->where('grace_period', 0) // ticket 1219 Muhammad Ahmad
                                ->orderBy('go_live_time','desc')->first();
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
                                ->where('camp_num', '=', $campnum)
                                //->where('objector_nick_id', '=', NULL)
                                ->where('go_live_time', '<=', $asofdate)
                                ->orderBy('go_live_time','desc')->first();
            }
        }
    }

    public static function getAllParentCamp($topicnum,$filter=array()) {
        if ((isset($_REQUEST['asof']) && $_REQUEST['asof'] == "bydate") || (session()->has('asofDefault') && session('asofDefault') == 'bydate') && !isset($_REQUEST['asof'])) {
            if(isset($_REQUEST['asof']) && $_REQUEST['asof'] == "bydate"){
                $asofdate = strtotime(date('Y-m-d H:i:s', strtotime($_REQUEST['asofdate'])));
            }else if(session()->has('asofdateDefault') && session('asofdateDefault')!='' && !isset($_REQUEST['asof'])){
                $asofdate =  strtotime(session('asofdateDefault'));
            }
        } else {

            $asofdate = time();
        }
        if(isset($filter['nofilter']) && $filter['nofilter']){
                    $asofdate  = time();
                }
        return self::where('topic_num', $topicnum)
                        ->where('objector_nick_id', '=', NULL)
                        ->where('go_live_time', '<=', $asofdate)
                        ->whereRaw('go_live_time in (select max(go_live_time) from camp where topic_num=' . $topicnum . ' and objector_nick_id is null and go_live_time < "' . $asofdate . '" group by camp_num)')
                        ->orderBy('submit_time', 'camp_name')->groupBy('camp_num')->get();
    }

    public static function getAllParentCampNew($topicnum) {
        return self::where('topic_num', $topicnum)
                        ->where('objector_nick_id', '=', NULL)
                        ->whereRaw('go_live_time in (select max(go_live_time) from camp where topic_num=' . $topicnum . ' and objector_nick_id is null  group by camp_num)')
                        ->orderBy('submit_time', 'camp_name')->groupBy('camp_num')->get();
    }

	public static function getAllTopicCamp($topicnum) {
        return self::where('topic_num', $topicnum)
                        ->where('objector_nick_id', '=', NULL)
                        ->orderBy('submit_time', 'camp_name')->get();
    }

    public static function getCampHistory($topicnum, $campnum, $filter = array()) {
        return self::where('topic_num', $topicnum)->where('camp_num', '=', $campnum)->latest('submit_time')->get();
    }

    public static function getAllParent($camp, $camparray = array()) {
        
        if (!empty($camp)) {
            if ($camp->parent_camp_num) {
                $camparray[] = $camp->parent_camp_num;

                $pcamp = self::getLiveCamp($camp->topic_num, $camp->parent_camp_num); //Camp::where('topic_num', $camp->topic_num)->where('camp_num', $camp->parent_camp_num)->groupBy('camp_num')->orderBy('submit_time', 'desc')->first();
                return self::getAllParent($pcamp, $camparray);
            }
        }
        return $camparray;
    }

    public static function getAllChildCamps($camp, $includeLiveCamps=false) {
        $camparray = [];
        if ($camp) {
            $key = $camp->topic_num . '-' . $camp->camp_num . '-' . $camp->parent_camp_num;
            $key1 = $camp->topic_num . '-' . $camp->parent_camp_num . '-' . $camp->camp_num;
           if (in_array($key, Camp::$chilcampArray) || in_array($key1, Camp::$chilcampArray)) {
                return [];/** Skip repeated recursions* */
            }
            Camp::$chilcampArray[] = $key;
            Camp::$chilcampArray[] = $key1;
            $camparray[] = $camp->camp_num;
            if($includeLiveCamps){
                //adding go_live_time condition Sunil Talentelgia //->where('go_live_time', '<=', time())
                $childCamps = Camp::where('topic_num', $camp->topic_num)->where('parent_camp_num', $camp->camp_num)->where('go_live_time', '<=', time())->groupBy('camp_num')->latest('submit_time')->get();
           
            }
            else{
                $childCamps = Camp::where('topic_num', $camp->topic_num)->where('parent_camp_num', $camp->camp_num)->groupBy('camp_num')->latest('submit_time')->get();
            }
            foreach ($childCamps as $child) {
                /**
                 * Adding check to skip camps rejected ones
                 * ticket # 1310 - Muhammad Ahmad
                 */
                if($includeLiveCamps){
                    //adding go_live_time condition Sunil Talentelgia //->where('go_live_time', '<=', time())
                    // $latestParent = Camp::where('topic_num', $child->topic_num)->where('camp_num', $child->camp_num)->latest('submit_time')->where('go_live_time', '<=', time())->where('objector_nick_id', NULL)->first();
                    $latestParent = Camp::where('topic_num', $child->topic_num)->where('camp_num', $child->camp_num)->latest('submit_time')->where('go_live_time', '<=', time())->first();
                }
                else{
                    //$latestParent = Camp::where('topic_num', $child->topic_num)->where('camp_num', $child->camp_num)->latest('submit_time')->where('objector_nick_id', NULL)->first();
                    $latestParent = Camp::where('topic_num', $child->topic_num)->where('camp_num', $child->camp_num)->latest('submit_time')->first();
                }
                if($latestParent->parent_camp_num == $camp->camp_num ){ 
                    $camparray = array_merge($camparray, self::getAllChildCamps($child)); 

                }
                
            }
        }

        return $camparray;
    }

    public function getAllChild($topicnum, $parentcamp, $lastparent = null, $campArray = array()) {

        $key = $topicnum . '-' . $parentcamp . '-' . $lastparent;
        if (in_array($key, Camp::$childtempArray)) {
            // dd($key,Camp::$childtempArray);
            //dd($key,Camp::$childtempArray);
            return;/** Skip repeated recursions* */
        }
        Camp::$childtempArray[] = $key;
        $childs = $this->campChild($topicnum, $parentcamp);


        //Camp::$chilcampArray = Camp::$chilcampArray + $campArray;
        $result = array();

        foreach ($childs as $key => $child) {
            $childCount = count($child->campChild($child->topic_num, $child->camp_num));
            $_SESSION['childs'][$lastparent][] = $child->camp_num;

            if ($childCount > 0) {
                $this->getAllChild($child->topic_num, $child->camp_num, $parentcamp, $campArray);
            }
        }
        if (isset($_SESSION['childs'][$lastparent]))
            $result = array_unique($_SESSION['childs'][$lastparent]);

        return $result;
    }

    public function campChild($topicnum, $parentcamp) {

        $childsData = Camp::where('topic_num', '=', $topicnum)
                        ->where('parent_camp_num', '=', $parentcamp)
                        ->where('camp_name', '!=', 'Agreement')
                        ->where('objector_nick_id', '=', NULL)
                        //->where('go_live_time','<=',time()) 				
                        //->orderBy('submit_time', 'desc')
                        ->get()->unique('camp_num');
        return $childsData;
    }

    public static function validateParentsupport($topic_num, $camp_num, $userNicknames, $confirm_support) {

        $onecamp = self::getLiveCamp($topic_num, $camp_num);
        if (count($onecamp) <= 0) {
            return 'notlive';
        }

        $parentcamps = self::getAllParent($onecamp);

        $mysupports = Support::where('topic_num', $topic_num)->whereIn('camp_num', $parentcamps)->whereIn('nick_name_id', $userNicknames)->where('end', '=', 0)->orderBy('support_order', 'ASC')->get();

        /* if($confirm_support && count($mysupports)) {

          $mysupports->end = time();
          $mysupports->update();

          $mysupports     = Support::where('topic_num',$topic_num)->whereIn('camp_num',$parentcamps)->whereIn('nick_name_id',$userNicknames)->where('end','=',0)->groupBy('topic_num')->orderBy('support_order','ASC')->first();

          } */


        if (count($mysupports))
            return $mysupports;
        else
            return false;
    }

    public static function validateChildsupport($topic_num, $camp_num, $userNicknames, $confirm_support) {

        $onecamp = self::getLiveCamp($topic_num, $camp_num);

        $childCamps = array_unique(self::getAllChildCamps($onecamp,$includeLiveCamps=true));

        // $mysupports = Support::where('topic_num', $topic_num)->whereIn('camp_num', $childCamps)->whereIn('nick_name_id', $userNicknames)->where('end', '=', 0)->where('delegate_nick_name_id','=',0)->orderBy('support_order', 'ASC')->groupBy('camp_num')->get();
        // Fixes #912: Warning is missing while supporting agreement camp (after delegate support)
        $mysupports = Support::where('topic_num', $topic_num)->whereIn('camp_num', $childCamps)->whereIn('nick_name_id', $userNicknames)->where('end', '=', 0)->orderBy('support_order', 'ASC')->groupBy('camp_num')->get();

        /* if($confirm_support && count($mysupports)) {

          $mysupports->end = time();
          $mysupports->update();

          $mysupports     = Support::where('topic_num',$topic_num)->whereIn('camp_num',$childCamps)->whereIn('nick_name_id',$userNicknames)->where('end','=',0)->groupBy('topic_num')->orderBy('support_order','ASC')->first();

          } */

        if (count($mysupports))
            return $mysupports;
        else
            return false;
    }

    public function sortByOrder($a, $b) {
        $a = $a['support_order'];
        $b = $b['support_order'];

        if ($a == $b)
            return 0;
        return ($a > $b) ? -1 : 1;
    }

    

    public function getDeletegatedSupportCount($algorithm, $topicnum, $campnum, $delegateNickId, $parent_support_order, $multiSupport) {

        /* Delegated Support */
        if(session()->has("topic-support-{$topicnum}")){
            $delegatedSupports = session("topic-support-{$topicnum}")->filter(function($item) use ($delegateNickId) {
                return $item->delegate_nick_name_id == $delegateNickId;
            });
        }else{
            $as_of_time = time();
            if ((isset($_REQUEST['asof']) && $_REQUEST['asof'] == 'bydate')) {
                $as_of_time = strtotime($_REQUEST['asofdate']);
            }else if((session()->has('asof') && session('asof') == 'bydate' && !isset($_REQUEST['asof']))){
                $as_of_time = strtotime(session('asofdateDefault'));
            } 
            session(["topic-support-{$this->topic_num}" => Support::where('topic_num', '=', $this->topic_num)
                        ->whereRaw("(start <= $as_of_time) and ((end = 0) or (end > $as_of_time))")
                        ->orderBy('start', 'DESC')
                        ->select(['support_order', 'camp_num', 'nick_name_id', 'delegate_nick_name_id', 'topic_num'])
                        ->get()]);
            $delegatedSupports = session("topic-support-{$topicnum}")->filter(function($item) use ($delegateNickId) {
                return $item->delegate_nick_name_id == $delegateNickId;
            });
        }

        if(count($delegatedSupports) > 0){
            foreach($delegatedSupports as $support){
                     if(array_key_exists($support->nick_name_id, $nick_name_wise_support)){
                             array_push($nick_name_wise_support[$support->nick_name_id],$support);
                     }else{
                         $nick_name_wise_support[$support->nick_name_id] = [];
                         array_push($nick_name_wise_support[$support->nick_name_id],$support);
                     }                    
            }
         }   


        $score = 0;
        foreach ($delegatedSupports as $support) {
            $supportPoint = Algorithm::{$algorithm}($support->nick_name_id,$support->topic_num, $support->camp_num);
            //Check for campnum
            if($campnum == $support['camp_num']){
                if ($multiSupport) {
                    $score += round($supportPoint / (2 ** ($parent_support_order)), 2);
                } else {
                    $score += $supportPoint;
                }
                $score += $this->getDeletegatedSupportCount($algorithm, $topicnum, $campnum, $support->nick_name_id, $parent_support_order, $multiSupport);
            }
            
        }
        
        return $score;
    }

    public function delegateSupportTree($algorithm, $topicnum, $campnum, $delegateNickId, $parent_support_order, $parent_score,$multiSupport,$array=[]){
        $nick_name_support_tree=[];
        $nick_name_wise_support=[];
        $is_add_reminder_back_flag = ($algorithm == 'blind_popularity') ? 1 : 0;
		/* Delegated Support */
        if(session()->has("topic-support-{$topicnum}")){
            $delegatedSupports = session("topic-support-{$topicnum}")->filter(function($item) use ($delegateNickId) {
                return $item->delegate_nick_name_id == $delegateNickId;
            });
        }else{
            $as_of_time = time();
            if ((isset($_REQUEST['asof']) && $_REQUEST['asof'] == 'bydate')) {
                $as_of_time = strtotime($_REQUEST['asofdate']);
            }else if((session()->has('asof') && session('asof') == 'bydate' && !isset($_REQUEST['asof']))){
                $as_of_time = strtotime(session('asofdateDefault'));
            } 
            session(["topic-support-{$this->topic_num}" => Support::where('topic_num', '=', $this->topic_num)
                        ->whereRaw("(start <= $as_of_time) and ((end = 0) or (end > $as_of_time))")
                        ->orderBy('start', 'DESC')
                        ->select(['support_order', 'camp_num', 'nick_name_id', 'delegate_nick_name_id', 'topic_num'])
                        ->get()]);
            $delegatedSupports = session("topic-support-{$topicnum}")->filter(function($item) use ($delegateNickId) {
                return $item->delegate_nick_name_id == $delegateNickId;
            });
        }

        
        
        if(count($delegatedSupports) > 0){
           foreach($delegatedSupports as $support){
                    if(array_key_exists($support->nick_name_id, $nick_name_wise_support)){
                            array_push($nick_name_wise_support[$support->nick_name_id],$support);
                    }else{
                        $nick_name_wise_support[$support->nick_name_id] = [];
                        array_push($nick_name_wise_support[$support->nick_name_id],$support);
                    }              
           }
        }
        
        foreach($nick_name_wise_support as $nickNameId=>$support_camp){
           foreach($support_camp as $support){ 
               if($support->camp_num == $campnum){
                    $support_total = 0; 
                    if($multiSupport){
                        $support_total = $support_total + round($supportPoint * 1 / (2 ** ($support->support_order)), 3);
                    }else{
                        $support_total = $support_total + $supportPoint;
                    } 
                    $nick_name_support_tree[$support->nick_name_id]['score'] = ($is_add_reminder_back_flag) ? $parent_score : $support_total;
                    $delegateTree = $this->delegateSupportTree($algorithm, $topicnum,$campnum, $support->nick_name_id, $parent_support_order,$parent_score,$multiSupport,[]);
                    $nick_name_support_tree[$support->nick_name_id]['delegates'] = $delegateTree;
                }               
               }
        }
       return $nick_name_support_tree;
    }

    public function getCampAndNickNameWiseSupportTree($algorithm, $topicnum){
        $as_of_time = time();
        $is_add_reminder_back_flag = ($algorithm == 'blind_popularity') ? 1 : 0;
        $nick_name_support_tree=[];
        $nick_name_wise_support=[];
        $camp_wise_support = [];
        $camp_wise_score = [];
		if(isset($_REQUEST['asof']) && $_REQUEST['asof']=='bydate'){
			$as_of_time = strtotime($_REQUEST['asofdate']);
		}
        $topic_support = Support::where('topic_num', '=', $topicnum)
        ->where('delegate_nick_name_id', 0)
        ->whereRaw("(start <= $as_of_time) and ((end = 0) or (end > $as_of_time))")
        ->orderBy('camp_num','ASC')->orderBy('support_order','ASC')
        ->select(['nick_name_id', 'delegate_nick_name_id', 'support_order', 'topic_num', 'camp_num'])
        ->get();
        
        if(count($topic_support) > 0){
           foreach($topic_support as $support){
                    if(array_key_exists($support->nick_name_id, $nick_name_wise_support)){
                            array_push($nick_name_wise_support[$support->nick_name_id],$support);
                    }else{
                        $nick_name_wise_support[$support->nick_name_id] = [];
                        array_push($nick_name_wise_support[$support->nick_name_id],$support);
                    }                   
           }
        }
        foreach($nick_name_wise_support as $nickNameId=>$support_camp){
            $multiSupport =  count($support_camp) > 1 ? 1 : 0;
           foreach($support_camp as $support){                
                $support_total = 0; 
                $nick_name_support_tree[$support->nick_name_id][$support->support_order][$support->camp_num]['score'] = 0;
                $camp_wise_score[$support->camp_num][$support->support_order][$support->nick_name_id]['score'] = 0;
                $supportPoint = Algorithm::{$algorithm}($support->nick_name_id,$support->topic_num,$support->camp_num);
                if($multiSupport){
                        $support_total = $support_total + round($supportPoint * 1 / (2 ** ($support->support_order)), 3);
                    }else{
                        $support_total = $support_total + $supportPoint;
                    }                    
                    $nick_name_support_tree[$support->nick_name_id][$support->support_order][$support->camp_num]['score'] = $support_total;
                    $camp_wise_score[$support->camp_num][$support->support_order][$support->nick_name_id]['score'] =  $support_total;
                                  
           }
        }
        if(count($nick_name_support_tree) > 0){
            foreach($nick_name_support_tree as $nickNameId=>$scoreData){
                ksort($scoreData);
                $index = 0;
                foreach($scoreData as $support_order=>$camp_score){
                    $index = $index +1;
                    $multiSupport =  count($camp_score) > 1 ? 1 : 0;
                   foreach($camp_score as $campNum=>$score){
                        if($support_order > 1 && $index == count($scoreData)  && $is_add_reminder_back_flag){
                            if(count(array_keys($nick_name_support_tree[$nickNameId][1])) > 0){
                            $campNumber = array_keys($nick_name_support_tree[$nickNameId][1])[0];
                            $nick_name_support_tree[$nickNameId][1][$campNumber]['score']=$nick_name_support_tree[$nickNameId][1][$campNumber]['score'] + $score['score'];
                            $camp_wise_score[$campNumber][1][$nickNameId]['score'] = $camp_wise_score[$campNumber][1][$nickNameId]['score'] + $score['score'];
                            $delegateTree = $this->delegateSupportTree($algorithm, $topicnum,$campNumber, $nickNameId, 1,$camp_wise_score[$campNumber][1][$nickNameId]['score'],$multiSupport ,[]);
                            $nick_name_support_tree[$nickNameId][1][$campNumber]['delegates'] = $delegateTree;
                        }
                    }
                    $delegateTree = $this->delegateSupportTree($algorithm, $topicnum,$campNum, $nickNameId, $support_order, $nick_name_support_tree[$nickNameId][$support_order][$campNum]['score'],$multiSupport,[]);
                    $nick_name_support_tree[$nickNameId][$support_order][$campNum]['delegates'] = $delegateTree;
                   }
                }
            }
        }
    
        return ['camp_wise_tree'=>$camp_wise_score,'nick_name_wise_tree'=>$nick_name_support_tree];
    }
    
    public function getDelegatesScore($tree){
        $score = 0;
        if(count($tree['delegates']) > 0){
            foreach($tree['delegates'] as $nick=>$delScore){
                $score = $score + $delScore['score'];
                if(count($delScore['delegates']) > 0){
                    $score = $score + $this->getDelegatesScore($delScore);
                }
            }
        }
        return $score;
    }

    public function getCamptSupportCount($algorithm, $topicnum, $campnum,$nick_name_id=null) {
        $score_tree = $this->getCampAndNickNameWiseSupportTree($algorithm, $topicnum);
        session(["score_tree_{$topicnum}_{$algorithm}"=>$score_tree]);
        if(session()->has("score_tree_{$topicnum}_{$algorithm}")){
            $score_tree = session("score_tree_{$topicnum}_{$algorithm}");
        }else{
            $score_tree = $this->getCampAndNickNameWiseSupportTree($algorithm, $topicnum);
            session(["score_tree_{$topicnum}_{$algorithm}"=>$score_tree]);
        }
        
         

         $support_total = 0;
         if(array_key_exists('camp_wise_tree',$score_tree) && count($score_tree['camp_wise_tree']) > 0 && array_key_exists($campnum,$score_tree['camp_wise_tree'])){
             if(count($score_tree['camp_wise_tree'][$campnum]) > 0){
                 foreach($score_tree['camp_wise_tree'][$campnum] as $order=>$tree_node){                                        
                     if(count($tree_node) > 0){
                         foreach($tree_node as $nick=>$score){
                            $delegate_arr = $score_tree['nick_name_wise_tree'][$nick][$order][$campnum];
                            $delegate_score = $this->getDelegatesScore($delegate_arr); 
                            $support_total =$support_total + $score['score'] + $delegate_score;
                         }
                     }
                 }    
             }
         }         
        return $support_total;
    }
// Commenting old logic code
    // public function getCamptSupportCount($algorithm, $topicnum, $campnum,$nick_name_id=null) {
    //     $supportCountTotal = 0;
       
    //     try {
    //         foreach (session("topic-support-nickname-$topicnum") as $supported) {
    //             if($nick_name_id !=null && $supported->nick_name_id == $nick_name_id ){
    //                 $nickNameSupports = session("topic-support-{$topicnum}")->filter(function ($item) use($nick_name_id) {
    //                     return $item->nick_name_id == $nick_name_id; /* Current camp support */
    //                 });
    //             }else{
    //                 $nickNameSupports = session("topic-support-{$topicnum}")->filter(function ($item) use($supported) {
    //                     return $item->nick_name_id == $supported->nick_name_id; /* Current camp support */
    //                 });
    //             }
                                
                
    //             $currentCampSupport = $nickNameSupports->filter(function ($item) use($campnum) {
    //                         return $item->camp_num == $campnum; /* Current camp support */
    //                     })->first();
                        
                       
	// 		   /*The canonizer value should be the same as their value supporting that camp. 
	// 			   1 if they only support one party, 
	// 			   0.5 for their first, if they support 2, 
	// 			   0.25 after and half, again, for each one after that. */
    //                 if($nick_name_id && $currentCampSupport && $supported->nick_name_id == $nick_name_id){
    //                     $supportPoint = Algorithm::{$algorithm}($supported->nick_name_id,$supported->topic_num,$supported->camp_num);
    //                     $multiSupport = false; //default;
    //                      if ($nickNameSupports->count() > 1) {
    //                         $multiSupport = true;
    //                         $supportCountTotal += round($supportPoint * 1 / (2 ** ($currentCampSupport->support_order)), 2);
    //                     } else if ($nickNameSupports->count() == 1) {
    //                          $supportCountTotal += $supportPoint;
    //                     }
    //                     $supportCountTotal += $this->getDeletegatedSupportCount($algorithm, $topicnum, $campnum, $supported->nick_name_id, $currentCampSupport->support_order, $multiSupport);
    //                 } else if ($currentCampSupport && $nick_name_id == null) {
    //                  $supportPoint = Algorithm::{$algorithm}($supported->nick_name_id,$supported->topic_num,$supported->camp_num);
    //                  $multiSupport = false; //default
    //                  if ($nickNameSupports->count() > 1) {
    //                     $multiSupport = true;
    //                     if($algorithm =='mind_experts'){
    //                         $supportCountTotal +=  $supportPoint;
    //                     }else{
    //                         $supportCountTotal +=  round($supportPoint * 1 / (2 ** ($currentCampSupport->support_order)), 2);
    //                     }
    //                 } else if ($nickNameSupports->count() == 1) {
    //                      $supportCountTotal += $supportPoint;
    //                 }
    //                 $supportCountTotal += $this->getDeletegatedSupportCount($algorithm, $topicnum, $campnum, $supported->nick_name_id, $currentCampSupport->support_order, $multiSupport);
    //             }
               
    //         } 
    //     } catch (\Exception $e) {
    //         echo "nickname-$topicnum" . $e->getMessage();
    //     }
    //     return $supportCountTotal;
    // }

    public function buildCampTree($traversedTreeArray, $currentCamp = null, $activeCamp = null, $activeCampDefault = false,$add_supporter = false, $arrowposition, $linkKey = 'link', $titleKey = 'title') {
        $html = '<ul class="childrenNode">';
		$action = Route::getCurrentRoute()->getActionMethod();
        //$onecamp =  self::getLiveCamp($this->topic_num, $activeCamp);
        
        if ($currentCamp == $activeCamp && $action != "index") { 
            $url_portion = self::getSeoBasedUrlPortion($this->topic_num,$currentCamp);
            $html = '<ul><li class="create-new-li"><span><a href="' . url('camp/create/'.$url_portion) . '">&lt;Start new supporting camp here&gt;</a></span></li>';
        }
        if (is_array($traversedTreeArray)) {
            foreach ($traversedTreeArray as $campnum => $array) {
                /* ticket 846 sunil */
                $filter = isset($_REQUEST['filter']) && is_numeric($_REQUEST['filter']) ? $_REQUEST['filter'] : 0.000;
				if(isset($_REQUEST['filter']) && !empty($_REQUEST['filter'])) {
									
					session()->forget('filter');
				}
			    if(session('filter')==="removed") {
					
				 $filter = 0.000;	
				} else if(isset($_SESSION['filterchange'])) {
					
				  $filter = $_SESSION['filterchange'];
				}
                if ($array['score'] < $filter && $campnum != $activeCamp) {
                    continue;
                }
                $childCount = is_array($array['children']) ? count($array['children']) : 0;
                $class = is_array($array['children']) && count($array['children']) > 0 ? 'parent' : '';
                $icon = ($childCount || ($campnum == $activeCamp)) ?  '<i class="fa '.$arrowposition.'"></i>' : '';
				
                $html .= "<li id='tree_" . $this->topic_num . "_" . $currentCamp . "_" . $campnum . "'>";
                //$selected = '';
                $selected = ($campnum == $activeCamp) && $activeCampDefault ? "color:#08b608; font-weight:bold" : "";
                if (($campnum == $activeCamp) && $activeCampDefault) {
                    session(['supportCountTotal' => $array['score']]);
                }
                $support_tree = '';
                if($add_supporter){
                  $support_tree=TopicSupport::topicSupportTree(session('defaultAlgo','blind_popularity'),$this->topic_num,$campnum,$add_supporter);
                }
                $support_tree_html = '';
                 if($support_tree !=''){
                    $support_tree_html .=  "<div class='supporter_list_tree'><ul><li class='supportLI' id='support_tree_" . $this->topic_num . "_" . $currentCamp . "_" . $campnum . "'>";
                    $support_tree_html .= '<span class="'.$class.'"><i class="supporter fa '.$arrowposition.'"></i></span>';
                    $support_tree_html.= '<ul>'.$support_tree.'</ul>';
                    $support_tree_html .= '</li></ul></div>';
                }
                $html .= '<span class="' . $class . '">' . $icon . '</span><div class="tp-title"><a style="' . $selected . '" href="' . $array[$linkKey] . '">' . $array[$titleKey] . '</a> <div class="badge">' . round($array['score'] ,2).'</div>'.$support_tree_html;
               
                $html .= '</div>';
                $html .= $this->buildCampTree($array['children'], $campnum, $activeCamp, $activeCampDefault,$add_supporter,$arrowposition, $linkKey, $titleKey);
                $html .= '</li>';
            }
        }
        $html .= '</ul>';
        return $html;
    }

    public static function sortTopicsBasedOnScore($topics){

        if(sizeof($topics) > 0){

                 foreach ($topics as $key => $value) {
                    $campData = self::where('topic_num',$value->topic_num)->where('camp_num',$value->camp_num)->first();
                    if( $campData){
                        $reducedTree = $campData ->getTopicScore(session('defaultAlgo', 'blind_popularity'), $activeAcamp = null, $supportCampCount = 0, $needSelected = 0);
                        $topics[$key]->score = $reducedTree[$value->camp_num]['score'];
                    }else{
                        $topics[$key]->score = 0;
                    }
                    
                }
              // $topics = $topics->sortBy('score',SORT_REGULAR, true);
                $topics->setCollection(collect(collect($topics->items())->sortByDesc('score'))->values());
                return $topics;
        }else{
            return $topics;
        }
    }

    public function traverseCampTree($algorithm, $topicnum, $parentcamp, $lastparent = null) {
        $key = $topicnum . '-' . $parentcamp . '-' . $lastparent;
       
        if (in_array($key, Camp::$traversetempArray)) {
            return;/** Skip repeated recursions* */
        }
        Camp::$traversetempArray[] = $key;
        $childs = $this->Childrens($topicnum, $parentcamp);
        
        $array = [];
        foreach ($childs as $key => $child) {
            //$childCount  = count($child->children($child->topic_num,$child->camp_num));
            $onecamp = self::getLiveCamp($child->topic_num,$child->camp_num,['nofilter'=>false]);
            $title = $onecamp->camp_name;//preg_replace('/[^A-Za-z0-9\-]/', '-', $onecamp->camp_name);
            $topic_id = $child->topic_num . "-" . $title;
            $array[$child->camp_num]['title'] = $title;
            $array[$child->camp_num]['review_title'] = $title;
			$queryString = (app('request')->getQueryString()) ? '?'.app('request')->getQueryString() : "";
            $array[$child->camp_num]['link'] = self::getTopicCampUrl($child->topic_num,$child->camp_num). $queryString .'#statement';
            $array[$child->camp_num]['review_link'] = self::getTopicCampUrl($child->topic_num,$child->camp_num). $queryString .'#statement';
            $array[$child->camp_num]['score'] = $this->getCamptSupportCount($algorithm, $child->topic_num, $child->camp_num);
           $children = $this->traverseCampTree($algorithm, $child->topic_num, $child->camp_num, $child->parent_camp_num);
           $array[$child->camp_num]['children'] = is_array($children) ? $children : [];
        }
        return $array;
        
    }

    public static function getSeoBasedUrlPortion($topic_num,$camp_num){
        $topic = \App\Model\Topic::getLiveTopic($topic_num,['nofilter'=>true]);
        $camp = self::getLiveCamp($topic_num,$camp_num,['nofilter'=>true]);
        $topic_name = '';
        $camp_name = '';
        if($topic && isset($topic->topic_name)){
                $topic_name = ($topic->topic_name !='') ? $topic->topic_name: $topic->title;
        }
        if($camp && isset($camp->camp_name)){
              $camp_name = $camp->camp_name;
            }
        $topic_id_name = $topic_num;
        $camp_num_name = $camp_num;
        if($topic_name!=''){
            $topic_id_name = $topic_num . "-" . preg_replace('/[^A-Za-z0-9\-]/', '-',$topic_name);
        }
        if($camp_name!=''){
                $camp_num_name = $camp_num."-".preg_replace('/[^A-Za-z0-9\-]/', '-', $camp->camp_name);
        }
        
        return $topic_id_name . '/' . $camp_num_name;
    }

    public static function getTopicCampUrl($topic_num,$camp_num){
        $urlPortion = self::getSeoBasedUrlPortion($topic_num,$camp_num);         
        return url('topic/' .$urlPortion);
    }

    public function getTopicScore($algorithm, $activeAcamp = null, $supportCampCount = 0, $needSelected = 0){
        if (!session("topic-support-nickname-{$this->topic_num}")) { 
        $this->campTreeData(session('defaultAlgo', 'blind_popularity'), $activeAcamp = null, $supportCampCount = 0, $needSelected = 0);
        } 
        $topic_name = (isset($this->topic) && isset($this->topic->topic_name)) ? $this->topic->topic_name: '';
        $title = preg_replace('/[^A-Za-z0-9\-]/', '-', $topic_name);
        $topic_id = $this->topic_num . "-" . $title;
       
        $treeNew = [];
        $treeNew[$this->camp_num]['score'] = $this->getCamptSupportCount($algorithm, $this->topic_num, $this->camp_num);
        
        $treeNew[$this->camp_num]['children'] = $this->traverseCampTree($algorithm, $this->topic_num, $this->camp_num);
        
        return $reducedTree = TopicSupport::sumTranversedArraySupportCount($treeNew);
    }

    public function campTreeData($algorithm, $activeAcamp = null, $supportCampCount = 0, $needSelected = 0){
        $as_of_time = time();
        if ((isset($_REQUEST['asof']) && $_REQUEST['asof'] == 'bydate')) {
            $as_of_time = strtotime($_REQUEST['asofdate']);
        }else if((session()->has('asof') && session('asof') == 'bydate' && !isset($_REQUEST['asof']))){
            $as_of_time = strtotime(session('asofdateDefault'));
        } 
        if (!session("topic-support-nickname-{$this->topic_num}")) { 
            session(["topic-support-nickname-{$this->topic_num}" => Support::where('topic_num', '=', $this->topic_num)
                        ->where('delegate_nick_name_id', 0)
                        ->whereRaw("(start <= $as_of_time) and ((end = 0) or (end > $as_of_time))")
                        ->orderBy('start', 'DESC')
                        ->groupBy('nick_name_id')
                        ->select(['nick_name_id', 'delegate_nick_name_id', 'support_order', 'topic_num', 'camp_num'])
                        ->get()]);
        }


        if (!session("topic-support-{$this->topic_num}")) {
            session(["topic-support-{$this->topic_num}" => Support::where('topic_num', '=', $this->topic_num)
                        ->whereRaw("(start <= $as_of_time) and ((end = 0) or (end > $as_of_time))")
                        ->orderBy('start', 'DESC')
                        ->select(['support_order', 'camp_num', 'nick_name_id', 'delegate_nick_name_id', 'topic_num'])
                        ->get()]);
        }

        if ((!isset($_REQUEST['asof']) && !(session()->has('asofDefault'))) || (isset($_REQUEST['asof']) && $_REQUEST['asof'] == "default") || (session()->has('asofDefault') && session('asofDefault') == 'default' && !isset($_REQUEST['asof']))) {
                session(["topic-child-{$this->topic_num}" => self::where('topic_num', '=', $this->topic_num)
                        ->where('camp_name', '!=', 'Agreement')
                        ->where('objector_nick_id', '=', NULL)
                        ->whereRaw('go_live_time in (select max(go_live_time) from camp where topic_num=' . $this->topic_num . ' and objector_nick_id is null and go_live_time < "' . time() . '" group by camp_num)')
                        ->where('go_live_time', '<=', time())
                        ->groupBy('camp_num')
                        ->orderBy('submit_time', 'desc')
                        ->get()]);
        } else {
            
            if ((isset($_REQUEST['asof']) && $_REQUEST['asof'] == "review") || (session('asofDefault')=="review" && !isset($_REQUEST['asof']))) {
            
            
                session(["topic-child-{$this->topic_num}" => self::where('topic_num', '=', $this->topic_num)
                            ->where('camp_name', '!=', 'Agreement')
                            ->where('objector_nick_id', '=', NULL)
                            ->whereRaw('go_live_time in (select max(go_live_time) from camp where topic_num=' . $this->topic_num . ' and objector_nick_id is null group by camp_num)')
                            ->orderBy('submit_time', 'desc')
                            ->groupBy('camp_num')
                            ->get()]);
            } else if ((isset($_REQUEST['asof']) && $_REQUEST['asof'] == "bydate") || (session()->has('asofDefault') && session('asofDefault') == 'bydate' && !isset($_REQUEST['asof']))) {
                if(isset($_REQUEST['asof']) && $_REQUEST['asof'] == "bydate"){
                          $asofdate = strtotime(date('Y-m-d H:i:s', strtotime($_REQUEST['asofdate'])));
                }else if(session()->has('asofdateDefault') && session('asofdateDefault')!='' && !isset($_REQUEST['asof'])){
                    $asofdate = strtotime(session('asofdateDefault'));
                }
               
                session(["topic-child-{$this->topic_num}" => self::where('topic_num', '=', $this->topic_num)
                            ->where('camp_name', '!=', 'Agreement')
                            ->where('objector_nick_id', '=', NULL)
                            ->whereRaw('go_live_time in (select max(go_live_time) from camp where topic_num=' . $this->topic_num . ' and objector_nick_id is null and go_live_time <= '.$asofdate.' group by camp_num)')
                            ->orderBy('submit_time', 'DESC')
                            ->groupBy('camp_num')
                            ->get()]);
                            
            }
        }

    }
    public function campTree($algorithm,$nick_name_id=null, $supportCampCount = 0, $needSelected = 0, $fetchTopicHistory = 0) {
        
        $as_of_time = time();
        Camp::$traversetempArray = []; 
        if ((isset($_REQUEST['asof']) && $_REQUEST['asof'] == 'bydate')) {
            $as_of_time = strtotime($_REQUEST['asofdate']);
        }else if((session()->has('asof') && session('asof') == 'bydate' && !isset($_REQUEST['asof']))){
            $as_of_time = strtotime(session('asofdateDefault'));
        }
        
       
        if (!session("topic-support-nickname-{$this->topic_num}")) { 
            session(["topic-support-nickname-{$this->topic_num}" => Support::where('topic_num', '=', $this->topic_num)
                        ->where('delegate_nick_name_id', 0)
                        ->whereRaw("(start <= $as_of_time) and ((end = 0) or (end > $as_of_time))")
                        ->orderBy('start', 'DESC')
                        ->groupBy('nick_name_id')
                        ->select(['nick_name_id', 'delegate_nick_name_id', 'support_order', 'topic_num', 'camp_num'])
                        ->get()]);
        }

        if (!session("topic-support-{$this->topic_num}")) {
            session(["topic-support-{$this->topic_num}" => Support::where('topic_num', '=', $this->topic_num)
                        ->whereRaw("(start <= $as_of_time) and ((end = 0) or (end > $as_of_time))")
                        ->orderBy('start', 'DESC')
                        ->select(['support_order', 'camp_num', 'nick_name_id', 'delegate_nick_name_id', 'topic_num'])
                        ->get()]);
        }
        
        if ((!isset($_REQUEST['asof']) && !(session()->has('asofDefault'))) || (isset($_REQUEST['asof']) && $_REQUEST['asof'] == "default") || (session()->has('asofDefault') && session('asofDefault') == 'default' && !isset($_REQUEST['asof']))) {
                session(["topic-child-{$this->topic_num}" => self::where('topic_num', '=', $this->topic_num)
                        ->where('camp_name', '!=', 'Agreement')
                        ->where('objector_nick_id', '=', NULL)
                        ->whereRaw('go_live_time in (select max(go_live_time) from camp where topic_num=' . $this->topic_num . ' and objector_nick_id is null and go_live_time < "' . time() . '" group by camp_num)')
                        ->where('go_live_time', '<=', time())
                        ->groupBy('camp_num')
                        ->orderBy('submit_time', 'desc')
                        ->get()]);
        } else {
            
            if ((isset($_REQUEST['asof']) && $_REQUEST['asof'] == "review") || (session('asofDefault')=="review" && !isset($_REQUEST['asof']))) {
                session(["topic-child-{$this->topic_num}" => self::where('topic_num', '=', $this->topic_num)
                            ->where('camp_name', '!=', 'Agreement')
							->where('objector_nick_id', '=', NULL)
                            ->whereRaw('go_live_time in (select max(go_live_time) from camp where topic_num=' . $this->topic_num . ' and objector_nick_id is null group by camp_num)')
                            ->orderBy('submit_time', 'desc')
                            ->groupBy('camp_num')
                            ->get()]);

            } else if ((isset($_REQUEST['asof']) && $_REQUEST['asof'] == "bydate") || (session()->has('asofDefault') && session('asofDefault') == 'bydate' && !isset($_REQUEST['asof']))) {
                if(isset($_REQUEST['asof']) && $_REQUEST['asof'] == "bydate"){
                          $asofdate = strtotime(date('Y-m-d H:i:s', strtotime($_REQUEST['asofdate'])));
                }else if(session()->has('asofdateDefault') && session('asofdateDefault')!='' && !isset($_REQUEST['asof'])){
                    $asofdate = strtotime(session('asofdateDefault'));
                }
               
                session(["topic-child-{$this->topic_num}" => self::where('topic_num', '=', $this->topic_num)
                            ->where('camp_name', '!=', 'Agreement')
                            ->where('objector_nick_id', '=', NULL)
                            ->whereRaw('go_live_time in (select max(go_live_time) from camp where topic_num=' . $this->topic_num . ' and objector_nick_id is null and go_live_time <= '.$asofdate.' group by camp_num)')
                            ->orderBy('submit_time', 'DESC')
                            ->groupBy('camp_num')
                            ->get()]);
							
            }
        }

        $topic = Topic::getLiveTopic($this->topic->topic_num,['nofilter'=>false], $fetchTopicHistory);

        $topic_name = (isset($topic) && isset($topic->topic_name)) ? $topic->topic_name: '';
        $title = preg_replace('/[^A-Za-z0-9\-]/', '-', $topic_name);        
        $topic_id = $this->topic_num . "-" . $title;
        $tree = [];
        $tree[$this->camp_num]['title'] = $topic_name;
        $tree[$this->camp_num]['review_title'] = $topic_name;
        $tree[$this->camp_num]['link'] = self::getTopicCampUrl($this->topic_num,$this->camp_num);//  url('topic/' . $topic_id . '/' . $this->camp_num.'#statement');
        $tree[$this->camp_num]['review_link'] = self::getTopicCampUrl($this->topic_num,$this->camp_num);
        $tree[$this->camp_num]['score'] =  $this->getCamptSupportCount($algorithm, $this->topic_num, $this->camp_num,$nick_name_id);
       
        $tree[$this->camp_num]['children'] = $this->traverseCampTree($algorithm, $this->topic_num, $this->camp_num);
               
        return $reducedTree = TopicSupport::sumTranversedArraySupportCount($tree);
    }

    public function campTreeHtml($activeCamp = null, $activeCampDefault = false,$add_supporter = false, $arrowposition ='fa-arrow-down', $topic = null, $fetchTopicHistory) {
        /**  
         * Added by Ali Ahmad 
        * Jira Ticket CS-17
        */

        $titleKey = 'title';
        $linkKey = 'link';

        $cronDate = config('app.CS_CRON_DATE');
        $cronDate =  isset($cronDate) ? strtotime($cronDate) : strtotime(date('Y-m-d'));
  
        $asOf = 'default';

        if((isset($_REQUEST['asof']) && ($_REQUEST['asof'] == "review" || $_REQUEST['asof'] == "bydate"))){
            $asOf = $_REQUEST['asof'];
        }
        else if ((session('asofDefault')== "review" || session('asofDefault')== "bydate" ) && !isset($_REQUEST['asof'])) {
            $asOf = session('asofDefault');
        }
  
          $asOfDefaultDate = date('Y-m-d');
  
        if(isset($_REQUEST['asof']) && $_REQUEST['asof'] == "bydate"){
            $asOfDefaultDate = date('Y-m-d', strtotime($_REQUEST['asofdate']));
        } else if(($asOf == 'bydate') && session('asofdateDefault')){
            $asOfDefaultDate =  session('asofdateDefault');
        }
  
        $asOfDefaultDate = strtotime($asOfDefaultDate);
  
        $selectedAlgo = 'blind_popularity';
        if(session('defaultAlgo')) {
            $selectedAlgo = session('defaultAlgo');
        }

        if($asOf == 'review'){
            $titleKey = 'review_title';
            $linkKey = 'review_link';
        }

        $asOfDefaultDate = time();
        $checkOfDefaultToday = time();

        if(isset($_REQUEST['asof']) && $_REQUEST['asof'] == "bydate"){
            $asOfDefaultDate = strtotime(date('Y-m-d H:i:s', strtotime($_REQUEST['asofdate'])));
            $checkOfDefaultDate = $asOfDefaultDate;
        } else if(($asOf == 'bydate') && session('asofdateDefault')){
            $asOfDefaultDate =  strtotime(session('asofdateDefault'));
            $checkOfDefaultDate = $asOfDefaultDate;
        }

        //check if bydate is greater than current date
        if($checkOfDefaultDate > $checkOfDefaultToday){
            // $asOfDefaultDate = time();  ticket 1219 Muhammad Ahmad
        }

        $requestBody = [
            'topic_num' => $topic->topic_num,
            'algorithm' => $selectedAlgo,
            'asofdate'  => $asOfDefaultDate,
            'asOf'      => $asOf,
            'update_all' => 0,
            'fetch_topic_history' => $fetchTopicHistory
        ];

        $appURL = config('app.CS_APP_URL');
        $endpointCSGETTree =   config('app.CS_GET_TREE');
        $endpoint = $appURL."/".$endpointCSGETTree;
        $headers = array('Content-Type:multipart/form-data');

        $reducedTree = Util::execute('POST', $endpoint, $headers, $requestBody);

        $data = [];//json_decode($reducedTree, true);
        
        if(count($data['data']) && $data['code'] == 200 ){
            $reducedTree = $data['data'][0];
             // calling this to fill data in sessions as on main page data is loading from mongo so sessions remian blank
            if (!session("topic-support-nickname-{$this->topic_num}")) { 
                $this->campTreeData(session('defaultAlgo', 'blind_popularity'), $activeAcamp = null, $supportCampCount = 0, $needSelected = 0);
            } 
            // calling this to fill data in sessions as on main page data is loading from mongo so sessions remian blank
        
        } else {
            $reducedTree = $this->campTree(session('defaultAlgo', 'blind_popularity'), $activeAcamp = null, $supportCampCount = 0, $needSelected = 0, $fetchTopicHistory);
        }
        
        /* End of CS-17 Jira ticket */
       
        /* ticket 846 sunil */
        $filter = isset($_REQUEST['filter']) && is_numeric($_REQUEST['filter']) ? $_REQUEST['filter'] : 0.000;
        if(session('filter')==="removed") {
            $filter = 0.000;	
        } else if(isset($_SESSION['filterchange'])) {
            $filter = $_SESSION['filterchange'];
        }
		
        if ($reducedTree[$this->camp_num]['score'] < $filter) {
            $val = session('topic_on_page');
            session(['topic_on_page' => $val+1]);
            return;
        }

        $selected = ($this->camp_num == $activeCamp) && $activeCampDefault ? "color:#08b608; font-weight:bold" : "";
        if (($this->camp_num == $activeCamp)) {
            session(['supportCountTotal' => $reducedTree[$this->camp_num]['score']]);
        }
        $support_tree = '';
        if($add_supporter){
          $support_tree=TopicSupport::topicSupportTree(session('defaultAlgo','blind_popularity'),$this->topic_num,$this->camp_num,$add_supporter);
        }
        
        $support_tree_html = '';
         if($support_tree !=''){
            $support_tree_html .=  "<div class='supporter_list_tree'><ul><li class='supportLI' id='support_tree_" . $this->topic_num . "_" . $activeCamp . "_" . $this->camp_num . "'>";
            $support_tree_html .= '<span class="'.$class.'"><i class="supporter fa '.$arrowposition.'"></i></span>';
            $support_tree_html.= '<ul>'.$support_tree.'</ul>';
            $support_tree_html .= '</li></ul></div>';
        }
        
        $html = "<li id='tree_" . $this->topic_num . "_" . $activeCamp . "_" . $this->camp_num . "'>";
        $parentClass = is_array($reducedTree[$this->camp_num]['children']) && count($reducedTree[$this->camp_num]['children']) > 0 ? 'parent' : '';
        $icon = is_array($reducedTree[$this->camp_num]['children']) && count($reducedTree[$this->camp_num]['children']) > 0 ? '<i class="fa '.$arrowposition.'"></i>' : '';
        if(count($reducedTree[$this->camp_num]['children']) == 0 )
		$icon = '<i class="fa '.$arrowposition.'"></i>';
	    
        $action = Route::getCurrentRoute()->getActionMethod();

        if($action =="index" || $action =="loadtopic")		
 	      $icon = '<i class="fa '.$arrowposition.'"></i>';
        
		$html .= '<span class="' . $parentClass . '">'. $icon.' </span>';
        $html .= '<div class="tp-title"><a style="' . $selected . '" href="' . $reducedTree[$this->camp_num][$linkKey] . '">' . $reducedTree[$this->camp_num][$titleKey] . '</a><div class="badge">' .round($reducedTree[$this->camp_num]['score'], 2) . '</div>'.$support_tree_html.'</div>';         
        $html .= $this->buildCampTree($reducedTree[$this->camp_num]['children'], $this->camp_num, $activeCamp, $activeCampDefault,$add_supporter,$arrowposition, $linkKey, $titleKey);
        $html .= "</li>";
        return $html;
    }

    public static function getCampSubscription($topicnum,$campnum,$userid=null){
        $returnArr = array('flag'=>0,'camp'=>[],'camp_subscription_data'=>[]);
        if($userid){
               $camp_subscription = \App\Model\CampSubscription::where('user_id','=',$userid)->where('camp_num','=',$campnum)->where('topic_num','=',$topicnum)->where('subscription_start','<=',strtotime(date('Y-m-d H:i:s')))->where('subscription_end','=',null)->orWhere('subscription_end','>=',strtotime(date('Y-m-d H:i:s')))->get();
                $flag = sizeof($camp_subscription) > 0  || 0;
                 if(!$flag){
                    $onecamp = self::getLiveCamp($topicnum, $campnum,['nofilter'=>true]);
                    $childCampData = [];
                    if($onecamp){
                         $childCampData = $onecamp->campChild($topicnum,$campnum);
                    }
                   
                    $child_camps = [];
                    if(count($childCampData) > 0){
                        foreach($childCampData as $key=>$child){
                            $child_camps[$key] = $child->camp_num;
                        }
                    }

                    if(count($child_camps) > 0){
                        $camp_subs_child = \App\Model\CampSubscription::where('user_id','=',$userid)->whereIn('camp_num',$child_camps)->where('topic_num','=',$topicnum)->where('subscription_start','<=',strtotime(date('Y-m-d H:i:s')))->where('subscription_end','=',null)->orWhere('subscription_end','>=',strtotime(date('Y-m-d H:i:s')))->get();
                        $flag = ($camp_subs_child && sizeof($camp_subs_child) > 0 );
                        if($flag){
                            $flag =2;
                        }
                      foreach($child_camps as $camp){
                        $camp_subscription = \App\Model\CampSubscription::where('user_id','=',$userid)->where('camp_num','=',$camp)->where('topic_num','=',$topicnum)->where('subscription_start','<=',strtotime(date('Y-m-d H:i:s')))->where('subscription_end','=',null)->orWhere('subscription_end','>=',strtotime(date('Y-m-d H:i:s')))->get();
                        if(sizeof($camp_subscription) > 0){
                            $onecamp = self::getLiveCamp($topicnum, $camp,['nofilter'=>true]);
                            $returnArr = array('flag'=>$flag,'camp'=>$onecamp,'camp_subscription_data'=>$camp_subscription);
                            break;
                        }
                      }
                    }
                  }else{
                    $onecamp = self::getLiveCamp($topicnum, $campnum,['nofilter'=>true]);
                    $returnArr = array('flag'=>$flag,'camp'=>$onecamp,'camp_subscription_data'=>$camp_subscription);
                  }
                return $returnArr;
        }else{
            return $returnArr;
        }
    }

    public static function getTopicSubscription($topicnum,$campnum=0,$userid=null){
        $returnArr = array('flag'=>0,'id'=>0);
        if($userid){
               $camp_subscription = \App\Model\CampSubscription::where('user_id','=',$userid)->where('camp_num','=',$campnum)->where('topic_num','=',$topicnum)->where('subscription_start','<=',strtotime(date('Y-m-d H:i:s')))->where('subscription_end','=',null)->orWhere('subscription_end','>=',strtotime(date('Y-m-d H:i:s')))->get();
               $flag = sizeof($camp_subscription) > 0  || 0;
                 if(!$flag){
                    $returnArr = array('flag'=>0,'id'=>$camp_subscription[0]->id);
                  }else{
                    $returnArr = array('flag'=>1,'id'=>$camp_subscription[0]->id);
                  }
                return $returnArr;
        }else{
            return $returnArr;
        }
    }
    
    public static function getSubscriptionList($userid,$topic_num,$camp_num=1){
        $list = [];
        $onecamp = self::getLiveCamp($topic_num, $camp_num);
        self::clearChildCampArray();
        $childCamps = array_unique(self::getAllChildCamps($onecamp));
        // #1291 notify parent camps subscribers
        $parentCamps = array_unique(self::getAllParent($onecamp));
        $camps = array_unique(array_merge($childCamps, $parentCamps));
        $subscriptions = \App\Model\CampSubscription::where('user_id','=',$userid)->where('topic_num','=',$topic_num)->where('subscription_start','<=',strtotime(date('Y-m-d H:i:s')))->whereNull('subscription_end')->get();
        if(isset($subscriptions ) && count($subscriptions ) > 0){
            $i=1;
            foreach($subscriptions as $subs){
                if($camp_num!=1){
                    if(!in_array($subs->camp_num, $camps) && $subs->camp_num != 0){
                        continue;
                    }
                }
                $topic = self::getLiveCamp($subs->topic_num,$subs->camp_num,['nofilter'=>true]);
                $title = preg_replace('/[^A-Za-z0-9\-]/', '-', ($topic->title != '') ? $topic->title : $topic->camp_name);
                $topic_id =$subs->topic_num . "-" . $title;
                $link = self::getTopicCampUrl($topic_num,$subs->camp_num); //$camp_num change to $subs->camp_num for #934 
                //url('topic/' . $topic_id . '/' . $subs->camp_num);
                if($subs->camp_num == 0){
                    $topic = \App\Model\Topic::getLiveTopic($subs->topic_num,['nofilter'=>true]);
                    $link = self::getTopicCampUrl($topic_num,1,time());
                    if(!empty($topic)){
                        $list[]= '<a href="'.$link.'">'.$topic->topic_name.'</a>';
                    }
                }else{
                    $list[]= '<a href="'.$link.'">'.$topic->camp_name.'</a>';
                }
            }
        }
        return $list;
    }

    public static function getCampSubscribers($topic_num,$camp_num=1){
        $users_data = [];
        $users = \App\Model\CampSubscription::select('user_id')->where('topic_num','=',$topic_num)
                ->whereIn('camp_num',[0,$camp_num])
                ->whereNull('subscription_end')
                ->get();
        if(count($users)){
            foreach($users as $user){
                array_push($users_data, $user->user_id);
            }
        }
        if($camp_num){
            $onecamp = self::getLiveCamp($topic_num, $camp_num,['nofilter'=>true]);
        }else{
            $onecamp = self::getLiveCampFromTopic($topic_num,['nofilter'=>true]);
        }
        $childCampData = [];
        $parent_camps = [];
        if(isset($onecamp) && isset($onecamp->camp_name)){
            if($camp_num){
                $childCampData = $onecamp->campChild($topic_num,$camp_num);   
            }else{
                $childCampData = self::campChildFromTopic($topic_num);
            }
            // #1291 notify parent camps subscribers
            $parent_camps = self::getAllParent($onecamp);
        }
        $child_camps = [];
        if(count($childCampData) > 0){
            foreach($childCampData as $key=>$child){
                $child_camps[$key] = $child->camp_num;
            }
        }
        if(count($child_camps) > 0 || count($parent_camps) > 0){
            // #1291 notify parent camps subscribers
            $camps = array_unique(array_merge($child_camps, $parent_camps));
            $usersData = \App\Model\CampSubscription::select('user_id')->where('topic_num','=',$topic_num)
                ->whereIn('camp_num',$camps)
                ->where('subscription_end','=',null)
                ->get();

           if(count($usersData)){
            foreach($usersData as $user){
                array_push($users_data, $user->user_id);
                }
            }
        }
        return  array_unique($users_data);

    }

    public static function clearChildCampArray(){
        self::$chilcampArray=[];
    }

    public static function getObjectionOptionsLink(){
        $helpLink = null;
        $disagreementCamp = Camp::where('camp_name', 'Dealing with Disagreement')->first();
        if(!empty($disagreementCamp)) {
            $helpLink = 'topic/'.$disagreementCamp->topic_num.'-'.$disagreementCamp->camp_name.'/'.$disagreementCamp->camp_num;
        }
        return $helpLink;
    }
    
    public static function getAllLiveCampsInTopic($topicnum){ 
        return self::where('topic_num', '=', $topicnum)
                        ->where('camp_name', '!=', 'Agreement')
                        ->where('objector_nick_id', '=', NULL)
                        ->whereRaw('go_live_time in (select max(go_live_time) from camp where topic_num=' . $topicnum . ' and objector_nick_id is null and go_live_time < "' . time() . '" group by camp_num)')
                        ->where('go_live_time', '<=', time())
                        ->groupBy('camp_num')
                        ->orderBy('submit_time', 'desc')
                        ->get();
    }

    public static function getAllNonLiveCampsInTopic($topicnum){ 
        return self::where('topic_num', '=', $topicnum)
                        ->where('camp_name', '!=', 'Agreement')
                        ->where('objector_nick_id', '=', NULL)
                        ->where('go_live_time',">",time())
                        ->groupBy('camp_num')
                        ->orderBy('submit_time', 'desc')
                        ->get();
    }

    public static function getCampNameByTopicIdCampId($topic_num,$campnum,$as_of_time){
        $parentCampName ="";
        $campDetails=Camp::where('topic_num', $topic_num)->where('camp_num', '=', $campnum)->where('objector_nick_id', '=', NULL)->where('go_live_time', '<=', $as_of_time)->orderBy('submit_time', 'DESC')->first();
        if(!empty($campDetails)) {
            $parentCampName = $campDetails->camp_name;
        }
        return $parentCampName;
    }

    /**
     * By Reena Nalwa Talentelgia
     * Return Users subscribing that topic directly or through childs
     */
    public static function getSubscribersInTopic($topicNum){
        $users_data = [];
        $users = \App\Model\CampSubscription::select('*')->where('topic_num','=',$topicNum)
                ->get();
        if(count($users)){
            foreach($users as $user){
                array_push($users_data, $user->user_id);
            }
        }        
        return $users_data;
    }


    public static function getLiveCampFromTopic($topicnum, $filter = array()) {
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

    public static function campChildFromTopic($topicnum) {

        $childsData = Camp::where('topic_num', '=', $topicnum)
                        ->get()->unique('camp_num');
        return $childsData;
    }

    /**
     * Function Name : getAllChildrenCampsNum
     * Coder Name : Sunil Singh 
     * $camp : have Live Camp record information
     * $campChildren : return array of children camp num 
     */

    public static function getAllChildrenCampsNum($camp) {
        if ($camp) {
            $key = $camp->topic_num . '-' . $camp->camp_num . '-' . $camp->parent_camp_num;
            $key1 = $camp->topic_num . '-' . $camp->parent_camp_num . '-' . $camp->camp_num;
            if (in_array($key, Camp::$chilcampArray) || in_array($key1, Camp::$chilcampArray)) {
                return [];/** Skip repeated recursions* */
             }
            Camp::$chilcampArray[] = $key;
            Camp::$chilcampArray[] = $key1;
            Camp::$campChildren[] = $camp->camp_num;
            //adding go_live_time condition Sunil Talentelgia
            $childCamps = Camp::where('topic_num', $camp->topic_num)->where('parent_camp_num', $camp->camp_num)->where('go_live_time', '<=', time())->groupBy('camp_num')->latest('submit_time')->get();
            foreach ($childCamps as $child) {
                //adding go_live_time condition Sunil Talentelgia
                $latestParent = Camp::where('topic_num', $child->topic_num)
                ->where('camp_num', $child->camp_num)->where('go_live_time', '<=', time())->latest('submit_time')->first();
                if($latestParent->parent_camp_num == $camp->camp_num ){ 
                    Camp::$campChildren = array_merge(Camp::$campChildren, self::getAllChildrenCampsNum($child)); 
                }
                
            }
         }
 
        return Camp::$campChildren;
    }
}
