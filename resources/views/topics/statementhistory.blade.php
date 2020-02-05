<?php include(app_path() . '/Library/wiki_parser/wikiParser.class.php'); ?>
@extends('layouts.app')
@section('content')

<div class="camp top-head">
    <h3><b>Topic:</b>  {{ $topic->topic_name}}</h3>
    <h3><b>Camp:</b> {!! $parentcamp !!}</h3>  
</div>


<div class="page-titlePnl">
    <h1 class="page-title">Camp Statement History</h1>
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
			<div class="col-sm-2">
                <div class="yellow-circle" style="background-color:#1514ed"></div>
                <div class="circle-txt">Old</div>
            </div>
        </div>
    </div>
</div>
<div>
    <div class="col-sm-12 margin-btm-2">
              
			   <?php 
			        if(!empty($statement)) { 
			            $currentLive = 0; 
				          $currentTime = time();
                                    
                                                
			         foreach($statement as $key=>$data) { 
						   $isagreeFlag = false;
               $isGraceFlag = false;

                $nickNamesData = \App\Model\Nickname::personNicknameArray();
                $supported_camps = [];
                if(sizeof($nickNamesData) > 0){
                  foreach ($nickNamesData as $key => $value) {
                       $nickName = \App\Model\Nickname::find($value);
                       $supported_camp = $nickName->getSupportCampList();
                       $supported_camps = array_merge($supported_camps,$supported_camp);
                  }
                }
               $ifSupportingThisCampOrChild = 0;
                if(isset($supported_camps) && sizeof($supported_camps) > 0){ 
                     foreach ($supported_camps as $key => $value) {
                         if($key == $data->topic_num){
                            if(isset($value['array'])){
                              foreach($value['array'] as $i => $supportData ){
                                  foreach($supportData as $j => $support){
                                       if($support['camp_num'] == $data->camp_num){
                                          $ifSupportingThisCampOrChild = 1;
                                          break;
                                        }
                                      }
                                  }
                              }else{
                                $ifSupportingThisCampOrChild = 1;
                                break;
                              }
                         }                
                    }
                }

                if(!$ifSupportingThisCampOrChild){
                  $camp = \App\Model\Camp::where('camp_num','=',$data->camp_num)->where('topic_num','=',$data->topic_num)->get();
                  $allChildren = \App\Model\Camp::getAllChildCamps($camp[0]);
                  if(sizeof($allChildren) > 0 ){
                  foreach($allChildren as $campnum){
                      $support = \App\Model\Support::where('topic_num',$data->topic_num)->where('camp_num',$campnum)->whereIn('nick_name_id',$nickNamesData)->where('end','=',0)->orderBy('support_order','ASC')->get();
                         if(sizeof($support) > 0){
                              $ifSupportingThisCampOrChild = 1;
                              if(!$ifIamSupporter){
                                $ifIamSupporter = $support[0]->nick_name_id;
                              }
                              break;
                          }
                      }
                  }
                }


                
                $submittime = $data->submit_time;
                $starttime = time();
                $endtime = $submittime + 60*60;
                $interval = $endtime - $starttime;
                $intervalTime = date('H:i:s',$interval);
                $grace_hour = date('H',strtotime($intervalTime));
                $grace_minute = date('i',strtotime($intervalTime));
                $grace_second = date('s',strtotime($intervalTime));
                $submitterUserID = App\Model\Nickname::getUserIDByNickName($data->submitter_nick_id);
                                                   
						   if($data->objector_nick_id !== NULL)
							   $bgcolor ="rgba(255, 0, 0, 0.5);"; //red
						   else if($currentTime < $data->go_live_time && $currentTime >= $data->submit_time) {
							   $bgcolor ="rgba(255, 255, 0, 0.5);"; //yellow
                                                           $isagreeFlag = true;
                                                           $isGraceFlag = TRUE;
                                                           if($ifIamSupporter){
                                                            $isAgreed = App\Model\ChangeAgreeLog::isAgreed($data->id,$ifIamSupporter);
                                                           }
                                                           
                                                     if(Auth::check()){
                                                     if(Auth::user()->id == $submitterUserID && $data->grace_period && $interval > 0){
                                                     ?>
                                                      <script>
                                                            $(function(){
                                                              $("#countdowntimer<?php echo $data->id; ?>").countdowntimer({
                                                                      hours: "<?php echo $grace_hour; ?>",
                                                                      minutes : "<?php echo $grace_minute; ?>",
                                                                      seconds : "<?php echo $grace_second; ?>",
                                                                      timeUp : timeisUp
                                                              });

                                                              function timeisUp() {
                                                                  notifyAndCloseTimer('<?php echo $data->id ;?>');
                                                               }
                                                              });
                                                        </script>
                                                      
                                                     <?php } }
                                                           
						   }   
						   else if($currentLive!=1 && $currentTime >= $data->go_live_time) {
							   $currentLive = 1;
							   $bgcolor ="rgba(0, 128, 0, 0.5);"; // green
						   } else {
							   $bgcolor ="#4e4ef3;"; //blue
						   }
                   $input=$data->value;						   
			   ?>
			    <div class="form-group CmpHistoryPnl" style="background-color:{{ $bgcolor }}; width:100%;">
                  <div class="statement"><b>Statement :</b> 
				  <?php 
				              $rootUrl = str_replace("/public","",Request::root());
							  /*$finalStatement  = $wiky->parse($input); 
							  
							  $finalStatement = str_replace("http://canonizer.com",$rootUrl,$finalStatement);
							  $finalStatement = str_replace("http://www.canonizer.com",$rootUrl,$finalStatement);
							  
							  echo $finalStatement;*/
							  
							  $WikiParser = new wikiParser;
							  $output = $WikiParser->parse($input);
							  $finalStatement = str_replace("http://canonizer.com",$rootUrl,$output);
							  $finalStatement = str_replace("http://www.canonizer.com",$rootUrl,$finalStatement);
							  $finalStatement = str_replace("https://www.canonizer.com",$rootUrl,$finalStatement);
							  $finalStatement = str_replace("https://canonizer.com",$rootUrl,$finalStatement);
							  echo $finalStatement;
				   ?>
				  
				  </div><br/>
				  <b>Edit summary :</b> {{ $data->note }} <br/>				 
				  <b>Submitted on :</b> {{ to_local_time($data->submit_time) }} <br/>
				  <b>Submitter Nick Name :</b> {{ isset($data->submitternickname->nick_name) ? $data->submitternickname->nick_name : 'N/A' }} <br/>
				  <b>Go live Time :</b> {{ to_local_time($data->go_live_time) }}<br/> 
				  @if($data->objector_nick_id !=null)
				  <b>Object Reason :</b> {{ $data->object_reason}} <br/>	
                  <b>Objector Nick Name :</b> {{ $data->objectornickname->nick_name }} <br/> 			  
                  @endif 
				  
				 <div class="CmpHistoryPnl-footer">
				  <?php if($currentTime < $data->go_live_time && $currentTime >= $data->submit_time && $ifIamSupporter) { ?>
            <a id="object" class="btn btn-historysmt" href="<?php echo url('manage/statement/'.$data->id.'-objection');?>">Object</a>
          <?php } ?>	
					<a id="update" class="btn btn-historysmt" href="<?php echo url('manage/statement/'.$data->id);?>">Submit Statement Update Based On This</a>
                    <a id="version" class="btn btn-historysmt" href="<?php echo url('topic/'.$data->topic_num.'/'.$data->camp_num.'?asof=bydate&asofdate='. date('Y/m/d H:i:s', $data->go_live_time)); ?>">View This Version</a>
				          <script>
                   var href = $('#version').attr('href');
                   var date = new Date(<?= $data->go_live_time ?> * 1000).toLocaleString();
                   href = href+date;
                   //$('#version').attr('href',href);
               </script>
				 </div>
                                @if($isagreeFlag && $ifIamSupporter && Auth::user()->id != $submitterUserID)
                                <div class="CmpHistoryPnl-footer">
                                    <div>
                                       <input {{ (isset($isAgreed) && $isAgreed) ? 'checked' : '' }} {{ (isset($isAgreed) && $isAgreed) ? 'disabled' : '' }} class="agree-to-change" type="checkbox" name="agree" value="" onchange="agreeToChannge(this,'{{ $data->id}}')"> I agree with this statement change</form>
                                    </div>
                                </div>
                                @endif
                                
                                @if(Auth::check())
                                    @if(Auth::user()->id == $submitterUserID && $isGraceFlag && $data->grace_period && $interval > 0)
                                    <div class="CmpHistoryPnl-footer" id="countdowntimer_block<?php echo $data->id ;?>">
                                        <div class="grace-period-note"><b>Note: </b>This countdown timer is the grace period in which you can make minor changes to your statement before other direct supporters are notified.</div>
                                        <div style="float: right" > 
                                            <div class="timer-dial" id="countdowntimer<?php echo $data->id ;?>"></div>
                                           <a href="<?php echo url('manage/statement/'.$data->id.'-update');?>" class="btn btn-historysmt">Edit Change</a>
                                           <a href="javascript:void(0)" onclick="notifyAndCloseTimer('<?php echo $data->id ;?>')"class="btn btn-historysmt">Commit Change</a>
                                        </div>
                                    </div>
                                    @endif
                                @endif
                                 
			    </div> 	
                    <form id="changeAgreeForm" action="<?php echo  url('statement/agreetochange')?>" method="post">
                         <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="topic_num" value="{{ $topic->topic_num}}" />
                        <input type="hidden" name="camp_num" value="{{ $onecamp->camp_num}}" />
                        <input type="hidden" name="statement" value="" id="agree_to_statement"/>
                        <input type="hidden" name="nick_name_id" value="{{ $ifIamSupporter }}" />
                        <input type="hidden" name="change_for" value="statement" />
                    </form>
			   <?php } 
				 } else {
					 
					 echo " No statement history available.";
				 }
			   ?>
        
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
        
        function agreeToChannge(evt,id){
            if(evt.checked){
                $('#agree_to_statement').val(id);
                $('#changeAgreeForm').submit();
            }else{
                alert('uncheck - ' + id);
            }
        }
        
        function notifyAndCloseTimer(id){
            $('#countdowntimer_block'+id).remove();
            $.ajax({
                type:"POST",
                datatype:"text",
                data:{type:"statement",id:id},
                url:"<?php echo  url('graceperiod/notify_change')?>",
                success:function(res){

                },
                error:function(res){ alert('error occured');}
            })
        }
        
    </script>


    @endsection

