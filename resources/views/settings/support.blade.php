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
         <div class="">
		        <?php $lastsupportOrder = -1;?>
                @if(count($supportedTopic))
                 @foreach($supportedTopic as $data)
                   
                       <div><b>For Topic : {{ $data->topic->topic_name}}</b></div><br/>
               
					   <?php $topicSupport = $data->topic->Getsupports($data->topic->topic_num,$userNickname);?>
					   @foreach($topicSupport as $k=>$support)
					   
					     Camp : {{ $support->camp->camp_name }} <br/>
					   
					     Support Order : {{ $k+1 }} Choice <br/>
						 
						 Nickname : {{ $support->nickname->nick_name }} <br/>
					   <hr/>
					   
					   <?php if(isset($topic->topic_num) && $topic->topic_num==$data->topic_num) $lastsupportOrder++;
						   
					   ?>
					   
					   @endforeach
					   
					   
                 @endforeach
               @else
				  <h6> You didn't supported any camp yet.</h6>
               @endif			  

         </div>
        @if(isset($topic))
         <div id="myTabContent" class="add-nickname-section">  
                 <h5>Select Nick Name To Support {{ $parentcamp }} Camp </h5>
                <form action="{{ route('settings.support.add')}}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="topic_num" value="{{ $topic->topic_num }}">
					<input type="hidden" name="delegate_nick_name_id" value="{{ $delegate_nick_name_id }}">
					<input type="hidden" name="camp_num" value="{{ $camp->camp_num }}">
					<input type="hidden" name="lastsupport_rder" value="{{ $lastsupportOrder }}">
					<input type="hidden" name="userNicknames" value="{{ serialize($userNickname) }}">
					
					
					
                    <div class="row">
                        <div class="col-sm-6 margin-btm-1">
						<label for="camp_name"></label>
						<select name="nick_name" class="form-control">
							@foreach($nicknames as $nick)
							<option  value="{{ $nick->nick_name_id }}">{{ $nick->nick_name}}</option>
							@endforeach
							
						</select>
						 @if ($errors->has('nick_name')) <p class="help-block">{{ $errors->first('nick_name') }}</p> @endif
						 <a href="<?php echo url('settings/nickname');?>">Add new nickname </a>
						</div> 
                        <div class="col-sm-6 margin-btm-1">
                            <input type="checkbox" name="firstchoice" value="1"> <label for="namespace">Make This First Choice</label>
                            
                            @if ($errors->has('private')) <p class="help-block">{{ $errors->first('private') }}</p> @endif
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

