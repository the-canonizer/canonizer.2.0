<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Support extends Model {

    protected $table = 'support';
    public $timestamps = false;

    protected static $tempArray = [];

   
    public static function boot() {
        
    }   
	
	public function nickname() {
        return $this->hasOne('App\Model\Nickname', 'nick_name_id', 'nick_name_id');
    }

}
