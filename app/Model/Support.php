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
        return $this->hasOne('App\Model\Camp', 'camp_num', 'camp_num')->where('camp.objector_nick_id','=',NULL)->where('camp.topic_num',$this->topic_num)->orderBy('camp.submit_time','DESC');
    }
	public function topic() {
        return $this->hasOne('App\Model\Topic', 'topic_num', 'topic_num')->where('topic.objector_nick_id','=',NULL)->where('topic.go_live_time','<=',time())->orderBy('topic.submit_time','DESC');
    }
	
	public function delegatednickname() {
        return $this->hasOne('App\Model\Nickname', 'id', 'delegate_nick_name_id');
    }
    
	public static function ifIamSingleSupporter($topic_num,$camp_num=0,$userNicknames) {
	   $othersupports = [];
       $supportFlag = 1;
       if($camp_num != 0){
        $othersupports = self::where('topic_num',$topic_num)->where('camp_num',$camp_num)->whereNotIn('nick_name_id',$userNicknames)->where('end','=',0)->orderBy('support_order','ASC')->get();
        }else{
            $othersupports = self::where('topic_num',$topic_num)->whereNotIn('nick_name_id',$userNicknames)->where('end','=',0)->orderBy('support_order','ASC')->get();
        }


       $othersupports->filter(function($item) use($camp_num){
            if($camp_num){
                return $item->camp_num == $camp_num;
            }
        });
       
        if(count($othersupports) > 0){
                $supportFlag = 0;
        }else{
            if($camp_num != 0){
                $camp = Camp::where('camp_num','=',$camp_num)->where('topic_num','=',$topic_num)->get();
                $allChildren = Camp::getAllChildCamps($camp[0]);
                if(sizeof($allChildren) > 0 ){
                foreach($allChildren as $campnum){
                    $support = self::where('topic_num',$topic_num)->where('camp_num',$campnum)->whereNotIn('nick_name_id',$userNicknames)->where('end','=',0)->orderBy('support_order','ASC')->get();
                      if(sizeof($support) > 0){
                            $supportFlag = 0;
                            break;
                        }
                    }
                }
            }else{
                $support = self::where('topic_num',$topic_num)->whereNotIn('nick_name_id',$userNicknames)->where('end','=',0)->orderBy('support_order','ASC')->get();
                      if(sizeof($support) > 0){
                            $supportFlag = 0;
                        }
            }
            
        }
		return  $supportFlag;
	   
	}	
	public static function getAllDirectSupporters($topic_num,$camp_num=1){
        $camp  = Camp::getLiveCamp($topic_num, $camp_num);
        Camp::clearChildCampArray();
        $subCampIds = array_unique(Camp::getAllChildCamps($camp));
        $directSupporter = [];
        $alreadyExists = [];
        foreach ($subCampIds as $camp_id) {            
            $data = self::getDirectSupporter($topic_num, $camp_id);
            if(isset($data) && count($data) > 0){
                foreach($data as $key=>$value){
                    if(!in_array($value->nick_name_id, $alreadyExists,TRUE)){
                        $directSupporter[] = $value;
                         $alreadyExists[] = $value->nick_name_id;
                     }  
                }
                  
            }
            
        }
        return $directSupporter;
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
        
        public static function ifIamSupporter($topinum,$campnum,$nickNames,$submit_time = null){
            if($submit_time){
                $support = self::where('topic_num','=',$topinum)->where('camp_num','=',$campnum)->whereIn('nick_name_id',$nickNames)->where('delegate_nick_name_id',0)->where('end','=',0)->where('start','<=',$submit_time)->first();
            }else{
                $support = self::where('topic_num','=',$topinum)->where('camp_num','=',$campnum)->whereIn('nick_name_id',$nickNames)->where('delegate_nick_name_id',0)->where('end','=',0)->first();
            }
            
            return count($support) ? $support->nick_name_id : 0 ;
        }
        
        public static function getAllSupporters($topic,$camp,$excludeNickID){
            $nickNametoExclude = [$excludeNickID];
           $support = self::where('topic_num','=',$topic)->where('camp_num','=',$camp)
                    ->where('end','=',0)
                    ->where('nick_name_id','!=',$excludeNickID)
                    ->where('delegate_nick_name_id',0)->groupBy('nick_name_id')->get(); 
            $camp = Camp::where('camp_num','=',$camp)->where('topic_num','=',$topic)->get();
            $allChildren = Camp::getAllChildCamps($camp[0]);
            $supportCount = 0;
            $nickNamesData = \App\Model\Nickname::personNicknameArray();
            if(sizeof($support) > 0 || count($support) >0){
                foreach($support as $sp){
                    array_push( $nickNametoExclude, $sp->nick_name_id);
                }
            }
          if(sizeof($allChildren) > 0 ){
            foreach($allChildren as $campnum){
                $supportData = self::where('topic_num',$topic)->where('camp_num',$campnum)->whereNotIn('nick_name_id',$nickNametoExclude)->where('end','=',0)->orderBy('support_order','ASC')->get();
               if(count($supportData) > 0){
                        foreach($supportData as $sp){
                            array_push( $nickNametoExclude, $sp->nick_name_id);
                        }
                        $supportCount = $supportCount + count($supportData);
                    }
                }
            }
           return count($support)+$supportCount;
           
        }
}
