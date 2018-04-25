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
		        <?php $lastsupportOrder = 0;?>
                @if(count($supportedTopic))
                   
                       <div class="SpCmpHd"><b>Your supported camps for topic "{{ $supportedTopic->topic->topic_name}}"</b></div>
               		<div class="row">
					   <?php $topicSupport = $supportedTopic->topic->Getsupports($supportedTopic->id);?>
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
						 <b>Nickname :</b> {{ $supportedTopic->nickname->nick_name }} <br/>
                        @if($supportedTopic->delegate_nick_id != 0) 						 
						 <b>Support Delegated To:</b> {{ $supportedTopic->delegatednickname->nick_name}}
					    @endif
					   
					   <?php if(isset($topic->topic_num) && $topic->topic_num==$supportedTopic->topic_num) $lastsupportOrder++;
						   
					   ?>
					  
                       </div>
					   
					   </div>
					   @endforeach
					</div>   
					   
                
               @else
				  <h6 style="margin-top:30px;margin-left:20px;"> You didn't supported any camp yet for this topic.</h6>
               @endif			  

         </div>
        @if(isset($topic))
         <div id="myTabContent" class="add-nickname-section">  
                 <h5>Nick Name To Support {!! $parentcamp !!} Camp </h5>
                <form action="{{ route('settings.support.add')}}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="topic_num" value="{{ $topic->topic_num }}">
					<input type="hidden" name="delegate_nick_name_id" value="{{ $delegate_nick_name_id }}">
					<input type="hidden" name="camp_num" value="{{ $camp->camp_num }}">
					<input type="hidden" name="lastsupport_rder" value="{{ $lastsupportOrder }}">
					<input type="hidden" name="userNicknames" value="{{ serialize($userNickname) }}">
					<input type="hidden" name="topic_support_id" value="{{ isset($supportedTopic->id) ? $supportedTopic->id : '0'}}">
					
					
					
                    <div class="row">
                        <div class="col-sm-6 margin-btm-1">
						<select name="nick_name" class="form-control">
							
							@if(isset($supportedTopic->nickname->nick_name_id))
								
							<option  value="{{ $supportedTopic->nickname->nick_name_id }}">{{ $supportedTopic->nickname->nick_name}}</option>
							@else
							
							@foreach($nicknames as $nick)
							<option  value="{{ $nick->nick_name_id }}">{{ $nick->nick_name}}</option>
							@endforeach
							
							@endif
						</select>
						 @if ($errors->has('nick_name')) <p class="help-block">{{ $errors->first('nick_name') }}</p> @endif
						 <a href="<?php echo url('settings/nickname');?>">Add new nickname </a>
						</div> 
                        <div class="col-sm-6 margin-btm-1" style="padding-top:6px;">
                            <input type="checkbox" name="firstchoice" value="1"> <label for="namespace">Make This First Choice</label>
                            
                            @if ($errors->has('firstchoice')) <p class="help-block">{{ $errors->first('firstchoice') }}</p> @endif
                        </div> 
                    </div>
                    
                    <button type="submit" class="btn btn-login">Confirm Support</button>
                    
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
        })
    </script>


    @endsection

