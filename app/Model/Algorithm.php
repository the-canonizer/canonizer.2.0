<?php

namespace App\Model;
use DB;
use Illuminate\Support\Facades\Cache;
use App\Model\Camp;
use App\Model\Support;
use App\Model\EtherAddresses;
use App\Model\Nickname;
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
			'ether' => 'Ethereum'
        );
    }
	
	/**
    @return all the available algorithm key values
    */
    public static function getKeyList(){
        return array('blind_popularity','mind_experts','computer_science_experts','PhD','christian','secular','mormon','uu','atheist','transhumanist','united_utah','republican','democrat', 'ether'
        );
    }
    
    /**
        Returns camp_count
        @nick_name_id , $condition
    */
    public static function camp_count($nick_name_id,$condition){
    
        $as_of_time = time();
        $cacheWithTime = false; 
        if(isset($_REQUEST['asof']) && $_REQUEST['asof'] == 'bydate'){
            if(isset($_REQUEST['asofdate']) && !empty($_REQUEST['asofdate'])){
                $as_of_time = strtotime($_REQUEST['asofdate']);
                $cacheWithTime = true;
            }
        }

        $sql = "select count(*) as countTotal from support where nick_name_id = $nick_name_id and (" .$condition.")";
        $sql2 ="and ((start < $as_of_time) and ((end = 0) or (end > $as_of_time)))
         ";
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
            return isset($result[0]->countTotal) ? $result[0]->countTotal : 0;
        }
    }

    public static function ether($nick_name_id) {
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

    public static function blind_popularity($nick_name_id = null){
        return 1;
    }

    public static function mind_experts($nick_name_id = null){
        return self::camp_tree_count(81,$nick_name_id);
    }

    public static function computer_science_experts($nick_name_id){
        return self::camp_tree_count(124,$nick_name_id);
    }

    /**
        Transhumanist - Algorithm
    */

    public static function transhumanist($nick_name_id){
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

    public static function atheist($nick_name_id){
        $condition = '(topic_num = 54 and camp_num = 2) or ' .
				'(topic_num = 2 and camp_num = 2) or ' .
				'(topic_num = 2 and camp_num = 4) or ' .
				'(topic_num = 2 and camp_num = 5)';
        return self::camp_count($nick_name_id,$condition);
    }

    public static function uu($nick_name_id){
        $condition = '(topic_num = 54 and camp_num = 15)';
        return self::camp_count($nick_name_id,$condition);
    }

    public static function secular($nick_name_id){
        $condition = '(topic_num = 54 and camp_num = 3)';
        return self::camp_count($nick_name_id,$condition);
    }

    public static function mormon($nick_name_id){
        $condition = '(topic_num = 54 and camp_num = 7) or ' .
				'(topic_num = 54 and camp_num = 8) or ' .
				'(topic_num = 54 and camp_num = 10) or ' .
				'(topic_num = 54 and camp_num = 11)';
        return self::camp_count($nick_name_id,$condition);
    }

    public static function christian($nick_name_id){
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

    public static function PhD($nick_name_id){
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
	
	public static function united_utah($nick_name_id){
        $condition = '(topic_num = 231 and camp_num = 2)';
        return self::camp_count($nick_name_id,$condition);
    }
	
	// Republican Algorithm using related topic and camp
	 
	public static function republican($nick_name_id){
        $condition = '(topic_num = 231 and camp_num = 3)';
        return self::camp_count($nick_name_id,$condition);
    }
	
	// Democrat Algorithm using related topic and camp
	
	public static function democrat($nick_name_id){
        $condition = '(topic_num = 231 and camp_num = 4)';
        return self::camp_count($nick_name_id,$condition);
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
		
        $expertCampReducedTree = $expertCamp->campTree('blind_popularity'); # only need to canonize this branch
        
        if($directSupports->count() > 0 || $delegatedSupports->count() > 0){
             return $expertCampReducedTree[$expertCamp->camp_num]['score'] * 5;
        }else{
             return $expertCampReducedTree[$expertCamp->camp_num]['score'] * 1;
        }
    }

    
}