@extends('layouts.app')
@section('content')
<div class="page-titlePnl">
    <h1 class="page-title">Supported Camps</h1>
</div> 

@if(!Session::has('success') && Session::has('warning'))
<div class="alert alert-danger">
    <strong>Warning! </strong>{{ Session::get('warning')}} 
</div>
@endif
<?php $removedCampList = array(); ?>
@if(Session::has('confirm') && Session::has('warning') && Session::get('confirm') !='samecamp')
	
<div class="row">
<div class="col-sm-6">
					<div class="row">
					
					 
					 <?php

					  if(isset($childSupport) && !empty($childSupport) ) { foreach($childSupport as $supportData) { 
					       $removedCampList[]=$supportData->camp->camp_num;
					 ?>
 					  <div class="col-sm-12">   
					   <div class="SpCmpBDY support-sorter-element ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
                            
							
                            <b>{{ $supportData->support_order }}. {{ $supportData->camp->camp_name }}</b><br/>
                            
                       
                        
                        </div>
						</div>
					 <?php } }?>	
					 <?php if(isset($parentSupport) && !empty($parentSupport) ) { foreach($parentSupport as $supportData) { 
					       $removedCampList[]=$supportData->camp->camp_num;
					 ?>
 					  <div class="col-sm-12">   
					   <div class="SpCmpBDY support-sorter-element ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
                            
							
                            <b>{{ $supportData->support_order }} . {{ $supportData->camp->camp_name }}</b><br/>
                            
                       
                        
                        </div>
						</div>
					 <?php } } ?>
                     				 
					</div></div>
</div>

<div class="alert alert-success">
   <div style="text-align:center">
     <a href="{{ route('settings.support')}}"><input type="button" name="cancel" class="btn btn-login" value="Cancel"></a>
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
                <li><a class="" href="{{ route('settings')}}">Manage Profile Info</a></li>
                <li class=""><a class="" href="{{ route('settings.nickname')}}" >Manage Nick Names</a></li>
				<li class="active"><a class="" href="{{ route('settings.support')}}" >My Supports</a></li>
                <li><a class="" href="{{ route('settings.algo-preferences')}}">Default Algorithm</a></li>
            </ul>
		<form id="support_form" action="{{ route('settings.support.add')}}" method="post">	
         <div class="SupportCmp">
		        <p style="margin-left: 15px;color:red">Note : To change support order of camp, drag & drop the camp box on your choice position. </p>
		        <?php $lastsupportOrder = 0;
				
				?>
                @if(count($supportedTopic))
                   
                   <div class="SpCmpHd"><b>Your supporting camps list for topic "{{ $supportedTopic->topic->topic_name}}"</b></div>
               		<div class="row" style="min-height:120px">
					@if(Session::has('confirm') && Session::get('confirm') == 'samecamp')
					 <div class="col-sm-6">
					<div class="row column">
					
                       <?php $k = 0; $topicSupport = $supportedTopic->topic->Getsupports($supportedTopic->topic_num,[$supportedTopic->nick_name_id]);

                       ?>
					   @foreach($topicSupport as $k=>$support)
					   <?php 
					   		
                            $camp = \App\Model\Camp::getLiveCamp($support->topic_num,$support->camp_num);
					   ?>
					  
                       <div class="col-sm-12">
                            <div id="positions_{{ $support->support_id }}" class="SpCmpBDY support-sorter-element ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
                            <!--<form action="{{ route('settings.support.delete')}}" id="support-{{$support->support_id}}" method="post">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <!--<input type="hidden" id="support_id_{{ $support->support_id }}" name="support_id" value="{{ $support->support_id }}">
                                <input type="hidden" id="topic_num_{{ $support->support_id }}" name="topic_num" value="{{ $supportedTopic->topic_num }}">
                                
                                <input type="hidden" id="nick_name_id_{{ $support->support_id }}" name="nick_name_id" value="{{ $support->nick_name_id }}">
                            <button type="submit" id="submit_{{ $support->support_id }}" class="btn-sptclose"><i class="fa fa-close"></i></button>
                            </form> -->
							<input type="hidden" class="final_support_order" name="support_order[{{$support->camp->camp_num}}]" id="support_order_{{ $support->support_id }}" value="{{ $support->support_order  }}">
                                
							<input type="hidden" name="camp[{{$support->camp->camp_num}}]" value="{{ $support->camp->camp_num }}">
							<input type="hidden" name="delegated[{{$support->camp->camp_num}}]" value="{{ $support->delegate_nick_name_id }}">
                            <b><span class="support_order"> {{ $support->support_order }} </span> . {{ $camp->camp_name }} </b><br/>
                             
                        
							<?php if(isset($topic->topic_num) && $topic->topic_num==$supportedTopic->topic_num) $lastsupportOrder++;
								
							?>
							<span class="remove_camp">X</span>
                        
                           </div>
					  </div>
                       				  
					   @endforeach
					  </div>
                     </div>					  
                    @else  				
					
					<div class="col-sm-6">
					 <div class="row column">
					 
                       <?php $key = 0; $topicSupport = $supportedTopic->topic->Getsupports($supportedTopic->topic_num,[$supportedTopic->nick_name_id]);

                       ?>
					   @foreach($topicSupport as $k=>$support)
					   
					   @if(!in_array($support->camp->camp_num,$removedCampList)) <?php $key = $key + 1; ?>
                       <div class="col-sm-12">
                            <div id="positions_{{ $support->support_id }}" class="SpCmpBDY support-sorter-element ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
                            <!--<form action="{{ route('settings.support.delete')}}" id="support-{{$support->support_id}}" method="post">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <!--<input type="hidden" id="support_id_{{ $support->support_id }}" name="support_id" value="{{ $support->support_id }}">
                                <input type="hidden" id="topic_num_{{ $support->support_id }}" name="topic_num" value="{{ $supportedTopic->topic_num }}">
                                
                                <input type="hidden" id="nick_name_id_{{ $support->support_id }}" name="nick_name_id" value="{{ $support->nick_name_id }}">
                            <button type="submit" id="submit_{{ $support->support_id }}" class="btn-sptclose"><i class="fa fa-close"></i></button>
                            </form> -->
							<input type="hidden" class="final_support_order" name="support_order[{{$support->camp->camp_num}}]" id="support_order_{{ $support->support_id }}" value="{{ $key  }}">
                                
							<input type="hidden" name="camp[{{$support->camp->camp_num}}]" value="{{ $support->camp->camp_num }}">
							<input type="hidden" name="delegated[{{$support->camp->camp_num}}]" value="{{ $support->delegate_nick_name_id }}">
                            <b><span class="support_order">{{ $support->support_order }} </span> . {{ $support->camp->camp_name }} </b><br/>
                             
                        
							<?php if(isset($topic->topic_num) && $topic->topic_num==$supportedTopic->topic_num) $lastsupportOrder++;
								
							?>
							<span class="remove_camp">X</span>
                        
                           </div>
					  </div>
                       @endif					  
					   @endforeach
					  
				  @if(Session::get('confirm') !='samecamp') 
					   <!-- current supporting camp detail -->
					<div class="col-sm-12">   
					   <div id="positions_0" class="SpCmpBDY support-sorter-element ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
                            <!--<form action="{{ route('settings.support.delete')}}" id="support-0" method="post">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                
                                <input type="hidden" id="support_id_0" name="support_id" value="0">
								<input type="hidden" id="support_id_0" name="support_id" value="0">
                                <input type="hidden" id="topic_num_0" name="topic_num" value="{{ $camp->topic_num }}">
                             
                                <input type="hidden" id="nick_name_id_0" name="nick_name_id" value="{{ $supportedTopic->nickname->id }}">
                            <button type="submit" id="submit_0" class="btn-sptclose"><i class="fa fa-close"></i></button>
                            </form> -->
							<input type="hidden" class="final_support_order" name="support_order[{{$camp->camp_num}}]" id="support_order_0" value="{{ $key + 1  }}">
                            
							<input type="hidden" name="camp[{{$camp->camp_num}}]" value="{{ $camp->camp_num }}">
							<input type="hidden" name="delegated[{{$camp->camp_num}}]" value="{{ $delegate_nick_name_id }}">
                            
                            <b><span class="support_order">{{ $key+1 }} </span> . {{ $camp->camp_name }} <br/></b>
                            <span class="remove_camp">X</span>
                            
                        	
                        <?php $lastsupportOrder++; ?>
                        
                        </div>
					</div>	
                    @endif 
					  </div>
					</div>   
					</div>
					@endif   
                <script>
                $( function() {
                    $( ".column" ).sortable({
                        connectWith: ".column",
                        cursor: 'move',
                        opacity: 0.6,
                        update: function(event, ui) {
                           /* $.post('{{ route("settings.support-reorder") }}', $(this).sortable('serialize')+"&_token={{ csrf_token() }}&topicnum={{ $supportedTopic->topic_num }}", function(data) {
                                
                                if(!data.success) {
                                    alert('Whoops, something went wrong :/');
                                }
                                
                        }, 'json');*/
                        $( ".column" ).find('.support-sorter-element').each(function(i,v){
                                $(v).find('.support_order').text(i+1);
								$(v).find('.final_support_order').val(i+1);
                            });

                        } 
                    });

                     $('.remove_camp').click(function(){
                        	$(this).parent(".support-sorter-element").remove();
                        	$( ".column" ).find('.support-sorter-element').each(function(i,v){
                                $(v).find('.support_order').text(i+1);
								$(v).find('.final_support_order').val(i+1);
                            });

                        })
                    
                });
                </script>
               @else

				   <div class="row">
				  	<div class="col-sm-12">   
					   <div id="positions_0" class="SpCmpBDY support-sorter-element ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
                          
							<input type="hidden" class="final_support_order" name="support_order[{{$camp->camp_num}}]" id="support_order_0 }}" value="{{ (isset($support->support_order)) ? $support->support_order + 1 : 1 }}">
                            
							<input type="hidden" name="camp[{{$camp->camp_num}}]" value="{{ $camp->camp_num }}">
							<input type="hidden" name="delegated[{{$camp->camp_num}}]" value="{{ $delegate_nick_name_id }}">
                            
                            <b><span class="support_order">{{ ++$lastsupportOrder }} </span> . {{ $camp->camp_name }} </b><br/>


                            
                        <?php $lastsupportOrder++; ?>
                        
                        </div>
					</div>
				 </div>	
               @endif			  


         </div>
		            
					
					 
        @if(isset($topic))
         <div id="myTabContent" class="add-nickname-section">  
                 <h5>Nick Name To Support {!! $parentcamp !!} </h5>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" id="topic_num" name="topic_num" value="{{ $topic->topic_num }}">
					<input type="hidden" id="delegate_nick_name_id" name="delegate_nick_name_id" value="{{ $delegate_nick_name_id }}">
					<input type="hidden" id="camp_num" name="camp_num" value="{{ $camp->camp_num }}">
					<input type="hidden" id="lastsupport_order" name="lastsupport_order" value="{{ $lastsupportOrder }}">
					<input type="hidden" id="userNicknames" name="userNicknames" value="{{ serialize($userNickname) }}">
					<input type="hidden" id="support_id" name="support_id" value="{{ isset($supportedTopic->support_id) ? $supportedTopic->support_id : '0'}}">
					<input type="hidden" id="confirm_support" name="confirm_support" value="0">
					<input type="hidden" id="removed_camp" name="removed_camp" value="">
                    
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
						 <?php if(count($nicknames) == 0) { ?>
						 <a id="add_new_nickname" href="<?php echo url('settings/nickname');?>">Add New Nick Name </a>
						 <?php } ?>
						</div> 
                       
                    </div>
                     @if(!Session::has('warning'))
                    <button type="submit" id="submit" class="btn btn-login">Submit</button>
				    <a  class="btn btn-login" href="<?php echo url('topic/'.$topic->topic_num.'/'.session('campnum'));?>">Cancel</a>
				    @else
					<div style="display:none">	
					<button type="submit" id="submit" class="btn btn-login"></button>	
					</div>
					@endif
                    
                
        </div>
		
	  @endif
     </form>  
     
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
<style>
	.remove_camp{
		    position: absolute;
		    top: 2px;
		    right: 5px;
		    cursor: pointer;
	}
</style>

{{ Session::forget('warning')}} 
{{ Session::forget('success') }} 
{{ Session::forget('confirm') }} 
{{ Session::forget('error') }}


@endsection

