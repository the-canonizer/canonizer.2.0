@extends('layouts.app')
@section('content')

<div class="camp top-head">
    <h3><b>Topic:</b>  {{ $topic->title}}</h3>
    <h3><b>Camp:</b> {{ $parentcamp }}</h3>  
</div>


<div class="page-titlePnl">
    <h1 class="page-title">Camp History</h1>
</div> 

@if(Session::has('error'))
<div class="alert alert-danger">
    <strong>Error!</strong>{{ Session::get('error')}}    
</div>
@endif

@if(Session::has('success'))
<div class="alert alert-success">
    <strong>Success!</strong>{{ Session::get('success')}}    
</div>
@endif


<div class="right-whitePnl">
<div>
    <div class="col-sm-12 margin-btm-2">
        <form action="{{ route('camp.save')}}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="topic_num" value="{{ $topic->topic_num }}">
            
               
			   <?php if(!empty($camps)) { 
			            $currentLive = 0;
			            foreach($camps as $key=>$data) { 
			               
						   $currentTime = time();
						   
						   
						   if($data->objector !== NULL)
							   $bgcolor ="rgba(255, 0, 0, 0.5);"; //red
						   else if($currentTime < $data->go_live_time) {
							   $bgcolor ="rgba(255, 255, 0, 0.5);"; //yellow
						   }   
						   else if($currentLive!=1) {
							   $currentLive = 1;
							   $bgcolor ="rgba(0, 128, 0, 0.5);"; // green
						   } else {
							   $bgcolor ="rgba(255, 255, 0, 0.5);"; //yellow
						   }	   
						
			        	
			    ?>
			    <div class="form-group CmpHistoryPnl" style="background-color:{{ $bgcolor }}">
                <div>
                  <b>Camp Title :</b> {{ $data->title }} <br/>
				  <b>Camp Name :</b> {{ $data->title }} <br/>
				  <b>Keyword :</b> {{ $data->key_words }} <br/>
				  <b>Note :</b> {{ $data->note }} <br/>
				  <b>Language :</b> {{ $data->language }}<br/>
				  <b>URL :</b> {{ $data->url }} <br/>
				  <b>Nickname :</b> {{ $data->nickname->nick_name }} <br/>
				  <b>Submitted on :</b> {{ date('m-d-Y H:i:s',$data->submit_time) }} <br/>
				  <b>Go live Time :</b> {{ date('m-d-Y H:i:s',$data->go_live_time)}} <br/> 
               </div>    
               <div class="CmpHistoryPnl-footer">
				  <a class="btn btn-historysmt" href="<?php echo url('manage/camp/'.$data->id);?>">Object Or Submit New Update</a> 
			    </div> 	
			   </div>
			   <?php } 
			    } else {
					
					echo " No camp history available.";
				}
			   ?>
        </form>
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

