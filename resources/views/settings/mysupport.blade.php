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
                <li class=""><a class="" href="{{ route('settings')}}">Profile Info</a></li>
                <li class=""><a class="" href="{{ route('settings.sociallinks')}}">Social Oauth Verification</a></li>                
                <li><a class="" href="{{ route('settings.changepassword')}}">Change Password</a></li>
                <li><a class="" href="{{ route('settings.nickname')}}" >Nick Names</a></li>
                <li class="active"><a class="" href="{{ route('settings.support')}}" >Supported Camps</a></li>
                <!-- <li><a class="" href="{{ route('settings.algo-preferences')}}">Default Algorithm</a></li> -->
                <li class=""><a class="" href="{{ route('settings.blockchain')}}">Crypto Verification (was Metamask Account)</a></li>
            </ul>
         <div class="SupportCmp">
		        <p style="margin-left: 15px;color:red">Note : To change support order of camp, drag & drop the camp box on your choice position. </p>
                @if(count($supportedTopic))
                 @foreach($supportedTopic as $data)
                 <?php  
                     $link = \App\Model\Camp::getTopicCampUrl($data->topic->topic_num,1);
                 ?>
                       <div class="SpCmpHd"><b>For Topic : <a href="<?php echo $link; ?>">{{ $data->topic->topic_name}} </a> </b></div>
               		<div class="row column{{ $data->topic_num }}" style="padding:10px 15px;">
					   <?php $topicSupport = $data->topic->Getsupports($data->topic_num,$userNickname,['nofilter'=>true]);?>
                       
					   @foreach($topicSupport as $k=>$support)
                        <?php if($support->delegate_nick_name_id == 0) {
                            $camp = \App\Model\Camp::getLiveCamp($support->topic_num,$support->camp_num,['nofilter'=>true]);

                           
                         ?>
					   <div id="positions_{{ $support->support_id }}" class="SpCmpBDY support_camp support-sorter-element ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
					     <form onsubmit="return confirm('Do you really want to remove this support ?');" action="{{ route('settings.support.delete')}}" id="support-{{$support->support_id}}" method="post">
						    <input type="hidden" name="_token" value="{{ csrf_token() }}">

							<input type="hidden" id="support_id_{{ $support->support_id }}" name="support_id" value="{{ $support->support_id }}">
							<input type="hidden" id= "topic_num_{{ $support->support_id }}" name="topic_num" value="{{ $data->topic_num }}">
							<input type="hidden" id= "nick_name_id_{{ $support->support_id }}" name="nick_name_id" value="{{ $support->nick_name_id }}">
						  <button type="submit" id="submit_{{ $support->support_id }}" class="btn-sptclose" title="Remove Support"><i class="fa fa-close"></i></button>
						 </form>
						    <?php  
                     $link = \App\Model\Camp::getTopicCampUrl($data->topic_num,isset($support->camp->camp_num) ? $support->camp->camp_num : '1' );

                 ?>
					     <b><span class="support_order">{{ $support->support_order }}</span> . <a style="text-decoration: underline; color: blue;" href="<?php echo $link; ?>"> {{ isset($camp->camp_name) ? $camp->camp_name : '' }}  </a> <br/>
					   	 </b>
                       </div>
						<?php } else { 
                            $topic = \App\Model\Topic::where('topic_num','=',$data->topic_num)->latest('submit_time')->get();
                            $topic_name_space_id = isset($topic[0]) ? $topic[0]->namespace_id:1;
                            $delegatedNickDetail  = $delegatedNick->getNickName($support->delegate_nick_name_id);
                            $nickName = \App\Model\Nickname::find($support->delegate_nick_name_id);
                            $supported_camp = $nickName->getDelegatedSupportCampList($topic_name_space_id,$support->nick_name_id,['nofilter'=>true]);
                            $supported_camp_list = $nickName->getSupportCampListNames($supported_camp,$data->topic_num);
						 ?>
						 <div id="positions" class="SpCmpBDY delegate_support support-sorter-element ui-widget">
					        <form onsubmit="return confirm('Do you really want to remove this support ?');" action="{{ route('settings.support.delete')}}" id="support-{{$support->support_id}}" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" id="support_id_{{ $support->support_id }}" name="support_id" value="{{ $support->support_id }}">
                            <input type="hidden" id= "topic_num_{{ $support->support_id }}" name="topic_num" value="{{ $data->topic_num }}">
                            <input type="hidden" id= "nick_name_id_{{ $support->support_id }}" name="nick_name_id" value="{{ $support->nick_name_id }}">
                            <input type="hidden" id= "delegate_nick_name_id_{{ $support->support_id }}" name="delegate_nick_name_id" value="{{ $support->delegate_nick_name_id }}">
                          <button type="submit" id="submit_{{ $support->support_id }}" class="btn-sptclose" title="Remove Support"><i class="fa fa-close"></i></button>
                         </form>
						  Support delegated to {{$delegatedNickDetail->nick_name }}
                          
						</div>
              <?php if($supported_camp_list != '' && $supported_camp_list!= null){ ?>
                  <span style="font-size:10px; width:100%; float:left;"><b>Supported camp list</b> : {!!$supported_camp_list !!}</span>
              <?php } ?>
						 <?php
						 break;
						 } ?>
					   @endforeach
                    </div>
                    <script>
                    $( function() {
                        $( ".column{{ $data->topic_num }}" ).sortable({
                            connectWith: ".column",
                            cursor: 'move',
                            opacity: 0.6,
                            update: function(event, ui) {
                                $.post('{{ route("settings.support-reorder") }}', $(this).sortable('serialize')+"&_token={{ csrf_token() }}&topicnum={{ $data->topic_num }}", function(data) {
                                    if(!data.success) {
                                        alert('Whoops, something went wrong :/');
                                    }

                            }, 'json');
                            $( ".column{{ $data->topic_num }}" ).find('.support-sorter-element').each(function(i,v){
                                $(v).find('.support_order').text(i+1);
                            });
                            }
                        });


                    });
                    </script>

                 @endforeach
               @else
				  <h6 style="margin-top:30px;margin-left:20px;"> You currently don't support any camps.</h6>
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
