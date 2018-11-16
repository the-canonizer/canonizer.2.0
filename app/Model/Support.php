<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Support extends Model {

    protected $primaryKey = 'support_id';
    protected $table = 'support';
    public $timestamps = false;

    protected static $tempArray = [];

   
    public static function boot() {
        
    }   
	
	public function nickname() {
        return $this->hasOne('App\Model\Nickname', 'id', 'nick_name_id');
    }
	public function camp() {
        return $this->hasOne('App\Model\Camp', 'camp_num', 'camp_num')->where('camp.topic_num',$this->topic_num)->orderBy('camp.submit_time','DESC');
    }
	public function topic() {
        return $this->hasOne('App\Model\Topic', 'topic_num', 'topic_num');
    }
	
	public function delegatednickname() {
        return $this->hasOne('App\Model\Nickname', 'id', 'delegate_nick_name_id');
    }
    
	public static function ifIamSingleSupporter($topic_num,$camp_num=0,$userNicknames) {
	 
	   $othersupports = self::where('topic_num',$topic_num)->whereNotIn('nick_name_id',$userNicknames)->where('end','=',0)->orderBy('support_order','ASC')->get();
	   
	   $othersupports->filter(function($item) use($camp_num){
			if($camp_num){
				return $item->camp_num == $camp_num;
			}
		});
		
		return count($othersupports) ? 0 : 1 ;
	   
	}	
	
	public static function getDirectSupporter($topic_num,$camp_num=1) {
		$as_of_time = time();
		return Support::where('topic_num','=',$topic_num)
		                ->where('camp_num','=',$camp_num)
                        ->where('delegate_nick_name_id',0)
                        ->whereRaw("(start <= $as_of_time) and ((end = 0) or (end > $as_of_time))")
                        ->orderBy('start','DESC')
                        ->groupBy('nick_name_id')
						->select(['nick_name_id','support_order','topic_num','camp_num'])
                        ->get();
	}
        
        public static function ifIamSupporter($topinum,$campnum,$nickNames){
            $support = self::where('topic_num','=',$topinum)->where('camp_num','=',$campnum)->whereIn('nick_name_id',$nickNames)->where('delegate_nick_name_id',0)->where('end','=',0)->first();
            //echo "<pre>"; print_r($support); exit;
            return count($support) ? $support->nick_name_id : 0 ;
        }
        
        public static function getAllSupporters($topic,$camp){
           $support = self::where('topic_num','=',$topic)->where('camp_num','=',$camp)
                    ->where('end','=',0)
                    ->where('delegate_nick_name_id',0)->groupBy('nick_name_id')->get(); 
           
           return count($support);
           
        }
}
