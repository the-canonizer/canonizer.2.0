<?php include(app_path() . '/Library/wiki_parser/wikiParser.class.php'); ?>
@extends('layouts.app')
@section('content')

<div class="camp top-head">
    <h3><b>Topic:</b>  {{ $topic->title}}</h3>
    <h3><b>Camp:</b> {!! $parentcamp !!}</h3>  
</div>


<div class="page-titlePnl">
    <h1 class="page-title">Camp Statement History</h1>
</div> 

@if(Session::has('error'))
<div class="alert alert-danger">
    <strong>Error! </strong>{{ Session::get('error')}}    
</div>
@endif

@if(Session::has('success'))
<div class="alert alert-success">
    <strong>Success! </strong>{{ Session::get('success')}} {{ to_local_time(Session::get('go_live_time')) }}   
</div>
@endif


<div class="right-whitePnl">
<div class="row">
    <div class="col-sm-12">
        <div class="notifySignPNL">
            <div class="col-sm-2">
                <div class="red-circle"></div>
                <div class="circle-txt">Objected</div>
            </div>
            <div class="col-sm-2">
                <div class="green-circle"></div>
                <div class="circle-txt">Live</div>
            </div>
            <div class="col-sm-2">
                <div class="yellow-circle"></div>
                <div class="circle-txt">Not Live</div>
            </div>
			<div class="col-sm-2">
                <div class="yellow-circle" style="background-color:#1514ed"></div>
                <div class="circle-txt">Old</div>
            </div>
        </div>
    </div>
</div>
<div>
    <div class="col-sm-12 margin-btm-2">
              
			   <?php 
			        if(!empty($statement)) { 
			            $currentLive = 0; 
						$currentTime = time();
			         foreach($statement as $key=>$data) { 
						   
						   if($data->objector_nick_id !== NULL)
							   $bgcolor ="rgba(255, 0, 0, 0.5);"; //red
						   else if($currentTime < $data->go_live_time && $currentTime >= $data->submit_time) {
							   $bgcolor ="rgba(255, 255, 0, 0.5);"; //yellow
						   }   
						   else if($currentLive!=1 && $currentTime >= $data->go_live_time) {
							   $currentLive = 1;
							   $bgcolor ="rgba(0, 128, 0, 0.5);"; // green
						   } else {
							   $bgcolor ="#4e4ef3;"; //blue
						   }
                   $input=$data->value;						   
			   ?>
			    <div class="form-group CmpHistoryPnl" style="background-color:{{ $bgcolor }}; width:100%;">
                  <div class="statement"><b>Statement :</b> 
				  <?php 
				              $rootUrl = str_replace("/public","",Request::root());
							  /*$finalStatement  = $wiky->parse($input); 
							  
							  $finalStatement = str_replace("http://canonizer.com",$rootUrl,$finalStatement);
							  $finalStatement = str_replace("http://www.canonizer.com",$rootUrl,$finalStatement);
							  
							  echo $finalStatement;*/
							  
							  $WikiParser = new wikiParser;
							  $output = $WikiParser->parse($input);
							  $finalStatement = str_replace("http://canonizer.com",$rootUrl,$output);
							  $finalStatement = str_replace("http://www.canonizer.com",$rootUrl,$finalStatement);
							  $finalStatement = str_replace("https://www.canonizer.com",$rootUrl,$finalStatement);
							  $finalStatement = str_replace("https://canonizer.com",$rootUrl,$finalStatement);
							  echo $finalStatement;
				   ?>
				  
				  </div><br/>
				  <b>Note :</b> {{ $data->note }} <br/>
				 
				  <b>Submitted on :</b> {{ to_local_time($data->submit_time) }} <br/>
				  <b>Submitter Nickname :</b> {{ isset($data->submitternickname->nick_name) ? $data->submitternickname->nick_name : 'N/A' }} <br/>
				  <b>Go live Time :</b> {{ to_local_time($data->go_live_time) }}; </script><br/> 
				  
				   @if($data->objector_nick_id !=null)
				  <b>Object Reason :</b> {{ $data->object_reason}} <br/>	
                  <b>Objector Nickname :</b> {{ $data->objectornickname->nick_name }} <br/> 			  
                  @endif 
				  
				 <div class="CmpHistoryPnl-footer">
				  <?php if($currentTime < $data->go_live_time && $currentTime >= $data->submit_time) { ?>
				    <a id="object" class="btn btn-historysmt" href="<?php echo url('manage/statement/'.$data->id.'-objection');?>">Object</a>
				  <?php } ?>	
					<a id="update" class="btn btn-historysmt" href="<?php echo url('manage/statement/'.$data->id);?>">Submit Statement Update Based On This</a>
                    <a id="version" class="btn btn-historysmt" href="<?php echo url('topic/'.$data->topic_num.'/'.$data->camp_num.'?asof=bydate&asofdate='.date('Y/m/d H:i:s',$data->submit_time));?>">View This Version</a>
				 
				 </div>
			    </div> 	
			   
			   <?php } 
				 } else {
					 
					 echo " No statement history available.";
				 }
			   ?>
        
</div>
</div>
</div>  <!-- /.right-whitePnl-->
    

    <script>
        $(document).ready(function () {
            $("#datepicker").datepicker({
                changeMonth: true,
                changeYear: true
            });
        })
    </script>


    @endsection

