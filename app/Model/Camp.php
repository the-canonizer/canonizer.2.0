<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Nickname;
use DB;
use App\Model\Algorithm;
use App\Model\TopicSupport;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Collection;

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

    public function scopeCampNameWithAncestors($query, $camp, $campname = '',$title = '') {
        $as_of_time = time();
        if (isset($_REQUEST['asof']) && $_REQUEST['asof'] == 'bydate') {
            $as_of_time = strtotime($_REQUEST['asofdate']);
        }
        if (!empty($camp)) {
            if ($campname != '') {
                if($title==''){
                    $titleAppend      = preg_replace('/[^A-Za-z0-9\-]/', '-', $camp->camp_name);
                }else{
                    if($camp->parent_camp_num){
                            $titleAppend      = preg_replace('/[^A-Za-z0-9\-]/', '-', $camp->camp_name);
                    }else {
                       $titleAppend      = preg_replace('/[^A-Za-z0-9\-]/', '-', $title); 
                    }
                     
                }				
                //$url = url('topic/' . $camp->topic_num . '-'.$titleAppend.'/' . $camp->camp_num);
                $url = self::getTopicCampUrl($camp->topic_num,$camp->camp_num);
                $campname = "<a href='" . $url . "'>" . $camp->camp_name . '</a> / ' . $campname;
            } else {
                if($title ==''){
                        $titleAppend      = preg_replace('/[^A-Za-z0-9\-]/', '-', $camp->camp_name);
                }else{
                    if($camp->parent_camp_num){
                            $titleAppend      = preg_replace('/[^A-Za-z0-9\-]/', '-', $camp->camp_name);
                    }else {
                       $titleAppend      = preg_replace('/[^A-Za-z0-9\-]/', '-', $title); 
                    }
                }
				//$url = url('topic/' . $camp->topic_num .'-'.$titleAppend. '/' . $camp->camp_num);
                $url = self::getTopicCampUrl($camp->topic_num,$camp->camp_num);
                $campname = "<a href='" . $url . "'>" . $camp->camp_name . '</a>';
            }

            if (isset($camp) && $camp->parent_camp_num) {
                
                $pcamp = Camp::where('topic_num', $camp->topic_num)
                    ->where('camp_num', $camp->parent_camp_num)
                    // ->where('camp_name', '!=', 'Agreement')  
                    ->where('objector_nick_id', '=', NULL)
                    //->whereRaw('go_live_time in (select max(go_live_time) from camp where topic_num=' . $camp->topic_num . ' and objector_nick_id is null group by camp_num)')
                    ->where('go_live_time', '<=', $as_of_time)
                    ->orderBy('submit_time', 'DESC')->first();

                return self::campNameWithAncestors($pcamp, $campname,$title);
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
                ->where('camp.go_live_time', '<=', $as_of_time)
                ->whereRaw('topic.go_live_time in (select max(topic.go_live_time) from topic where topic.topic_num=topic.topic_num and topic.objector_nick_id is null and topic.go_live_time <=' . $as_of_time . ' group by topic.topic_num)')
                ->where('topic.topic_name', '<>', "");
        if (isset($_REQUEST['namespace']) && (!empty($_REQUEST['namespace']) || $_REQUEST['namespace'] != 0)) {
            $query->where('namespace_id', $_REQUEST['namespace']);
        }else if( null !== session('defaultNamespaceId') && !empty(session('defaultNamespaceId'))){
            $query->whereIn('namespace_id',explode(',', session('defaultNamespaceId', 1)));
        }
        if(isset($_REQUEST['my']) && $_REQUEST['my'] == $_REQUEST['namespace']){
            $query->whereIn('topic.submitter_nick_id', $nicknameIds);
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
                return self::select('topic.topic_name', 'topic.namespace_id','camp.*', 'namespace.name as namespace_name','namespace.name')
                                ->join('topic', 'topic.topic_num', '=', 'camp.topic_num')
                                ->join('namespace', 'topic.namespace_id', '=', 'namespace.id')
                                ->where('camp.topic_num', $topicnum)->where('camp_name', '=', 'Agreement')
                                ->where('camp.objector_nick_id', '=', NULL)
                                ->where('topic.objector_nick_id', '=', NULL)
                                ->latest('topic.submit_time')->first();

            } else if (isset($filter['asof']) && $filter['asof'] == "bydate" || (session()->has('asofDefault') && session('asofDefault') == 'bydate' && !isset($filter['asof']))) {
                if(isset($filter['asof']) && $filter['asof'] == "bydate"){
                    $asofdate = strtotime(date('Y-m-d H:i:s', strtotime($filter['asofdate'])));   
                    
                }else if(session('asofDefault') == 'bydate' && !isset($filter['asof'])){
                    $asofdate = strtotime(session('asofdateDefault'));
                }
                if(isset($filter['nofilter']) && $filter['nofilter']){
                    $asofdate  = time();
                }
                return self::select('topic.topic_name','topic.namespace_id', 'camp.*', 'namespace.name as namespace_name','namespace.name')
                                ->join('topic', 'topic.topic_num', '=', 'camp.topic_num')
                                ->join('namespace', 'topic.namespace_id', '=', 'namespace.id')
                                ->where('camp.topic_num', $topicnum)->where('camp_name', '=', 'Agreement')
                                ->where('camp.objector_nick_id', '=', NULL)
                                ->where('topic.objector_nick_id', '=', NULL)
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
                            ->whereRaw('topic.go_live_time in (select max(topic.go_live_time) from topic where topic.topic_num=topic.topic_num and topic.objector_nick_id is null and topic.go_live_time <=' . $as_of_time . ' group by topic.topic_num)')
                            ->latest('support')->groupBy('topic.topic_num')->orderBy('topic.topic_name', 'DESC')->paginate($limit,['camp.topic_num']);

        } else {
            if ((isset($filter['asof']) && $filter['asof'] == "review") || (session('asofDefault')=="review" && !isset($filter['asof']))) {
                $returnTopics =  DB::table('camp')->select(DB::raw('(select count(topic_support.id) from topic_support where topic_support.topic_num=camp.topic_num) as support, camp.*'))
                            ->join('topic', 'topic.topic_num', '=', 'camp.topic_num')
                            ->where('camp_name', '=', 'Agreement')
                            ->where('topic.objector_nick_id', '=', NULL)
                            ->whereIn('namespace_id', explode(',', session('defaultNamespaceId', 1)))
                            ->whereRaw('topic.go_live_time in (select max(topic.go_live_time) from topic where topic.topic_num=topic.topic_num and topic.objector_nick_id is null group by topic.topic_num)')
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
                            ->latest('submit_time')->first();
        } else {

            if ((isset($_REQUEST['asof']) && $_REQUEST['asof'] == "review") || (session('asofDefault')=="review" && !isset($_REQUEST['asof']))) {

                return self::where('topic_num', $topicnum)
                                ->where('camp_num', '=', $campnum)
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
                                ->where('camp_num', '=', $campnum)
                                ->where('objector_nick_id', '=', NULL)
                                ->where('go_live_time', '<=', $asofdate)
                                ->latest('submit_time')->first();
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

    public static function getAllChildCamps($camp) {
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
            $childCamps =  Camp::where('topic_num', $camp->topic_num)->where('parent_camp_num', $camp->camp_num)->groupBy('camp_num')->latest('submit_time')->get();
            //echo "<pre>"; print_r($childCamps);
            foreach ($childCamps as $child) {
                $camparray = array_merge($camparray, self::getAllChildCamps($child));
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
                        //->where('objector_nick_id', '=', NULL)
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

        $childCamps = array_unique(self::getAllChildCamps($onecamp));
        $mysupports = Support::where('topic_num', $topic_num)->whereIn('camp_num', $childCamps)->whereIn('nick_name_id', $userNicknames)->where('end', '=', 0)->where('delegate_nick_name_id','=',0)->orderBy('support_order', 'ASC')->groupBy('camp_num')->get();

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
        $delegatedSupports = session("topic-support-{$topicnum}")->filter(function($item) use ($delegateNickId) {
            return $item->delegate_nick_name_id == $delegateNickId;
        });

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

    public function getCamptSupportCount($algorithm, $topicnum, $campnum) {

        $supportCountTotal = 0;
        try {
           
            foreach (session("topic-support-nickname-$topicnum") as $supported) {
                $nickNameSupports = session("topic-support-{$topicnum}")->filter(function ($item) use($supported) {
                    return $item->nick_name_id == $supported->nick_name_id; /* Current camp support */
                });
                $supportPoint = Algorithm::{$algorithm}($supported->nick_name_id,$supported->topic_num,$supported->camp_num);                
                
                $currentCampSupport = $nickNameSupports->filter(function ($item) use($campnum) {
                            return $item->camp_num == $campnum; /* Current camp support */
                        })->first();
                        
                
			   /*The canonizer value should be the same as their value supporting that camp. 
				   1 if they only support one party, 
				   0.5 for their first, if they support 2, 
				   0.25 after and half, again, for each one after that. */
				if ($currentCampSupport) {
                    $multiSupport = false; //default
                     if ($nickNameSupports->count() > 1) {
                        $multiSupport = true;
                        $supportCountTotal += round($supportPoint / (2 ** ($currentCampSupport->support_order)), 2);
                    } else if ($nickNameSupports->count() == 1) {
                         $supportCountTotal += $supportPoint;
                    }
                    $supportCountTotal += $this->getDeletegatedSupportCount($algorithm, $topicnum, $campnum, $supported->nick_name_id, $currentCampSupport->support_order, $multiSupport);
                }
               
            } 
        } catch (\Exception $e) {
            echo "topic-support-nickname-$topicnum" . $e->getMessage();
        }

        return $supportCountTotal;
    }

    public function buildCampTree($traversedTreeArray, $currentCamp = null, $activeCamp = null, $activeCampDefault = false,$add_supporter = false, $arrowposition) {
        $html = '<ul class="childrenNode">';
		$action = Route::getCurrentRoute()->getActionMethod();
        $onecamp =  self::getLiveCamp($this->topic_num, $activeCamp);
        
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
                $html .= '<span class="' . $class . '">' . $icon . '</span><div class="tp-title"><a style="' . $selected . '" href="' . $array['link'] . '">' . $array['title'] . '</a> <div class="badge">' . $array['score'] .'</div>'.$support_tree_html;
               
                $html .= '</div>';
                $html .= $this->buildCampTree($array['children'], $campnum, $activeCamp, $activeCampDefault,$add_supporter,$arrowposition);
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
            $onecamp = self::getLiveCamp($child->topic_num,$child->camp_num,['nofilter'=>true]);
            $title = $onecamp->camp_name;//preg_replace('/[^A-Za-z0-9\-]/', '-', $onecamp->camp_name);
            $topic_id = $child->topic_num . "-" . $title;
            $array[$child->camp_num]['title'] = $title;
			$queryString = (app('request')->getQueryString()) ? '?'.app('request')->getQueryString() : "";
            $array[$child->camp_num]['link'] = self::getTopicCampUrl($child->topic_num,$child->camp_num). $queryString .'#statement';
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

    public function campTree($algorithm, $activeAcamp = null, $supportCampCount = 0, $needSelected = 0) {
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

        $topic = Topic::getLiveTopic($this->topic->topic_num,['nofilter'=>false]);

        $topic_name = (isset($topic) && isset($topic->topic_name)) ? $topic->topic_name: '';
        $title = preg_replace('/[^A-Za-z0-9\-]/', '-', $topic_name);        
        $topic_id = $this->topic_num . "-" . $title;
        $tree = [];
        $tree[$this->camp_num]['title'] = $topic_name;
        $tree[$this->camp_num]['link'] = self::getTopicCampUrl($this->topic_num,$this->camp_num);//  url('topic/' . $topic_id . '/' . $this->camp_num.'#statement');
        $tree[$this->camp_num]['score'] = $this->getCamptSupportCount($algorithm, $this->topic_num, $this->camp_num);
        $tree[$this->camp_num]['children'] = $this->traverseCampTree($algorithm, $this->topic_num, $this->camp_num);
        
        return $reducedTree = TopicSupport::sumTranversedArraySupportCount($tree);
    }

    public function campTreeHtml($activeCamp = null, $activeCampDefault = false,$add_supporter = false, $arrowposition ='fa-arrow-down') {
        $reducedTree = $this->campTree(session('defaultAlgo', 'blind_popularity'), $activeAcamp = null, $supportCampCount = 0, $needSelected = 0);
        
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
        $parentClass = is_array($reducedTree[$this->camp_num]['children']) && count($reducedTree[$this->camp_num]['children']) > 0 ? 'parent' : 'noCampArrow';
        $icon = is_array($reducedTree[$this->camp_num]['children']) && count($reducedTree[$this->camp_num]['children']) > 0 ? '<i class="fa '.$arrowposition.'"></i>' : '';
        if(count($reducedTree[$this->camp_num]['children']) == 0 )
		$icon = '<i class="fa '.$arrowposition.'"></i>';
	  
        $action = Route::getCurrentRoute()->getActionMethod();

        if($action =="index" || $action =="loadtopic")		
 	      $icon = '<i class="fa '.$arrowposition.'"></i>';

		$html .= '<span class="' . $parentClass . '">'. $icon.' </span>';
        $html .= '<div class="tp-title"><a style="' . $selected . '" href="' . $reducedTree[$this->camp_num]['link'] . '">' . $reducedTree[$this->camp_num]['title'] . '</a><div class="badge">' . round($reducedTree[$this->camp_num]['score'], 2) . '</div>'.$support_tree_html.'</div>';        
        $html .= $this->buildCampTree($reducedTree[$this->camp_num]['children'], $this->camp_num, $activeCamp, $activeCampDefault,$add_supporter,$arrowposition);
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
    
    public static function getSubscriptionList($userid,$topic_num,$camp_num=1){
        $list = [];
         $onecamp = self::getLiveCamp($topic_num, $camp_num);
         self::clearChildCampArray();
        $childCamps = array_unique(self::getAllChildCamps($onecamp));
       
        $subscriptions = \App\Model\CampSubscription::where('user_id','=',$userid)->where('topic_num','=',$topic_num)->where('subscription_start','<=',strtotime(date('Y-m-d H:i:s')))->where('subscription_end','=',null)->orWhere('subscription_end','>=',strtotime(date('Y-m-d H:i:s')))->get();
        if(isset($subscriptions ) && count($subscriptions ) > 0){
            $i=1;
            foreach($subscriptions as $subs){
                if($camp_num!=1){
                    if(!in_array($subs->camp_num, $childCamps)){
                        continue;
                    }
                }
                $topic = self::getLiveCamp($subs->topic_num,$subs->camp_num,['nofilter'=>true]);
                $title = preg_replace('/[^A-Za-z0-9\-]/', '-', ($topic->title != '') ? $topic->title : $topic->camp_name);
                $topic_id =$subs->topic_num . "-" . $title;
                $link = self::getTopicCampUrl($topic_num,$camp_num); //url('topic/' . $topic_id . '/' . $subs->camp_num);
                $list[]= '<a href="'.$link.'">'.$topic->camp_name.'</a>';
            }
        }
        return $list;
    }

    public static function getCampSubscribers($topic_num,$camp_num){
        $users_data = [];
        $users = \App\Model\CampSubscription::select('user_id')->where('topic_num','=',$topic_num)
                ->where('camp_num','=',$camp_num)->get();
        if(count($users)){
            foreach($users as $user){
                array_push($users_data, $user->user_id);
            }
        }
        $onecamp = self::getLiveCamp($topic_num, $camp_num,['nofilter'=>true]);

        $childCampData = [];
        if(isset($onecamp) && isset($onecamp->camp_name)){
             $childCampData = $onecamp->campChild($topic_num,$camp_num);   
        }
        $child_camps = [];
        if(count($childCampData) > 0){
            foreach($childCampData as $key=>$child){
                $child_camps[$key] = $child->camp_num;
            }
        }

        if(count($child_camps) > 0){
            $usersData = \App\Model\CampSubscription::select('user_id')->where('topic_num','=',$topic_num)
                ->whereIn('camp_num',$child_camps)->get(); 
           if(count($usersData)){
            foreach($usersData as $user){
                array_push($users_data, $user->user_id);
                }
            }
        }
        return $users_data;

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

}
