<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class SharesAlgorithm extends Model {

    protected $table = 'shares_algo_data';
    public $timestamps = false;
    protected static $tempArray = [];



    public function usernickname() {
        return $this->hasOne('App\Model\Nickname', 'id', 'nick_name_id');
    }

    
    

}
