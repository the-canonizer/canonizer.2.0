<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class ChangeAgreeLog extends Model {

    // protected $primaryKey = 'support_id';
    protected $table = 'change_agree_logs';
    public $timestamps = false;
    protected static $tempArray = [];

    public static function boot() {
        
    }

    public static function isAgreed($id, $nickNameId, $for = 'statement') {
        return self::where('change_id', '=', $id)->where('change_for', '=', $for)->where('nick_name_id', '=', $nickNameId)->count();
    }

}
