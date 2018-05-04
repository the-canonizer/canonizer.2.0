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

@if(Session::has('confirm'))

<div class="alert alert-success">
   <div style="text-align:center">
     <a href="{{$_SERVER['REQUEST_URI']}}"><input type="button" name="cancel" class="btn btn-login" value="Cancel"></a>
     <input type="button" id="confirm_submit" name="submit" class="btn btn-login" value="Submit">
   </div>    
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
		        <p style="margin-left: 15px;color:red">Note : To change support order of camp, drag & drop the camp box on your choice position. </p>
		        <?php $lastsupportOrder = 0;?>
                @if(count($supportedTopic))
                   
                       <div class="SpCmpHd"><b>Your supported camps for topic "{{ $supportedTopic->topic->topic_name}}"</b></div>
               		<div class="row column">
                       <?php  $topicSupport = $supportedTopic->topic->Getsupports($supportedTopic->topic_num,[$supportedTopic->nick_name_id]);?>
					   @foreach($topicSupport as $k=>$support)
                      
                            <div id="positions_{{ $support->support_id }}" class="SpCmpBDY support-sorter-element ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
                            <form action="{{ route('settings.support.delete')}}" id="support-{{$support->support_id}}" method="post">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                
                                <input type="hidden" id="support_id_{{ $support->support_id }}" name="support_id" value="{{ $support->support_id }}">
                                <input type="hidden" id="topic_num_{{ $support->support_id }}" name="topic_num" value="{{ $supportedTopic->topic_num }}">
                                
                                <input type="hidden" id="nick_name_id_{{ $support->support_id }}" name="nick_name_id" value="{{ $support->nick_name_id }}">
                            <button type="submit" id="submit_{{ $support->support_id }}" class="btn-sptclose"><i class="fa fa-close"></i></button>
                            </form> 
                            <b>Camp :</b> {{ $support->camp->camp_name }} <br/>
                             <b>Support Order :</b> <span class="support_order">{{ $support->support_order }}</span> Choice <br/>
                            <b>Nickname :</b> {{ $supportedTopic->nickname->nick_name }} <br/>
                            @if($support->delegate_nick_name_id != 0) 						 
                            <b>Support Delegated To:</b> {{ $support->delegatednickname->nick_name}}
                            @endif
                        
                        <?php if(isset($topic->topic_num) && $topic->topic_num==$supportedTopic->topic_num) $lastsupportOrder++;
                            
                        ?>
                        
                        </div>
                      
					   
					   @endforeach
                       
					</div>   
					   
                <script>
                $( function() {
                    $( ".column" ).sortable({
                        connectWith: ".column",
                        cursor: 'move',
                        opacity: 0.6,
                        update: function(event, ui) {
                            $.post('{{ route("settings.support-reorder") }}', $(this).sortable('serialize')+"&_token={{ csrf_token() }}&topicnum={{ $supportedTopic->topic_num }}", function(data) {
                                
                                if(!data.success) {
                                    alert('Whoops, something went wrong :/');
                                }
                                
                        }, 'json');

                        $( ".column" ).find('.support-sorter-element').each(function(i,v){
                                $(v).find('.support_order').text(i+1);
                            });

                        } 
                    });
                    
                });
                </script>
               @else
				  <h6 style="margin-top:30px;margin-left:20px;"> You didn't supported any camp yet for this topic.</h6>
               @endif			  


         </div>
        @if(isset($topic))
         <div id="myTabContent" class="add-nickname-section">  
                 <h5>Nick Name To Support {!! $parentcamp !!} Camp </h5>
                <form id="support_form" action="{{ route('settings.support.add')}}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" id="topic_num" name="topic_num" value="{{ $topic->topic_num }}">
					<input type="hidden" id="delegate_nick_name_id" name="delegate_nick_name_id" value="{{ $delegate_nick_name_id }}">
					<input type="hidden" id="camp_num" name="camp_num" value="{{ $camp->camp_num }}">
					<input type="hidden" id="lastsupport_order" name="lastsupport_order" value="{{ $lastsupportOrder }}">
					<input type="hidden" id="userNicknames" name="userNicknames" value="{{ serialize($userNickname) }}">
					<input type="hidden" id="support_id" name="support_id" value="{{ isset($supportedTopic->support_id) ? $supportedTopic->support_id : '0'}}">
					<input type="hidden" id="confirm_support" name="confirm_support" value="0">
					
					
                    <div class="row">
                        <div class="col-sm-6 margin-btm-1">
						<select name="nick_name" id="select_nick_name" class="form-control">
							
							@if(isset($supportedTopic->nickname->id))
								
							<option  value="{{ $supportedTopic->nickname->id }}">{{ $supportedTopic->nickname->nick_name}}</option>
							@else
							
							@foreach($nicknames as $nick)
							<option  value="{{ $nick->id }}">{{ $nick->nick_name}}</option>
							@endforeach
							
							@endif
						</select>
						 @if ($errors->has('nick_name')) <p class="help-block">{{ $errors->first('nick_name') }}</p> @endif
						 <a id="add_new_nickname" href="<?php echo url('settings/nickname');?>">Add new nickname </a>
						</div> 
                       
                    </div>
                    
                    <button type="submit" id="submit" class="btn btn-login">Confirm Support</button>
                    
                </form>  
        </div>
	  @endif	           
    </div>   
 </div></div>
</div>  <!-- /.right-whitePnl-->

    <script>
        $(document).ready(function () {
            $("#birthday").datepicker({
                changeMonth: true,
                changeYear: true
            });
			
			$('#confirm_submit').click(function() {
				
				$('#confirm_support').val('1');
				
				$( "#submit" ).trigger( "click" );
				
			})	
        })
    </script>


    @endsection

