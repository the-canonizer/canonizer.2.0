<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Support extends Model {

    protected $primaryKey = 'support_id';
    protected $table = 'support';
    public $timestamps = false;

    protected static $tempArray = [];

    protected $fillable = ['nick_name_id','topic_num','camp_num','delegate_nick_name_id','start','end','flags','support_order'];

   
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
        $othersupports = self::where('topic_num',$topic_num)->where('camp_num',$camp_num)->whereNotIn('nick_name_id',$userNicknames)->where('delegate_nick_name_id',0)->where('end','=',0)->orderBy('support_order','ASC')->get();
        }else{
            $othersupports = self::where('topic_num',$topic_num)->whereNotIn('nick_name_id',$userNicknames)->where('delegate_nick_name_id',0)->where('end','=',0)->orderBy('support_order','ASC')->get();
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
                Camp::clearChildCampArray();
                $allChildren = Camp::getAllChildCamps($camp[0]);
                if(sizeof($allChildren) > 0 ){
                    foreach($allChildren as $campnum){
                        $support = self::where('topic_num',$topic_num)->where('camp_num',$campnum)->whereNotIn('nick_name_id',$userNicknames)->where('delegate_nick_name_id',0)->where('end','=',0)->orderBy('support_order','ASC')->get();
                        if(sizeof($support) > 0){
                            $supportFlag = 0;
                            break;
                        }
                    }
                }
            }else{
                $support = self::where('topic_num',$topic_num)->whereNotIn('nick_name_id',$userNicknames)->where('delegate_nick_name_id',0)->where('end','=',0)->orderBy('support_order','ASC')->get();
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
        
    public static function ifIamSupporter($topinum,$campnum,$nickNames,$submit_time = null,$delayed=false){
        if($submit_time){
            if($delayed){
                $support = self::where('topic_num','=',$topinum)->where('camp_num','=',$campnum)->whereIn('nick_name_id',$nickNames)->where('delegate_nick_name_id',0)->where('end','=',0)->where('start','>',$submit_time)->first();
            }else{
                $support = self::where('topic_num','=',$topinum)->where('camp_num','=',$campnum)->whereIn('nick_name_id',$nickNames)->where('delegate_nick_name_id',0)->where('end','=',0)->where('start','<=',$submit_time)->first();
            }
        }else{
            $support = self::where('topic_num','=',$topinum)->where('camp_num','=',$campnum)->whereIn('nick_name_id',$nickNames)->where('delegate_nick_name_id',0)->where('end','=',0)->first();
        }
        
        return count($support) ? $support->nick_name_id : 0 ;
    }

    public static function ifIamImplicitSupporter($topic_num,$camp_num,$nickNames,$submit_time = null){
        $liveCamp = Camp::getLiveCamp($topic_num,$camp_num);
        $allChildCamps = Camp::getAllChildCamps($liveCamp);
        // dd(json_encode($allChildCamps));
        if($submit_time){
            $support = self::where('topic_num','=',$topic_num)->whereIn('camp_num',$allChildCamps)->whereIn('nick_name_id',$nickNames)->where('delegate_nick_name_id',0)->where('end','=',0)->where('start','<=',$submit_time)->first();
        }else{
            $support = self::where('topic_num','=',$topic_num)->whereIn('camp_num',$allChildCamps)->whereIn('nick_name_id',$nickNames)->where('delegate_nick_name_id',0)->where('end','=',0)->first();
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
            $supportData = self::where('topic_num',$topic)->where('camp_num',$campnum)->whereNotIn('nick_name_id',$nickNametoExclude)->where('delegate_nick_name_id',0)->where('end','=',0)->orderBy('support_order','ASC')->get();
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

    public function supportById($id){
        return $support = self::where('id','=',$id)->first();
    }

    public static function getDirectSupporterInCampList($topic_num, $campList=[]){
        $supporters = [];
        if(!empty($campList)){
            foreach($campList as $camp){
                $supporters[] = self::getAllDirectSupporters($topic_num,$camp);
            }
        }
        return $supporters;
    }

    public static function removeSupport($topicNum,$p_campNum,$nickName = [],$campNum="") {
        if(empty($topicNum) || empty($p_campNum) || empty($nickName)){
            return;
        }
        $supportData = self::where('topic_num',$topicNum)->where('camp_num',$p_campNum)->where('end','=',0);
        if(!empty($nickName)){
            $supportData->whereIn('nick_name_id',$nickName);
        }
        $results = $supportData->get();
        $results_child = [];
        if($campNum!=""){
            $supportData_child = self::where('topic_num',$topicNum)->where('camp_num',$campNum)->where('end','=',0);
            if(!empty($nickName)){
                $supportData_child->whereIn('nick_name_id',$nickName);
            }
            $results_child = $supportData_child->get()->toArray();
        }
        foreach($results as $value){
           
            //$value->end = time();
            //$value->save();
           
            if($campNum!=""){ 
                //1311 and 1334 1398 
                //if child camp have  same support of parent camp then remove support from parent
                if(!empty($results_child)){ // && $p_campNum==$value->camp_num

                   if(array_search($value->nick_name_id, array_column($results_child, 'nick_name_id')) !== FALSE) { //found
                        $value->end = time();
                        $value->save();
                        
                    } 
                }
                else{
                    
                    if(!empty($nickName)){
                        if (in_array($value->nick_name_id, $nickName->all())){
                            $value->end = time();
                            $value->save();
                            $supportTopic =  new self();
                            $supportTopic->topic_num = $value->topic_num;
                            $supportTopic->nick_name_id = $value->nick_name_id;
                            $supportTopic->delegate_nick_name_id = $value->delegate_nick_name_id;
                            $supportTopic->start = time();
                            $supportTopic->camp_num = $value->camp_num; //add child camp with support
                            $supportTopic->support_order = $value->support_order;
                            $supportTopic->save();
                        }
                    }
                }
               
            }
            
            //support order changes 
            $higherSupportNumbers = self::where('topic_num',$topicNum)
                ->where('end','=',0)
                ->where('nick_name_id',$value->nick_name_id)
                ->where('support_order','>',$value->support_order)->get(); 
            foreach($higherSupportNumbers as $support){
                $support->end = time();
                $support->save();
                $create = new self();
                $create->topic_num = $support->topic_num;
                $create->nick_name_id = $support->nick_name_id;
                //delegate nick name id add if any delegate
                $create->delegate_nick_name_id = $support->delegate_nick_name_id; 
                $create->start = time();
                $create->camp_num = $support->camp_num;
                $create->support_order = ($support->support_order - 1);
                $create->save();
            }
        }
        
        
       
    }

    public static function getAllChildDelegateNicknameId($topic_num,$nick_name_id, $supportId=[]) {
        $as_of_time = time();
           
            $support = Support::where('topic_num','=',$topic_num)
                    ->where('delegate_nick_name_id',$nick_name_id)
                    ->whereRaw("(start <= $as_of_time) and ((end = 0) or (end > $as_of_time))")
                    ->orderBy('start','DESC')
                    ->groupBy('nick_name_id')
                    ->select(['nick_name_id'])
                    ->get();  
            if(isset($support[0]->nick_name_id)){ 
                array_push($supportId,$nick_name_id);
                return  self::getAllChildDelegateNicknameId($topic_num, $support[0]->nick_name_id,$supportId);
            }
            else{
                return $supportId;
            }       
    }
}
