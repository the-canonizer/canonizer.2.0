<?php

namespace App\Model;
use DB;
use Illuminate\Support\Facades\Cache;
use App\Model\Camp;
use App\Model\Support;
use App\Model\EtherAddresses;
use App\Model\Nickname;
use App\Model\SharesAlgorithm;
use Illuminate\Database\Eloquent\Collection;

class Algorithm{

    /**
    @return all the available algorithm list
    */
    public static function getList(){
        return array(
            'blind_popularity'=>'One Person One Vote',
            'mind_experts'=>'Mind Experts',
            'computer_science_experts'=>'Computer Science Experts',
            'PhD'=>'Ph.D.',
            'christian'=>'Christian',
            'secular'=>'Secular / Non Religious',
            'mormon'=>'Mormon',
            'uu'=>'Universal Unitarian',
            'atheist'=>'Atheist',
            'transhumanist'=>'Transhumanist',
			'united_utah'=>'United Utah',
			'republican'=>'Republican',
			'democrat'=>'Democrat',
			'ether' => 'Ethereum',
            'shares'=> 'Canonizer Shares',
            'shares_sqrt' => 'Canonizer Canonizer'
        );
    }
	
	/**
        @return all the available algorithm key values
    */
    public static function getKeyList(){
        return array('blind_popularity','mind_experts','computer_science_experts','PhD','christian','secular','mormon','uu','atheist','transhumanist','united_utah','republican','democrat', 'ether','shares','shares_sqrt'
        );
    }
    
    /**
        Returns camp_count
        @nick_name_id , $condition
    */
    public static function camp_count($nick_name_id,$condition, $political=false,$topicnum=0,$campnum=0){
        $as_of_time = time();
        $cacheWithTime = false; 
        if(isset($_REQUEST['asof']) && $_REQUEST['asof'] == 'bydate'){
            if(isset($_REQUEST['asofdate']) && !empty($_REQUEST['asofdate'])){
                $as_of_time = strtotime($_REQUEST['asofdate']);
                $cacheWithTime = true;
            }
        }
  
        $sql = "select count(*) as countTotal,support_order,camp_num from support where nick_name_id = $nick_name_id and (" .$condition.")";
        $sql2 ="and ((start < $as_of_time) and ((end = 0) or (end > $as_of_time)))";
         
        /* Cache applied to avoid repeated queries in recursion */
        if($cacheWithTime){
            $result = Cache::remember("$sql $sql2", 2, function () use($sql,$sql2) {
                return DB::select("$sql $sql2");
            });
            return isset($result[0]->countTotal) ? $result[0]->countTotal : 0;
        }else{
            $result = Cache::remember("$sql", 1, function () use($sql,$sql2) {
                return DB::select("$sql $sql2");
            });
           
		 if($political==true && $topicnum==231 && ($campnum==2 ||  $campnum==3 || $campnum==4) ) {						
                      	
			if($result[0]->support_order==1)
				$total = $result[0]->countTotal / 2;
			else if($result[0]->support_order==2)
				$total = $result[0]->countTotal / 4;
			else if($result[0]->support_order==3)
				$total = $result[0]->countTotal / 6;
			else if($result[0]->support_order==4)
				$total = $result[0]->countTotal / 8;
			else $total = $result[0]->countTotal;
			
		 } else {
			$total = $result[0]->countTotal; 
		 }	
			
			
            return isset($result[0]->countTotal) ? $total : 0;
        }
    }

    public static function ether($nick_name_id,$topicnum=0,$campnum=0) {
        $user_id = Nickname::getUserIDByNickName($nick_name_id);
        $ethers = EtherAddresses::where('user_id', '=', $user_id)->get();
        
        $total_ethers = 0;
        
        $api_key = '0d4a2732eca64e71a1be52c3a750aaa4';                      // Project Key
        $ether_url = 'https://mainnet.infura.io/v3/' + $api_key;            // Ether Url

        foreach ($ethers as $ether) {                                       // If users has multiple addresses

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://mainnet.infura.io/v3/0d4a2732eca64e71a1be52c3a750aaa4",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "{\"jsonrpc\":\"2.0\",\"method\":\"eth_getBalance\",\"params\": [\"$ether->address\", \"latest\"],\"id\":1}",
                CURLOPT_HTTPHEADER => array(
                  "Accept-Encoding: gzip, deflate",
                  "Cache-Control: no-cache",
                  "Connection: keep-alive",
                  "Content-Type: application/json",
                  "Host: mainnet.infura.io"
                ),
              ));

            $curl_response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return 0;
            } 
            else {
                $curl_result_obj = json_decode($curl_response);
                $balance = $curl_result_obj->result;
                $total_ethers += (hexdec($balance)/1000000000000000000);       // Convert Ether to Wei
            }
        }
        
        return $total_ethers;
    }

    public static function share_algo($nick_name_id,$topicnum=0,$campnum=0,$algo='shares'){
        $as_of_time = time();
        if(isset($_REQUEST['asof']) && $_REQUEST['asof']=='bydate'){
            $as_of_time = strtotime($_REQUEST['asofdate']);
        }else{
            // get the last month shares added for user as current share #1055
            $latest_record = SharesAlgorithm::where('nick_name_id',$nick_name_id)->orderBy('as_of_date','desc')->first();
            if(isset($latest_record) && isset($latest_record->as_of_date)){
                $as_of_time = strtotime($latest_record->as_of_date);  
            }
        }
        $year = date('Y',$as_of_time);
        $month = date('m',$as_of_time);
        $shares = SharesAlgorithm::whereYear('as_of_date', '=', $year)
              ->whereMonth('as_of_date', '<=', $month)->where('nick_name_id',$nick_name_id)->orderBy('as_of_date', 'ASC')->get();
        $sum_of_shares = 0;
        $sum_of_sqrt_shares = 0;
       
        if(count($shares)){
            foreach($shares as $s){
                $sum_of_shares = $s->share_value; //$sum_of_shares + $s->share_value;
                $sum_of_sqrt_shares =  number_format(sqrt($s->share_value),2);//$sum_of_sqrt_shares+ number_format(sqrt($s->share_value),2);
            }
        }
        $condition = "topic_num = $topicnum and camp_num = $campnum";
         $sql = "select count(*) as countTotal,support_order,camp_num from support where nick_name_id = $nick_name_id and (" .$condition.")";
        $sql2 ="and ((start < $as_of_time) and ((end = 0) or (end > $as_of_time)))";
         
        $result = Cache::remember("$sql $sql2", 2, function () use($sql,$sql2) {
                return DB::select("$sql $sql2");
        });
        $total = 0;
         if($algo == 'shares'){
           // $total = $result[0]->countTotal * $sum_of_shares;
           $total = $sum_of_shares;
        }else{
            //$total = $result[0]->countTotal * $sum_of_sqrt_shares;
            $total =  $sum_of_sqrt_shares;
        }
        $shares_return  = $total;
        
        return  ($shares_return > 0) ? $shares_return : 0;

    }

    public static function blind_popularity($nick_name_id = null,$topicnum=0,$campnum=0){
        return 1;
    }

    public static function mind_experts($nick_name_id = null,$topicnum=0,$campnum=0){
        return self::camp_tree_count(81,$nick_name_id);
    }

    public static function shares($nick_name_id = null,$topicnum=0,$campnum=0,$algo='shares'){
        return self::share_algo($nick_name_id,$topicnum,$campnum,$algo);
    }
    public static function shares_sqrt($nick_name_id= null,$topicnum=0,$campnum=0,$algo='shares_sqrt'){
        return self::share_algo($nick_name_id,$topicnum,$campnum,$algo);
    }


    public static function computer_science_experts($nick_name_id,$topicnum=0,$campnum=0){
        return self::camp_tree_count(124,$nick_name_id);
    }

    /**
        Transhumanist - Algorithm
    */

    public static function transhumanist($nick_name_id,$topicnum=0,$campnum=0){
         $condition = '(topic_num = 40 and camp_num = 2) or ' .
			     '(topic_num = 41 and camp_num = 2) or ' .
			     '(topic_num = 42 and camp_num = 2) or ' .
			     '(topic_num = 42 and camp_num = 4) or ' .
			     '(topic_num = 43 and camp_num = 2) or ' .
			     '(topic_num = 44 and camp_num = 3) or ' .
			     '(topic_num = 45 and camp_num = 2) or ' .
			     '(topic_num = 46 and camp_num = 2) or ' .
			     '(topic_num = 47 and camp_num = 2) or ' .
			     '(topic_num = 48 and camp_num = 2) or ' .
			     '(topic_num = 48 and camp_num = 3) or ' .
			     '(topic_num = 49 and camp_num = 2) ';

        return self::camp_count($nick_name_id,$condition);
    }

    public static function atheist($nick_name_id,$topicnum=0,$campnum=0){
        $condition = '(topic_num = 54 and camp_num = 2) or ' .
				'(topic_num = 2 and camp_num = 2) or ' .
				'(topic_num = 2 and camp_num = 4) or ' .
				'(topic_num = 2 and camp_num = 5)';
        return self::camp_count($nick_name_id,$condition);
    }

    public static function uu($nick_name_id,$topicnum=0,$campnum=0){
        $condition = '(topic_num = 54 and camp_num = 15)';
        return self::camp_count($nick_name_id,$condition);
    }

    public static function secular($nick_name_id,$topicnum=0,$campnum=0){
        $condition = '(topic_num = 54 and camp_num = 3)';
        return self::camp_count($nick_name_id,$condition);
    }

    public static function mormon($nick_name_id,$topicnum=0,$campnum=0){
        $condition = '(topic_num = 54 and camp_num = 7) or ' .
                '(topic_num = 54 and camp_num = 8) or ' .
                '(topic_num = 54 and camp_num = 9) or ' .
                '(topic_num = 54 and camp_num = 10) or ' .
                '(topic_num = 54 and camp_num = 11)';
        // if($campnum == 9 || $campnum == 8){
        //     $condition = '(topic_num = 54 and camp_num = 8) or ' .
        //         '(topic_num = 54 and camp_num = 9)';
        // }
        
       // $condition = "(topic_num = $topicnum and camp_num = $campnum)";
                //if($nick_name_id == 67)
               // echo self::camp_count($nick_name_id,$condition,false,$topicnum,$campnum)."--".$nick_name_id."<br/>"; 
        return self::camp_count($nick_name_id,$condition,false,$topicnum,$campnum);
    }

    public static function christian($nick_name_id,$topicnum=0,$campnum=0){
        $condition = '(topic_num = 54 and camp_num = 4) or ' .
			     '(topic_num = 54 and camp_num = 5) or ' .
			     '(topic_num = 54 and camp_num = 6) or ' .
			     '(topic_num = 54 and camp_num = 7) or ' .
			     '(topic_num = 54 and camp_num = 8) or ' .
			     '(topic_num = 54 and camp_num = 9) or ' .
			     '(topic_num = 54 and camp_num = 10) or ' .
			     '(topic_num = 54 and camp_num = 11) or ' .
			     '(topic_num = 54 and camp_num = 18)';
        return self::camp_count($nick_name_id,$condition);
    }

    public static function PhD($nick_name_id,$topicnum=0,$campnum=0){
        $condition = '(topic_num = 55 and camp_num =  5) or ' .
						   '(topic_num = 55 and camp_num = 10) or ' .
						   '(topic_num = 55 and camp_num = 11) or ' .
						   '(topic_num = 55 and camp_num = 12) or ' .
						   '(topic_num = 55 and camp_num = 14) or ' .
						   '(topic_num = 55 and camp_num = 15) or ' .
						   '(topic_num = 55 and camp_num = 17)';
        return self::camp_count($nick_name_id,$condition);
    }
	
	// United Utah Party Algorithm using related topic and camp
	
	public static function united_utah($nick_name_id,$topicnum=0,$campnum=0){
        $condition = '(topic_num = 231 and camp_num = 2)';
        return self::camp_count($nick_name_id,$condition,true,231,2);
    }
	
	// Republican Algorithm using related topic and camp
	 
	public static function republican($nick_name_id,$topicnum=0,$campnum=0){
        $condition = '(topic_num = 231 and camp_num = 3)';
        return self::camp_count($nick_name_id,$condition,true,231,3);
    }
	
	// Democrat Algorithm using related topic and camp
	
	public static function democrat($nick_name_id,$topicnum=0,$campnum=0){
        $condition = '(topic_num = 231 and camp_num = 4)';
        return self::camp_count($nick_name_id,$condition,true,231,4);
    }

    public static function camp_tree_count($topicnum,$nick_name_id){

        $camps = new Collection;
        if(!isset($_REQUEST['asof']) || (isset($_REQUEST['asof']) && $_REQUEST['asof']=="default")) {
		
            $camps = Cache::remember("$topicnum-default-support", 2, function () use($topicnum) {
                return Camp::where('topic_num', '=', $topicnum)
                ->where('objector_nick_id', '=', NULL)
                ->whereRaw('go_live_time in (select max(go_live_time) from camp where topic_num='.$topicnum.' and objector_nick_id is null and go_live_time < "'.time().'" group by camp_num)')				
                ->where('go_live_time','<',time())
                ->groupBy('camp_num')				
				->orderBy('submit_time', 'desc')
                ->get();
            });
		} else {
			
			if(isset($_REQUEST['asof']) && $_REQUEST['asof']=="review") {
			$camps = Cache::remember("$topicnum-review-support", 2, function () use($topicnum) {
                return Camp::where('topic_num', '=', $topicnum)
                            ->where('objector_nick_id', '=', NULL)
                            ->whereRaw('go_live_time in (select max(go_live_time) from camp where topic_num='.$topicnum.' and objector_nick_id is null group by camp_num)')				
                            ->orderBy('submit_time', 'desc')
                            ->groupBy('camp_num')
                            ->get();	
                });
				
			} else if(isset($_REQUEST['asof']) && $_REQUEST['asof']=="bydate") {
				
				$asofdate =  strtotime(date('Y-m-d H:i:s', strtotime($_REQUEST['asofdate'])));
                $camps = Cache::remember("$topicnum-bydate-support-$asofdate", 2, function () use($topicnum,$asofdate) {
                    return $expertCamp = Camp::where('topic_num', '=', $topicnum)
                        ->where('objector_nick_id', '=', NULL)
                        ->whereRaw('go_live_time in (select max(go_live_time) from camp where topic_num='.$topicnum.' and objector_nick_id is null group by camp_num)')				
                        ->where('go_live_time','<',$asofdate) 				
                        ->orderBy('submit_time', 'desc')
                        ->groupBy('camp_num')
                        ->get();
                });
			} 
		}	

        $expertCamp = $camps->filter(function($item) use($nick_name_id){
            return  $item->camp_about_nick_id == $nick_name_id;
        })->last();
        
        if(!$expertCamp){ # not an expert canonized nick.
            return 0;
        }

        $as_of_time = time();
        $key = '';
		if(isset($_REQUEST['asof']) && $_REQUEST['asof']=='bydate'){
			$as_of_time = strtotime($_REQUEST['asofdate']);
            $key = $as_of_time;
		}

		# Implemented cache for existing data. 
        $supports = Cache::remember("$topicnum-supports-$key", 2, function () use($topicnum,$as_of_time) {
                 return Support::where('topic_num','=',$topicnum)
                    ->whereRaw("(start < $as_of_time) and ((end = 0) or (end > $as_of_time))")
                    ->orderBy('start','DESC')
                    ->select(['support_order','camp_num','topic_num','nick_name_id','delegate_nick_name_id'])
                    ->get();
        });

        $directSupports = $supports->filter(function($item) use($nick_name_id){
            return  $item->nick_name_id==$nick_name_id && $item->delegate_nick_name_id == 0;
        });
        
        $delegatedSupports = $supports->filter(function($item) use($nick_name_id){
             return $item->nick_name_id == $nick_name_id && $item->delegate_nick_name_id != 0;
        });
        
		# start with one person one vote canonize.
		$expertCampReducedTree = $expertCamp->campTree('blind_popularity',$nick_name_id); # only need to canonize this branch
        // Check if user supports himself
        $num_of_camps_supported = 0;
        
        $user_support_camps = Support::where('topic_num','=',$topicnum)
            ->whereRaw("(start < $as_of_time) and ((end = 0) or (end > $as_of_time))")
            ->where('nick_name_id', '=', $nick_name_id)
            ->get();
        
        $topic_num_array = array();
        $camp_num_array = array();

        foreach ($user_support_camps as $scamp) {
            $topic_num_array[] = $scamp->topic_num;
            $camp_num_array[] = $scamp->camp_num;
        }
        $ret_camp = Camp::whereIn('topic_num', array_unique($topic_num_array))
            ->whereIn('camp_num', array_unique($camp_num_array))
            ->whereNotNull('camp_about_nick_id')
            ->where('camp_about_nick_id', '<>', 0)
            ->whereRaw('go_live_time in (select max(go_live_time) from camp where topic_num=' . $topicnum . ' and objector_nick_id is null and go_live_time < "' . time() . '" group by camp_num)')
            ->where('go_live_time', '<', time())
            ->groupBy('camp_num')
            ->orderBy('submit_time', 'desc')
            ->get();
        if ($ret_camp->count()) {
            $num_of_camps_supported = $ret_camp->count();
        }
       
        if( ( $directSupports->count() > 0 || $delegatedSupports->count() > 0 ) && $num_of_camps_supported > 1 ) {
             return $expertCampReducedTree[$expertCamp->camp_num]['score'] * 5;             
        }else{
             return $expertCampReducedTree[$expertCamp->camp_num]['score'] * 1;
        }
        
    }
}