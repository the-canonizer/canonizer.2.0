<?php

namespace App\Model;
use DB;

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
            'transhumanist'=>'Transhumanist'
        );
    }
    
    /**
        Returns camp_count
        @nick_name_id , $condition
    */
    public static function camp_count($nick_name_id,$condition){
        $sql = "select count(*) as countTotal from support_instance
         inner join topic_support on topic_support.id = support_instance.topic_support_id 
         where nick_name_id = $nick_name_id and (" .$condition.")";
        $result = DB::select("$sql");
        return isset($result[0]->countTotal) ? $result[0]->countTotal : 0;
        //and ((start < $as_of_time) and ((end = 0) or (end > $as_of_time)))";
    }

    public static function blind_popularity($nick_name_id = null){
        return 1;
    }

    public static function mind_experts(){
        return 1;
    }

     public static function special_mind_experts(){
        return 1;
    }

    public static function computer_science_experts(){
        return 1;
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

    
}