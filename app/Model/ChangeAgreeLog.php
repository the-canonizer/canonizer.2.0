<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class ChangeAgreeLog extends Model {

   // protected $primaryKey = 'support_id';
    protected $table = 'change_agree_log';
    public $timestamps = false;

    protected static $tempArray = [];

   
    public static function boot() {
        
    }   
	

}
