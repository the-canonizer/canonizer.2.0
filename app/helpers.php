<?php

 function to_local_time($unixtime) {
			
	echo "<script>document.write((new Date($unixtime * 1000)).toLocaleString())</script>";
	
 }

