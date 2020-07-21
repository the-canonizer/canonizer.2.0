<?php

 function to_local_time($unixtime) {
			
	echo "<script>document.write((new Date($unixtime * 1000)).toLocaleString())</script>";
	
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
 	if($namespace->parent_id != 0){
 		  	return get_namespace_label($namespace->parentNamespace,$namespace->parentNamespace->name.$label);
 	}else{
 		return $label;
 	}
 	
 	
 }
