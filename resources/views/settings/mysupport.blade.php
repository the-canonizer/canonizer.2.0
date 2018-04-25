@extends('layouts.app')
@section('content')
<div class="page-titlePnl">
    <h1 class="page-title">Supported Camps</h1>
</div> 

@if(Session::has('error'))
<div class="alert alert-danger">
    <strong>Error! </strong>{{ Session::get('error')}}    
</div>
@endif

@if(Session::has('success'))
<div class="alert alert-success">
    <strong>Success! </strong>{{ Session::get('success')}}    
</div>
@endif



<div class="right-whitePnl">
   <div class="row justify-content-between">
    <div class="col-sm-12 margin-btm-2">
        <div class="well">
            <ul class="nav prfl_ul">
                <li><a class="" href="{{ route('settings')}}">Manage Profile info</a></li>
                <li class=""><a class="" href="{{ route('settings.nickname')}}" >Add & Manage Nick Names</a></li>
				<li class="active"><a class="" href="{{ route('settings.support')}}" >My Supports</a></li>
            </ul>
         <div class="SupportCmp">
		        
                @if(count($supportedTopic))
                 @foreach($supportedTopic as $data)
                   
                       <div class="SpCmpHd"><b>For Topic : {{ $data->topic->topic_name}}</b></div>
               		<div class="row">
					   <?php $topicSupport = $data->topic->Getsupports($data->id);?>
					   @foreach($topicSupport as $k=>$support)
					   <div class="col-sm-4">
                       <div class="SpCmpBDY">
					     <form action="{{ route('settings.support.delete')}}" id="support-{{$support->id}}" method="post">
						    <input type="hidden" name="_token" value="{{ csrf_token() }}">
							
							<input type="hidden" name="support_id" value="{{ $support->id }}">
							<input type="hidden" name="topic_support_id" value="{{ $support->topic_support_id }}">
							<input type="hidden" name="userNicknames" value="{{ serialize($userNickname) }}">
						  <button type="submit" class="btn-sptclose"><i class="fa fa-close"></i></button>
						 </form> 
					     <b>Camp :</b> {{ $support->camp->title }} <br/>
					   	 <b>Support Order :</b> {{ $k+1 }} Choice <br/>
						 <b>Nickname :</b> {{ $data->nickname->nick_name }} <br/>
                        @if($data->delegate_nick_id != 0) 						 
						 <b>Support Delegated To:</b> {{ $data->delegatednickname->nick_name}}
					    @endif
					   
					  
                       </div>
					   </div>
					   @endforeach
					</div>   
					   
                 @endforeach
               @else
				  <h6 style="margin-top:30px;margin-left:20px;"> You didn't supported any camp yet.</h6>
               @endif			  

         </div>
        
    </div>   
 </div></div>
</div>  <!-- /.right-whitePnl-->

    <script>
        $(document).ready(function () {
            $("#birthday").datepicker({
                changeMonth: true,
                changeYear: true
            });
        })
    </script>


    @endsection

