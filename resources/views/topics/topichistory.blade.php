@extends('layouts.app')
@section('content')

<?php if (!empty($topics)) {
	            $topicBreadName = "";
                $currentLive = 0;
                $currentTime = time();
				$topicNum = 0;
                foreach ($topics as $key => $data) {
                    
                   if ($currentLive != 1 && $currentTime >= $data->go_live_time && $data->objector_nick_id == NULL) {
                        $currentLive = 1;
                       $topicBreadName = $data->topic_name; 
					             $topicNum = $data->topic_num;
					             $urltitle      = $topicNum."-".preg_replace('/[^A-Za-z0-9\-]/', '-', $data->topic_name);
                       $url_portion = \App\Model\Camp::getSeoBasedUrlPortion($topicNum,$currentLive);
                    } 
				}	
                    ?>
<div class="camp top-head">
    <h3><b>Topic:</b>  <a href="/topic/{{$url_portion}}" >{{ $topicBreadName}}</a></h3>
   
</div>
<?php } ?>
<div class="page-titlePnl">
    <h1 class="page-title">Topic History</h1>
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
            if (!empty($topics)) {
                $currentLive = 0;
                $currentTime = time();
                $ifIamDelegatedSupporter= 0;
                foreach ($topics as $key => $data) {
                    $nickNamesData = \App\Model\Nickname::personNicknameArray();
                    $liveTopic = \App\Model\Topic::getLiveTopic($data->topic_num);
                    if($submit_time){
                      $support = \App\Model\Support::where('topic_num',$data->topic_num)->whereIn('nick_name_id',$nickNamesData)->where('delegate_nick_name_id',0)->where('end','=',0)->where('start','<=',$submit_time)->orderBy('support_order','ASC')->get();
                    }else{
                      $support = \App\Model\Support::where('topic_num',$data->topic_num)->whereIn('nick_name_id',$nickNamesData)->where('delegate_nick_name_id',0)->where('end','=',0)->orderBy('support_order','ASC')->get();
                    }
                     
                     if(sizeof($support) > 0){
                         if(!$ifIamSupporter){
                            $ifIamSupporter = $support[0]->nick_name_id;
                          }
                      }
                      $delegatedUsers = \App\Model\Support::where('topic_num',$data->topic_num)->where('delegate_nick_name_id','!=',0)->orderBy('support_order','ASC')->get();
                        if(count($delegatedUsers) > 0){
                            foreach ($delegatedUsers as $key => $value) {
                                if(in_array($value->nick_name_id,$nickNamesData)){
                                    $ifIamDelegatedSupporter =  $nickNamesData[array_search($value->nick_name_id,$nickNamesData,true)];
                                }
                            }
                        }
                    $isagreeFlag = false;
                    $isGraceFlag = false;
                    $submittime = $data->submit_time;
                    $starttime = time();
                    $endtime = $submittime + 60*60;
                    $interval = $endtime - $starttime;
                    $intervalTime = date('H:i:s',$interval);
                    $grace_hour = date('H',strtotime($intervalTime));
                    $grace_minute = date('i',strtotime($intervalTime));
                    $grace_second = date('s',strtotime($intervalTime));
                    $submitterUserID = App\Model\Nickname::getUserIDByNickName($data->submitter_nick_id);
                    if ($data->objector_nick_id !== NULL)
                        $bgcolor = "rgba(255, 0, 0, 0.5);"; //red
                    else if ($currentTime < $data->go_live_time && $currentTime >= $data->submit_time) {
                        $bgcolor = "rgba(255, 255, 0, 0.5);"; //yellow
                        $isagreeFlag = true;
                        $isGraceFlag = TRUE;
                        if ($ifIamSupporter) {
                            $isAgreed = App\Model\ChangeAgreeLog::isAgreed($data->id, $ifIamSupporter, 'topic');
                        }
                        
                    //grace period
                        if(Auth::check()){
                       if(Auth::user()->id == $submitterUserID && $data->submit_time > $liveTopic->submit_time && $data->grace_period && $interval > 0){?>
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
                       <?php }} 
                    } else if ($currentLive != 1 && $currentTime >= $data->go_live_time) {
                        $currentLive = 1;
                        $bgcolor = "rgba(0, 128, 0, 0.5);"; // green
                    } else {
                        $bgcolor = "#4e4ef3;"; //blue
                    }
                    ?>
                    <div class="form-group CmpHistoryPnl" style="background-color:{{ $bgcolor }}">
                        <div>
                            <b>Topic Name :</b> {{ $data->topic_name }} <br/>
                            <b>Edit summary :</b> {{ $data->note }} <br/>
                            <b>Namespace :</b>{{namespace_label($data->topicnamespace)}} <br/>
                             <?php
                                  $namespace_id = isset($data->topicnamespace->id) ? $data->topicnamespace->id:1  ;
                                  $userUrl = '';
                                  $objectUsrUrl = '';
                                  if(isset($data->submitternickname->nick_name)){
                                    $userUrl = route('user_supports',$data->submitter_nick_id)."?topicnum=".$data->topic_num."&campnum=".$data->camp_num."&namespace=".$namespace_id."#camp_".$data->topic_num."_".$data->camp_num;  
                                  }
                                  if(isset($data->objectornickname->nick_name)){
                                     $objectUsrUrl = route('user_supports',$data->objector_nick_id)."?topicnum=".$data->topic_num."&campnum=".$data->camp_num."&namespace=".$namespace_id."#camp_".$data->topic_num."_".$data->camp_num;  
                                  }
                                ?>
                            <b>Submitter Nick Name :</b> <a href="{{$userUrl}}">{{ isset($data->submitternickname->nick_name) ? $data->submitternickname->nick_name : 'N/A' }} </a><br/>
                            <b>Submitted on :</b> {{ to_local_time($data->submit_time) }} <br/>
                            <b>Go live Time :</b> {{ to_local_time($data->go_live_time)}} <br/>
                            @if($data->objector_nick_id !=null)
                            <b>Object Reason :</b> {{ $data->object_reason}} <br/>	
                            <b>Objector Nick Name :</b> <a href="{{$objectUsrUrl}}">{{ $data->objectornickname->nick_name }}</a> <br/> 			  
                            @endif 	 				 
                        </div>    
                        <div class="CmpHistoryPnl-footer">
        <?php if ($currentTime < $data->go_live_time && $currentTime >= $data->submit_time && ($ifIamSupporter || $ifIamDelegatedSupporter)) { ?>
                                <a id="object" class="btn btn-historysmt" href="<?php echo url('manage/topic/' . $data->id . '-objection'); ?>">Object</a>
                            <?php } ?>  
                            <a id="update" class="btn btn-historysmt" href="<?php echo url('manage/topic/' . $data->id); ?>">Submit Topic Update Based On This</a>		
                            <?php
                              $link = \App\Model\Camp::getTopicCampUrl($data->topic_num,1);
                            ?>		  
                            <a id="version" class="btn btn-historysmt" href="<?php echo $link.'?asof=bydate&asofdate=' . date('Y/m/d H:i:s', $data->go_live_time); ?>">View This Version</a>
                               <script>
                                     var href = $('#version').attr('href');
                                     var date = new Date(<?= $data->go_live_time ?> * 1000).toLocaleString();
                                     href = href+date;
                                     //$('#version').attr('href',href);
                                 </script>

                        </div> 
                        @if(Auth::check())	
                         @if($isagreeFlag && $ifIamSupporter && $data->submit_time  > $liveTopic->submit_time  && Auth::user()->id != $submitterUserID)

                            <div class="CmpHistoryPnl-footer">
                                <div>
                                    <input {{ (isset($isAgreed) && $isAgreed) ? 'checked' : '' }} {{ (isset($isAgreed) && $isAgreed) ? 'disabled' : '' }} class="agree-to-change" type="checkbox" name="agree" value="" onchange="agreeToChannge(this,'{{ $data->id}}')"> I agree with this topic change</form>
                                </div>                              
                            </div>
                         @endif
                         
                          @if(Auth::user()->id == $submitterUserID && $isGraceFlag && $data->submit_time  > $liveTopic->submit_time && $data->grace_period && $interval > 0)
                          <div class="CmpHistoryPnl-footer" id="countdowntimer_block<?php echo $data->id ;?>">
                                <div class="grace-period-note"><b>Note: </b>This countdown timer is the grace period in which you can make minor changes to your topic before other direct supporters are notified.</div>
                                <div style="float: right" > 
                                    <div class="timer-dial" id="countdowntimer<?php echo $data->id ;?>"></div>
                                   <a href="<?php echo url('manage/topic/'.$data->id.'-update');?>" class="btn btn-historysmt">Edit Change</a>
                                   <a href="javascript:void(0)" onclick="notifyAndCloseTimer('<?php echo $data->id ;?>')"class="btn btn-historysmt">Commit Change</a>
                                </div>
                          </div>
                          @endif
                          @endif
                                

                        
                    </div>
                    <!-- change agreement form -->
                    <form id="changeAgreeForm" action="<?php echo url('statement/agreetochange') ?>" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="topic_num" value="{{ $topicnum}}" />
                        <input type="hidden" name="camp_num" value="{{ isset($onecamp) ? $onecamp->camp_num : '1'}}" />
                        <input type="hidden" name="topic_id" value="" id="agree_to_topic" />
                        <input type="hidden" name="nick_name_id" value="{{ $ifIamSupporter }}" />
                        <input type="hidden" name="change_for" value="topic" />
                    </form>
            
    <?php
    }
} else {

    echo " No camp history available.";
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
    
    function agreeToChannge(evt, id){
        if (evt.checked){
            $('#agree_to_topic').val(id);
            $('#changeAgreeForm').submit();
        } else{
            alert('uncheck - ' + id);
        }
    }
    
    function notifyAndCloseTimer(id){
        $('#countdowntimer_block'+id).remove();
        $.ajax({
            type:"POST",
            datatype:"text",
            data:{type:"topic",id:id},
            url:"<?php echo  url('graceperiod/notify_change')?>",
            success:function(res){

            },
            error:function(res){ alert('error occured');}
        })
    }
</script>


@endsection

