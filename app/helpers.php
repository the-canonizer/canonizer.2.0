<?php

use Carbon\Carbon;
use App\Model\Topic;

function to_local_time($unixtime) {
    if(!is_numeric($unixtime)){
        $unixtime = Carbon::parse($unixtime)->timestamp;
    }
    echo "<script>
        document.write( 
            ( new Date($unixtime * 1000) ).toLocaleTimeString(
                'en-US', 
                { year: 'numeric', month:'short', day:'numeric' }
            ) 
         )
    </script>";
 }

 function namespace_label($namespace){
     echo get_namespace_label($namespace,$namespace->name);
 }

 function get_namespace_label($namespace,$label = ''){
 	if(isset($label[0]) && $label[0] !='/'){
         $label = "/".$label;
     }

	if((!empty($label) && $label[strlen($label) - 1] != '/')){
		$label = $label."/";
	}

 	if($namespace->parent_id != 0 && $namespace->id != $namespace->parent_id){
         $nameSpaceParent = \App\Model\Namespaces::find($namespace->parent_id);
         return get_namespace_label($nameSpaceParent,$nameSpaceParent->name.$label);
 	}

 	return $label;
 }

/**
 *  Get live topic array
 *  ticket # 1427
 */
function getAgreementTopic($topic_num) {

    return Topic::where('topic_num', $topic_num)
                ->where('objector_nick_id', '=', NULL)
                ->where('go_live_time', '<=', time())
                ->orderBy('go_live_time', 'desc')
                ->first();
}
