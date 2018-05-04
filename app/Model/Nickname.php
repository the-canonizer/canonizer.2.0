<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Library\General;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use DB;
use App\Model\Camp;
use Illuminate\Database\Eloquent\Collection;

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
    
    public static function personNicknameArray() {
		
		$userNickname=array();
		$nicknames = self::personNickname();
			
			foreach($nicknames as $nickname) {
				
				$userNickname[] = $nickname->id;
			}
		return $userNickname;	
	}	
    public function getSupportCampList(){

        $as_of_time = time();
        $as_of_clause = '';

        $namespace = isset($_REQUEST['namespace']) ? $_REQUEST['namespace'] : 1;

        if(isset($_REQUEST['asof']) && $_REQUEST['asof']  == 'review') {
            
        }else if (isset($_REQUEST['asof']) && $_REQUEST['asof'] ==  'bydate') {
            $as_of_time = strtotime(date('Y-m-d H:i:s', strtotime($_REQUEST['asofdate'])));
            $as_of_clause = "and go_live_time < $as_of_time";
        } else {
            $as_of_clause = 'and go_live_time < ' . $as_of_time;
        }

        $sql = "select u.topic_num, u.camp_num, u.title, p.support_order, p.delegate_nick_name_id from support p, 
        (select s.title, s.topic_num, s.camp_num from camp s,
            (select topic_num, camp_num, max(go_live_time) as camp_max_glt from camp
                where objector_nick_id is null $as_of_clause group by topic_num, camp_num) cz,
                (select t.topic_num, t.topic_name, t.namespace, t.go_live_time from topic t,
                    (select ts.topic_num, max(ts.go_live_time) as topic_max_glt from topic ts
                        where ts.namespace_id=$namespace and ts.objector_nick_id is null $as_of_clause group by ts.topic_num) tz
                            where t.namespace_id=$namespace and t.topic_num = tz.topic_num and t.go_live_time = tz.topic_max_glt) uz
                where s.topic_num = cz.topic_num and s.camp_num=cz.camp_num and s.go_live_time = cz.camp_max_glt and s.topic_num=uz.topic_num) u
        where u.topic_num = p.topic_num and ((u.camp_num = p.camp_num) or (u.camp_num = 1)) and p.nick_name_id = {$this->id} and
        (p.start < $as_of_time) and ((p.end = 0) or (p.end > $as_of_time))";
        $results = DB::select($sql);
        $supports = [] ;
        foreach($results as $rs){
            $topic_num     = $rs->topic_num;
		    $camp_num = $rs->camp_num;
            $title = preg_replace('/[^A-Za-z0-9\-]/', '-', $rs->title); 
		    $topic_id = $topic_num."-".$title;
		if($rs->delegate_nick_name_id) {
			
            }else if ($camp_num == 1) {
                $supports[$topic_num]['title'] = $rs->title;
                
                $supports[$topic_num]['link'] = url('topic/'.$topic_id.'/'.$camp_num);
            }else {
                $supports[$topic_num]['array'][$rs->support_order][]=['title' =>$rs->title,'camp_num'=> $camp_num,'link'=>url('topic/'.$topic_id.'/'.$camp_num)];
            }
        }

        return $supports;
    }

    public function getUser(){
        
        $userId = \App\Library\General::canon_decode($this->owner_code);
        return  \App\User::find($userId);
    }

}
