@extends('layouts.app')
@section('content')

<div class="camp top-head">
    <h3><b>Topic:</b>  {{ $topic->title}}</h3>
    <h3><b>Camp:</b> {{ $parentcamp }}</h3>  
</div>


<div class="page-titlePnl">
    <h1 class="page-title">Camp Statement History</h1>
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
<div class="row col-sm-12 justify-content-between">
    <div class="col-sm-5 margin-btm-2">
        <form action="{{ route('camp.save')}}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="topic_num" value="{{ $topic->topic_num }}">
            
               
			   <?php foreach($statement as $key=>$data) { 
			   
			   if($key==0 && $data->objector !== NULL)
				   $bgcolor ="yellow";
			   else if($key==1 && $data->objector == NULL ) {
				   $bgcolor ="green";
			   } else if($data->objector !== NULL || $data->objector !="") $bgcolor ="yellow";
			   else 
				   $bgcolor = "red";
			   ?>
			    <div class="form-group" style="background-color:{{ $bgcolor }}">
                  
				 		  
				  Statement : {{ $data->value }} <br/>
				 
				  Note : {{ $data->note }} <br/>
				  Language : {{ $data->language }}<br/>
				 
				  Submitted on : {{ $data->submit_time }} <br/>
				  
				  Go live Time : {{ $data->go_live_time}} <br/>
				  
				  <a href="<?php echo url('manage/statement/'.$data->topic_num.'/'.$data->camp_num);?>">Object Or Submit Statement Update</a>
			   <hr/>
			    </div> 	
			   
			   <?php } ?>
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

