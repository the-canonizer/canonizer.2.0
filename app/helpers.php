<?php

 function to_local_time($unixtime) {
			
	echo "<script>document.write((new Date($unixtime * 1000)).toLocaleString())</script>";
	
 }

 function get_view_version_url($url,$live_time) {
			
	return "<script>document.write($url+(new Date($unixtime * 1000)).toLocaleString())</script>";
	
 }