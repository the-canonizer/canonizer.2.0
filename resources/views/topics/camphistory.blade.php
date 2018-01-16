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
        </div>
    </div>
</div>
<div>
    <div class="col-sm-12 margin-btm-2">
        <form action="{{ route('camp.save')}}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="topic_num" value="{{ $topic->topic_num }}">
            
               
			   <?php foreach($camps as $key=>$data) { 
			   
			   if($key==0 && $data->objector !== NULL)
				   $bgcolor ="rgba(255, 0, 0, 0.5);";
			   else if($key==1 && $data->objector == NULL ) {
				   $bgcolor ="rgba(0, 128, 0, 0.5);";
			   } else if($data->objector !== NULL || $data->objector !="") $bgcolor ="rgba(255, 255, 0, 0.5);";
			   else 
				   $bgcolor = "rgba(255, 0, 0, 0.5);";
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
				  <b>Submitted on :</b> {{ $data->submit_time }} <br/>
				  <b>Go live Time :</b> {{ $data->go_live_time}} <br/> 
               </div>    
               <div class="CmpHistoryPnl-footer">
				  <a class="btn btn-historysmt" href="#">Object Or Submit New Update</a> 
			    </div> 	
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

