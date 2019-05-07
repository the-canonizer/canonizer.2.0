<?php include(app_path() . '/Library/wiki_parser/wikiParser.class.php'); ?>
<?php
function local_time($unixtime) {
			
	echo "<script>document.write((new Date($unixtime * 1000)).toLocaleString())</script>";
	
 } ?>
<?php $input = $data['statement'];
$currentLive = 0; 
$currentTime = time();
if(isset($data['objector_nick_id']) && $data['objector_nick_id'] !== NULL)
        $bgcolor ="rgba(255, 0, 0, 0.5);"; //red
else if($currentTime < $data['go_live_time'] && $currentTime >= $data['submit_time']) {
        $bgcolor ="rgba(255, 255, 0, 0.5);"; //yellow
}   
else if($currentLive!=1 && $currentTime >= $data['go_live_time']) {
        $currentLive = 1;
        $bgcolor ="rgba(0, 128, 0, 0.5);"; // green
} else {
        $bgcolor ="#4e4ef3;"; //blue
}


?>
<div class="form-group CmpHistoryPnl" style="background-color:#FFFF; width:100%;">
    <div class="statement">
        <?php
        $rootUrl = str_replace("/public", "", Request::root());
        /* $finalStatement  = $wiky->parse($input); 

          $finalStatement = str_replace("http://canonizer.com",$rootUrl,$finalStatement);
          $finalStatement = str_replace("http://www.canonizer.com",$rootUrl,$finalStatement);

          echo $finalStatement; */

        $WikiParser = new wikiParser;
        $output = $WikiParser->parse($input);
        $finalStatement = str_replace("http://canonizer.com", $rootUrl, $output);
        $finalStatement = str_replace("http://www.canonizer.com", $rootUrl, $finalStatement);
        $finalStatement = str_replace("https://www.canonizer.com", $rootUrl, $finalStatement);
        $finalStatement = str_replace("https://canonizer.com", $rootUrl, $finalStatement);
        echo $finalStatement;
        ?>

    </div><br/>
    <b>Note :</b> {{ $data['note'] }} <br/>				 
    <!--<b>Submitted on :</b> <span id="submitted_on"></span> <br/>-->
    <b>Submitter Nick Name :</b> {{ ($data['nickname'] != '') ? $data['nickname'] : 'N/A' }} <br/>
    <!--<b>Go live Time :</b> <span id="go_live_time"></span> <br/> -->
@if(isset($data['objector_nick_id']) && $data['objector_nick_id'] !=null)
<b>Object Reason :</b> <span style="word-wrap: break-word;">{{ $data['object_reason']}} </span><br/>	
<b>Objector Nick Name :</b> {{ $data['objector_nick_name'] }} <br/> 			  
@endif 

<script>
     $(document).ready(function () {
        var $unixtime = "{{ ($data['go_live_time']) }}";
        var goTime = new Date($unixtime * 1000).toLocaleString();
       // $('#go_live_time').html(goTime);
        
        var $submitted = "{{ $data['submit_time'] }}";
        var subTime = new Date($submitted * 1000).toLocaleString();
        //$('#submitted_on').html(subTime);
        
        
    })
    
</script>

</div> 


