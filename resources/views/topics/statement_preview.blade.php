<?php include(app_path() . '/Library/wiki_parser/wikiParser.class.php'); ?>
<?php $input = $data['statement'];
$currentLive = 0; 
$currentTime = time();
if($data['objector_nick_id'] !== NULL)
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
<div class="form-group CmpHistoryPnl" style="background-color:{{ $bgcolor }}; width:100%;">
    <div class="statement"><b>Statement :</b> 
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
    <!--b>Submitted on :</b> {{ to_local_time($data['submit_time']) }} <br/-->
    <b>Submitter Nickname :</b> {{ ($data['nickname'] != '') ? $data['nickname'] : 'N/A' }} <br/>
    <!--b>Go live Time :</b> {{ to_local_time($data['go_live_time']) }}<br/--> 
@if($data['objector_nick_id'] !=null)
<b>Object Reason :</b> {{ $data['object_reason']}} <br/>	
<b>Objector Nickname :</b> {{ $data['objector_nick_name'] }} <br/> 			  
@endif 

</div> 


