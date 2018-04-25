<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Library\General;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use DB;

class Nickname extends Model
{
    protected $table = 'nick_name';
    public $timestamps = false;
	
	
	public function camps() {
        return $this->hasMany('App\Model\Camp', 'nick_name_id', 'nick_name_id');
    }
	
	public function supports() {
        return $this->hasMany('App\Model\Support', 'nick_name_id', 'nick_name_id')->orderBy('support_order','ASC');
    }
	
	public static function personNickname(){
		
		$userid = Auth::user()->id; 
        $encode = General::canon_encode($userid);
		
        return  DB::table('nick_name')->select('id','nick_name')->where('owner_code',$encode)->get();
	}
}
