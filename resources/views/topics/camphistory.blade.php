@extends('layouts.app')
@section('content')

<div class="camp top-head">
    <h3><b>Topic:</b>  {{ $topic->title}}</h3>
    <h3><b>Camp:</b> {{ $topic->camp_name }}</h3>  
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
<div class="row col-sm-12 justify-content-between">
    <div class="col-sm-5 margin-btm-2">
        <form action="{{ route('camp.save')}}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="topic_num" value="{{ $topic->topic_num }}">
            
               
			   <?php foreach($camp as $key=>$data) { 
			   
			   if($key==0 && $data->objector !== NULL)
				   $bgcolor ="yellow";
			   else if($key==1 && $data->objector == NULL ) {
				   $bgcolor ="green";
			   } else if($data->objector !== NULL || $data->objector !="") $bgcolor ="yellow";
			   else 
				   $bgcolor = "red";
			   ?>
			    <div class="form-group" style="background-color:{{ $bgcolor }}">
                  Camp Title : {{ $data->title }} <br/>
				  Camp Name : {{ $data->title }} <br/>
				  Keyword : {{ $data->key_words }} <br/>
				  Note : {{ $data->note }} <br/>
				  Language : {{ $data->language }}<br/>
				  URL " {{ $data->url }} <br/>
				  Nickname : {{ $data->nickname->nick_name }} <br/>
				  Submitted on : {{ $data->submit_time }} <br/>
				  
				  Go live Time : {{ $data->go_live_time}} <br/>
				  
				  <button>Object Or Submit New Update</button>
			   <hr/>
			    </div> 	
			   
			   <?php } ?>
			   
           			

            <button type="submit" class="btn btn-login">Submit Update</button>
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

