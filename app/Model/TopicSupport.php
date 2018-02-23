<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Model\Nickname;

class TopicSupport extends Model {

    protected $table = 'topic_support';
    public $timestamps = false;    
    protected static $tempArray = [];

   
    public static function boot() {
        
    }   
	
	public function nickname() {
        return $this->hasOne('App\Model\Nickname', 'id', 'nick_name_id');
    }
	public function camp() {
        return $this->hasOne('App\Model\Camp', 'camp_num', 'camp_num');
    }
	public function topic() {
        return $this->hasOne('App\Model\Topic', 'topic_num', 'topic_num');
    }
	
	public function delegatednickname() {
        return $this->hasOne('App\Model\Nickname', 'id', 'delegate_nick_id');
    }
	public function campsupport() {
        return $this->hasMany('App\Model\SupportInstance', 'topic_support_id', 'id')->groupBy('camp_num')->orderBy('support_order','ASC')->orderBy('submit_time','DESC');
    }

    public static function traverseTree($topicnum,$campnum,$delegateNickId=0){
        $query = TopicSupport::join('support_instance','support_instance.topic_support_id','=','topic_support.id')
        ->where('topic_num', '=', $topicnum)
        ->where('support_instance.camp_num',$campnum);

		if($delegateNickId){
			$query->where('delegate_nick_id',$delegateNickId);
		}
		$supports = $query->orderBy('topic_support.submit_time','DESC')
		             ->get();

        if($supports->count() <= 0 ){
            return [$delegateNickId=>[]];
        }
        $array = [];
        foreach($supports as $support){
            
            if($support->delegate_nick_id){
				$array[$support->delegate_nick_id][] =  self::traverseTree($topicnum,$campnum,$support->nick_name_id);
			}else{
                $array[0] [] =  self::traverseTree($topicnum,$campnum,$support->nick_name_id);
                /*Collecting Direct Support*/
			}
        }
        return $array;
    }

    public static function buildTree($traversedTreeArray,$parentNode=false){
        
        $html= "";
        foreach($traversedTreeArray as $key => $array){
            if($key > 0){
                $nickName = Nickname::where('id',$key)->first();
                if($parentNode){
                    $html.= "<li class='main-parent'><a href='#'>{$nickName->nick_name}</a>";
                }else{
                    $html.= "<li><a href='#'>{$nickName->nick_name}</a>";
                }
                $html.="<ul>";
                foreach($array as $arr){
                    $html.=self::buildTree($arr,false);
                }
                $html.="</ul></li>";
            }else{
                $html.=self::buildTree($array,false);
            }
        }
        return $html;
    }

    public static function topicSupportTree($topicnum,$campnum){
        $traversedTreeArray = self::traverseTree($topicnum,$campnum);
        return self::buildTree($traversedTreeArray,true);
    }

   
}
