<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class TopicSupport extends Model {

    protected $table = 'topic_support';
    public $timestamps = false;

    protected static $tempArray = [];

   
    public static function boot() {
        
    }   
	
	public function nickname() {
        return $this->hasOne('App\Model\Nickname', 'nick_name_id', 'nick_name_id');
    }
	public function camp() {
        return $this->hasOne('App\Model\Camp', 'camp_num', 'camp_num');
    }
	public function topic() {
        return $this->hasOne('App\Model\Topic', 'topic_num', 'topic_num');
    }
	
	public function delegatednickname() {
        return $this->hasOne('App\Model\Nickname', 'nick_name_id', 'delegate_nick_id');
    }
	public function campsupport() {
        return $this->hasMany('App\Model\SupportInstance', 'topic_support_id', 'id')->groupBy('camp_num')->orderBy('support_order','ASC')->orderBy('submit_time','DESC');
    }

}
