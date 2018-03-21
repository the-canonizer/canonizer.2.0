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
                <li><a class="" href="{{ route('settings.algo-preferences')}}">Default Algorithm</a></li>
            </ul>
         <div class="SupportCmp">
		        
                @if(count($supportedTopic))
                 @foreach($supportedTopic as $data)
                   
                       <div class="SpCmpHd"><b>For Topic : {{ $data->topic->topic_name}}</b></div>
               		<div class="row column{{ $data->topic_num }}">
					   <?php $topicSupport = $data->topic->Getsupports($data->topic_num,$userNickname);?>
					   @foreach($topicSupport as $k=>$support)
					   <div id="positions_{{ $support->support_id }}" class="SpCmpBDY support-sorter-element ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
					     <form action="{{ route('settings.support.delete')}}" id="support-{{$support->support_id}}" method="post">
						    <input type="hidden" name="_token" value="{{ csrf_token() }}">
							
							<input type="hidden" name="support_id" value="{{ $support->support_id }}">
							<input type="hidden" name="topic_num" value="{{ $data->topic_num }}">
							<input type="hidden" name="userNicknames" value="{{ serialize($userNickname) }}">
						  <button type="submit" class="btn-sptclose"><i class="fa fa-close"></i></button>
						 </form> 
					     <b>Camp :</b> {{ $support->camp->title }} <br/>
					   	 <!--<b>Support Order :</b> {{ $k+1 }} Choice <br/>-->
						 <b>Nickname :</b> {{ $data->nickname->nick_name }} <br/>
                        @if($data->delegate_nick_id != 0) 						 
						 <b>Support Delegated To:</b> {{ $data->delegatednickname->nick_name}}
					    @endif
                       </div>
					   @endforeach
                    </div>   
                    <script>
                    $( function() {
                        $( ".column{{ $data->topic_num }}" ).sortable({
                            connectWith: ".column",
                            cursor: 'move',
                            opacity: 0.6,
                            update: function(event, ui) {
                                $.post('{{ route("settings.support-reorder") }}', $(this).sortable('serialize')+"&_token={{ csrf_token() }}", function(data) {
                                    if(!data.success) {
                                        alert('Whoops, something went wrong :/');
                                    }
                            }, 'json');
                            } 
                        });
                        
                    });
                    </script>
					   
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

