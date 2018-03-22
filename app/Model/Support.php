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

}
