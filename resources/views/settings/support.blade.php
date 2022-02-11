@extends('layouts.app')
@section('content')

<?php $topic_camp_link = \App\Model\Camp::getTopicCampUrl($topic->topic_num,1); ?>
<div class="page-titlePnl">
    <h1 class="page-title">Supported Camps</h1>
</div> 
<div class="camp top-head">
	<h3>
		<b>Topic:</b> 
		{{ isset($topic->title)? $topic->title :''}}
	</h3>
	<h3>
		<b>Camp:</b>
		{!! $parentcamp !!}
	</h3> 
</div>
@if(!Session::has('success') && Session::has('warning') && !Session::has('warningDelegate'))
<div class="alert alert-danger">
    <strong>Warning! </strong>{{ Session::get('warning')}} 
</div>
@endif

@if(Session::has('confirm') && Session::get('confirm') =='samecamp')
<!--div class="alert alert-danger">
    <strong>Warning! </strong>You are already supporting this camp. You can't submit support again.
</div-->	
@endif

@if(Session::has('warningDelegate'))
<div class="alert alert-danger">
    <strong>Warning! </strong>{{ Session::get('warningDelegate')}} 
</div>
@endif

<?php $removedCampList = array(); ?>
@if(Session::has('confirm') && Session::has('warning') && Session::get('confirm') !='samecamp')
	
<div class="row">
<div class="col-sm-6">
					<div class="row">				
					 
					 <?php


					  if(isset($childSupport) && !empty($childSupport) ) {
					   foreach($childSupport as $supportData) { 
					       $removedCampList[]=$supportData->camp->camp_num;
						   $livecamp = \App\Model\Camp::getLiveCamp($supportData->topic_num,$supportData->camp_num,['nofilter'=>true]);
					 ?>
 					  <div class="col-sm-12 column">   
					   <div class="SpCmpBDY  support-sorter-element-child ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
							
                            <b>{{ $supportData->support_order }}. {{ $livecamp->camp_name }}</b><br/>                            
                       
                        
                        </div>
						</div>
					 <?php } }?>	
					 <?php if(isset($parentSupport) && !empty($parentSupport) ) { 
					 	foreach($parentSupport as $supportData) { 
					       $removedCampList[]=$supportData->camp->camp_num;
						   $livecamp = \App\Model\Camp::getLiveCamp($supportData->topic_num,$supportData->camp_num,['nofilter'=>true]);
					
					 ?>
 					  <div class="col-sm-12">   
					   <div class="SpCmpBDY support-sorter-element-parent ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
                            
							
                            <b>{{ $supportData->support_order }} . {{ $livecamp->camp_name }}</b><br/>
                            
                       
                        
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
               <li class=""><a class="" href="{{ route('settings')}}">Profile Info</a></li>
                <li class=""><a class="" href="{{ route('settings.sociallinks')}}">Social Oauth Verification</a></li>                
                <li><a class="" href="{{ route('settings.changepassword')}}">Change Password</a></li>
                <li><a class="" href="{{ route('settings.nickname')}}" >Nick Names</a></li>
                <li class="active"><a class="" href="{{ route('settings.support')}}" >Supported Camps</a></li>
                <!-- <li><a class="" href="{{ route('settings.algo-preferences')}}">Default Algorithm</a></li> -->
                <li ><a class="" href="{{ route('settings.blockchain')}}">Crypto Verification (was MetaMask Account)</a></li>
            </ul>
		<form id="support_form" action="{{ route('settings.support.add')}}" method="post">	
			<div class="SupportCmp" id="draggable-area">
				<p style="margin-left: 15px;color:red">Note : To change support order of camp, drag & drop the camp box on your choice position. </p>
				<?php $lastsupportOrder = 0;?>
				@if(isset($supportedTopic) && isset($supportedTopic->topic_num) && count($supportedTopic))
					<div class="SpCmpHd col-sm-12"><b>Your supporting camps list for topic "{{ $supportedTopic->topic->topic_name}}"</b></div>
					<div class="quick-filter col-sm-12">
						<label class="quick-actn-lbl" >Quick Actions:</label>
						<span class="btn btn-sm btn-info"><input type="checkbox" name="remove_all" id="remove_all" />Remove All</span>
						<span class="btn btn-sm btn-secondary" id="undo_all">Clear All Changes</span>
					</div>
					<div class="row" style="min-height:120px">
					@if(Session::has('confirm') && Session::get('confirm') == 'samecamp')	
						<div class="col-sm-6">
						<div class="row column">
					<?php $k = 0; $topicSupport = $supportedTopic->topic->Getsupports($supportedTopic->topic_num,[$supportedTopic->nick_name_id],['nofilter'=>true]);
					?>
						
						@foreach($topicSupport as $k=>$support)
						<?php 
							
							$livecamp = \App\Model\Camp::getLiveCamp($support->topic_num,$support->camp_num,['nofilter'=>true]);
							$url =  \App\Model\Camp::getTopicCampUrl($support->topic_num,$support->camp_num);
						?>
						
						<div class="col-sm-12 column sortCamp">
							<div id="positions_{{ $support->support_id }}" class="SpCmpBDY  support-sorter-element ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
							
							<input type="hidden" class="final_support_order" name="support_order[{{$support->camp->camp_num}}]" id="support_order_{{ $support->support_id }}" value="{{ $support->support_order  }}">
								
							<input type="hidden" name="camp[{{$support->camp->camp_num}}]" value="{{ $support->camp->camp_num }}">
							<input type="hidden" name="delegated[{{$support->camp->camp_num}}]" value="{{ $support->delegate_nick_name_id }}">
							<b><a class="mr-5" href="<?= $url; ?>"><span class="support_order"> {{ $support->support_order }} </span> . {{ $livecamp->camp_name }} </a></b><br/>
								
						
							<?php if(isset($topic->topic_num) && isset($supportedTopic->topic_num) &&  $topic->topic_num==$supportedTopic->topic_num) $lastsupportOrder++;
								
							?>
							<span rel="{{ $support->camp->camp_num }}" order="{{ $support->support_order }}" delegated="{{ $support->delegate_nick_name_id }}" class="x-btn remove_camp">X</span>
						
							</div>
						</div>
										
						@endforeach
						</div>
						</div>					  
					@else  
										
					
					<div class="col-sm-6">
						<div class="row column">
						<?php $key = 0; 
							$topicSupport = $supportedTopic->topic->Getsupports($supportedTopic->topic_num,[$supportedTopic->nick_name_id],['nofilter'=>true]);
						?>

						@foreach($topicSupport as $k=>$support)					   
						<?php 
							$liveCamp = \App\Model\Camp::getLiveCamp($support->topic_num,$support->camp_num,['nofilter'=>true]);
							$url =  \App\Model\Camp::getTopicCampUrl($support->topic_num,$support->camp_num);
						?>
						@if(!in_array($support->camp->camp_num,$removedCampList)) <?php $key = $key + 1; ?>
						
						<div class="col-sm-12 column sortCamp">
							<div id="positions_{{ $support->support_id }}" class="SpCmpBDY  support-sorter-element ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
							
							<input type="hidden" class="final_support_order" name="support_order[{{$support->camp->camp_num}}]" id="support_order_{{ $support->support_id }}" value="{{ $key  }}">
								
							<input type="hidden" name="camp[{{$support->camp->camp_num}}]" value="{{ $support->camp->camp_num }}">
							<input type="hidden" name="delegated[{{$support->camp->camp_num}}]" value="{{ $support->delegate_nick_name_id }}">
							<b><a class="mr-5" href="<?= $url; ?>"><span class="support_order">{{ $key }} </span> . {{ $liveCamp->camp_name }} </b></a><br/>
								
						
							<?php if(isset($topic->topic_num) && isset($supportedTopic->topic_num) &&  $topic->topic_num==$supportedTopic->topic_num) $lastsupportOrder++;
								
							?>
							<span rel="{{$support->camp->camp_num}}" order="{{ $key }}" delegated="{{ $support->delegate_nick_name_id }}" class="x-btn remove_camp">X</span>
						
							</div>
						</div>
						@endif					  
						@endforeach
						
					@if(Session::get('confirm') !='samecamp' && !Session::has('warningDelegate')) 
					 <!-- CASE: When adding support to new camp  and already  have other supported List  --> 
					<?php $lastsupportOrder++;
						$url =  \App\Model\Camp::getTopicCampUrl($topic->topic_num,session('campnum'));
					?>
					<div class="col-sm-12 column sortCamp">   
						<div id="positions_0" class="SpCmpBDY  support-sorter-element ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
							
							<input type="hidden" class="final_support_order" name="support_order[{{$camp->camp_num}}]" id="support_order_0" value="{{ $key + 1  }}">
							
							<input type="hidden" name="camp[{{$camp->camp_num}}]" value="{{ $camp->camp_num }}">
							<input type="hidden" name="delegated[{{$camp->camp_num}}]" value="{{ $delegate_nick_name_id }}">
							
							<b><a class="mr-5" href="<?= $url; ?>"><span class="support_order">{{ $key+1}} </span> . {{ $camp->camp_name }} </a></b><br/>
							<span rel="{{$camp->camp_num}}" order="{{ $key + 1  }}" delegated="{{ $delegate_nick_name_id }}" class="x-btn remove_camp">X</span>                        
						</div>
					</div>	
					@endif 
						</div>
					</div>   
					</div>
					@endif   
				
				@else
				     <!-- CASE: adding support to camp and already do not have any other support --> 
					<?php
					   $url =  \App\Model\Camp::getTopicCampUrl($topic->topic_num,session('campnum'));
					?>
					<div class="row column">
						<div class="col-sm-12 column">   
							<div id="positions_0" class="SpCmpBDY  support-sorter-element ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
								<input type="hidden" class="final_support_order" name="support_order[{{$camp->camp_num}}]" id="support_order_0" value="{{ (isset($support->support_order)) ? $support->support_order + 1 : 1 }}">
								<input type="hidden" name="camp[{{$camp->camp_num}}]" value="{{ $camp->camp_num }}">
								<input type="hidden" name="delegated[{{$camp->camp_num}}]" value="{{ $delegate_nick_name_id }}">
								<b><a class="mr-5" href="<?= $url; ?>"><span class="support_order">{{ ++$lastsupportOrder }} </span> . {{ $camp->camp_name }} </a></b><br/>
								<span rel="{{ $camp->camp_num }}" order="{{ (isset($support->support_order)) ? $support->support_order + 1 : 1 }}" delegated="{{ $delegate_nick_name_id }}"  class="x-btn remove_camp">X</span>
								<?php $lastsupportOrder++; ?>							
							</div>
						</div>
					</div>	
				@endif
			</div>	
						
			@if(isset($topic))
			<div id="myTabContent" class="add-nickname-section">  
					<h5>Nick Name To Support Above Camps </h5>
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" id="topic_num" name="topic_num" value="{{ $topic->topic_num }}">
					<input type="hidden" id="delegate_nick_name_id" name="delegate_nick_name_id" value="{{ $delegate_nick_name_id }}">
					<input type="hidden" id="camp_num" name="camp_num" value="{{ $camp->camp_num }}">
					<input type="hidden" id="lastsupport_order" name="lastsupport_order" value="{{ $lastsupportOrder }}">
					<input type="hidden" id="userNicknames" name="userNicknames" value="{{ serialize($userNickname) }}">
					<input type="hidden" id="support_id" name="support_id" value="{{ isset($supportedTopic->support_id) ? $supportedTopic->support_id : '0'}}">
					<input type="hidden" id="confirm_support" name="confirm_support" value="0">
					@if(count($removedCampList) > 0)
						@foreach($removedCampList as $cmp)
						<input type="hidden" name="removed_camp[]" value="{{$cmp}}">
						@endforeach
						@endif
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
							<p style="color:red" class="help-block">Note:You have not yet added a nick name. A public or private nick name must be added then selected here when contributing.</p>
							<a id="add_new_nickname" href="<?php echo url('settings/nickname');?>">Add New Nick Name </a>
							<?php } ?>
						</div> 
						
					</div>	

						<?php  
						$link = \App\Model\Camp::getTopicCampUrl($topic->topic_num,session('campnum'));
						?>

					<!-- button Section -->
						<button type="submit" id="submit"  class="btn btn-success">Submit</button>
							<a  class="btn btn-login" href="<?php echo $link; ?>">Cancel</a>						
					<!-- ends here -->
					
					
				
			</div>		
			@endif
     </form>  
     
    </div>   
 </div></div>
</div>  <!-- /.right-whitePnl-->
 <script>
$('#widget').draggable();
 	 jQuery.fn.preventDoubleSubmission = function() {
              $(this).on('submit',function(e){
                var $form = $(this);

                if ($form.data('submitted') === true) {
                  // Previously submitted - don't submit again
                  e.preventDefault();
                } else {
                  // Mark it so that the next submit can be ignored
                  $form.data('submitted', true);
                }
              });

              // Keep chainability
              return this;
        };
        $('form').preventDoubleSubmission();
		var clone = $('#support_form').clone(true);

        $( function() {
            $( ".column.sortCamp" ).sortable({
                connectWith: ".column",
                cursor: 'move',
                opacity: 0.6,
				axis: "y",
				scroll: false,
				containment: "#draggable-area",
				
                update: function(event, ui) {
                $( ".column" ).find('.support-sorter-element').each(function(i,v){
                        $(v).find('.support_order').text(i+1);
						$(v).find('.final_support_order').val(i+1);
                    });
                $( ".column" ).find('.support-sorter-element-child').each(function(i,v){
                        $(v).find('.support_order').text(i+1);
						$(v).find('.final_support_order').val(i+1);
                    });
                 $( ".column" ).find('.support-sorter-element-parent').each(function(i,v){
                        $(v).find('.support_order').text(i+1);
						$(v).find('.final_support_order').val(i+1);
                    });
					activateClearBtn();
                } 
            });

			$('.SupportCmp').delegate('.x-btn','click',function(){
				var camp = $(this).attr('rel');
				//$('#submit').show();
				removeCamp(camp,this);    
            });

			$('.SupportCmp').delegate('.undo_camp','click',function(){
				var camp = $(this).attr('rel');	
				var order = $(this).attr('order');	
				var delegated = $(this).attr('delegated');				
				undoCampRemove(camp,this,order,delegated);

			})

			$('#remove_all').click(function(){
				$('.support-sorter-element').each(function(){
					var camp = $(this).find('.remove_camp').attr('rel');
					var order = $(this).find('.remove_camp').attr('order');
					var delegated = $(this).find('.remove_camp').attr('delegated');
					var ref = $(this).find('.remove_camp');
					if($('#remove_all').is(':checked')) { 
						removeCamp(camp, ref);
					}else{
						undoCampRemove(camp,ref,order,delegated);
					}
				})
				
			});

			$('.SupportCmp').delegate('.undo_all','click',function(){
				window.location.reload();
			})
            
        });
        
		function removeCamp(camp, ref){
			$('#myTabContent').append('<input type="hidden"  name="removed_camp[]" value="'+camp+'">');
			$('input[name="support_order['+camp+']"]').remove();
			$('input[name="camp['+camp+']"]').remove();
			$('input[name="delegated['+camp+']"]').remove();
			//$(this).parent(".support-sorter-element").remove();
			$(ref).parent(".support-sorter-element").addClass('greay-out');
			$(ref).parent(".support-sorter-element").find('.remove_camp').html('undo');
			$(ref).parent(".support-sorter-element").find('.remove_camp').removeClass('x-btn');
			$(ref).parent(".support-sorter-element").find('.remove_camp').addClass('undo_camp');
			$( ".column" ).find('.x-btn').each(function(i,v){ 
				$(v).parent(".support-sorter-element").find('.support_order').text(i+1);
				$(v).parent(".support-sorter-element").find('.final_support_order').val(i+1);
			});
			activateClearBtn();
		}
		function undoCampRemove(camp,ref,order,delegated){
			$(ref).parent(".support-sorter-element").removeClass('greay-out');
			$(ref).parent(".support-sorter-element").find('.remove_camp').html('X');
			$(ref).parent(".support-sorter-element").find('.remove_camp').removeClass('undo_camp');
			$(ref).parent(".support-sorter-element").find('.remove_camp').addClass('x-btn');
			$('input[name="removed_camp[]"]').each(function() {
				if($(this).val() == camp){
					$(this).remove();
				}
			});

			$(ref).parent(".support-sorter-element").append('<input type="hidden"  name="support_order['+camp+']" value="'+order+'">');
			$(ref).parent(".support-sorter-element").append('<input type="hidden"  name="camp['+camp+']" value="'+camp+'">');
			$(ref).parent(".support-sorter-element").append('<input type="hidden"  name="delegated['+camp+']" value="'+delegated+'">');
			
			$('#remove_all').prop('checked', false); // Unchecks it
		}

		function activateClearBtn(){
			$('#undo_all').removeClass('btn-secondary');
			$('#undo_all').addClass('btn-warning');
			$('#undo_all').addClass('undo_all');
		}
    </script>
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

	.greay-out{
		background: #ECECE5;
	}
</style>

{{ Session::forget('warning')}} 
{{ Session::forget('success') }} 
{{ Session::forget('confirm') }} 
{{ Session::forget('error') }}
{{ Session::forget('warningDelegate') }}


@endsection

