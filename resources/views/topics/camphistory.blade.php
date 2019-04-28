@extends('layouts.app')
@section('content')

<div class="camp top-head">
    <h3><b>Topic:</b>  {{ $topic->topic_name}}</h3>
    <h3><b>Camp:</b> {!! $parentcamp !!}</h3>  

</div>


<div class="page-titlePnl">
    <h1 class="page-title">Camp History</h1>
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
            <form action="{{ route('camp.save')}}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="topic_num" name="topic_num" value="{{ $topic->topic_num }}">


                <?php  
                if (!empty($camps) && !empty($topic)) { 
                    $currentLive = 0;
                    $currentTime = time();

                    foreach ($camps as $key => $data) {
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
                        $pCamp = App\Model\Camp::getLiveCamp($topic->topic_num,$data->parent_camp_num);
                       if ($data->objector_nick_id !== NULL)
                            $bgcolor = "rgba(255, 0, 0, 0.5);"; //red
                        else if ($currentTime < $data->go_live_time && $currentTime >= $data->submit_time) {
                            $bgcolor = "rgba(255, 255, 0, 0.5);"; //yellow
                            $isagreeFlag = true;
                            $isGraceFlag = true;
                            if ($ifIamSupporter) {
                                $isAgreed = App\Model\ChangeAgreeLog::isAgreed($data->id, $ifIamSupporter,'camp');
                            }
                            
                            //grace period
                            if(Auth::check()){
                            if(Auth::user()->id == $submitterUserID && $data->grace_period && $interval > 0){?>
                              <script>
                                    $(function(){
                                      $("#countdowntimer<?php echo $data->id; ?>").countdowntimer({
                                              hours: "<?php echo $grace_hour; ?>",
                                              minutes : "<?php echo $grace_minute; ?>",
                                              seconds : "<?php echo $grace_second; ?>",
                                              timeUp : timeisUp
                                      });

                                      function timeisUp() {
                                          notifyAndCloseTimer('<?php echo $data->id ;?>');                                                                                                                              }
                                      });
                                </script>
                            <?php } } 
                            
                        } else if ($currentLive != 1 && $currentTime >= $data->go_live_time) {
                            $currentLive = 1;
                            $bgcolor = "rgba(0, 128, 0, 0.5);"; // green
                        } else {
                            $bgcolor = "#4e4ef3;"; //blue
                        }
                        ?>
                        <div class="form-group CmpHistoryPnl" style="background-color:{{ $bgcolor }}">
                            <div>
                                @if(!empty($pCamp))<b>Parent Camp: </b>{{$pCamp->camp_name }}<br>@endif
                                <b>Camp Name :</b> {{ $data->camp_name }} <br/>
                                <b>Keyword :</b> {{ $data->key_words }} <br/>
                                <b>Note :</b> {{ $data->note }} <br/>

                                <b>Camp About URL :</b> {{ $data->camp_about_url }} <br/>
                                <b>Submitter Nickname :</b> {{ isset($data->submitternickname->nick_name) ? $data->submitternickname->nick_name : 'N/A' }} <br/>
                                <b>Submitted on :</b> {{ to_local_time($data->submit_time) }} <br/>
                                <b>Go live Time :</b> {{ to_local_time($data->go_live_time)}} <br/>
                                @if($data->objector_nick_id !=null)
                                <b>Object Reason :</b> {{ $data->object_reason}} <br/>	
                                <b>Objector Nickname :</b> {{ $data->objectornickname->nick_name }} <br/> 			  
                                @endif 	 				 
                            </div>    
                            <div class="CmpHistoryPnl-footer"><?php
                                $date = to_local_time($data->go_live_time);
                           ?>
        <?php if ($currentTime < $data->go_live_time && $currentTime >= $data->submit_time) { ?> 
                                    <a id="object" class="btn btn-historysmt" href="<?php echo url('manage/camp/' . $data->id . '-objection'); ?>">Object</a>
                                <?php } ?>
                                <a id="update" class="btn btn-historysmt" href="<?php echo url('manage/camp/' . $data->id); ?>">Submit Camp Update Based On This</a>				  
                                <!-- <a id="version" class="btn btn-historysmt" href="<?php //echo url('topic/' . $data->topic_num . '/' . $data->camp_num . '?asof=bydate&asofdate=' . date('d/m/Y H:i:s', $data->go_live_time)); ?>">View This Version</a> -->
                                <a id="version" class="btn btn-historysmt" href="<?php echo url('topic/' . $data->topic_num . '/' . $data->camp_num . '?asof=bydate&asofdate=' . date('d/m/Y H:i:s', $data->go_live_time)); ?>">View This Version</a>

                            </div> 	

                            @if($isagreeFlag && $ifIamSupporter && Auth::user()->id != $submitterUserID)
                            <div class="CmpHistoryPnl-footer">
                                <div>
                                    <input {{ (isset($isAgreed) && $isAgreed) ? 'checked' : '' }} {{ (isset($isAgreed) && $isAgreed) ? 'disabled' : '' }} class="agree-to-change" type="checkbox" name="agree" value="" onchange="agreeToChannge(this,'{{ $data->id}}')"> I agree with this change</form>
                                </div>
                            </div>
                            @endif
                            
                             @if(Auth::check())
                                @if(Auth::user()->id == $submitterUserID && $isGraceFlag &&  $data->grace_period && $interval > 0)
                                <div class="CmpHistoryPnl-footer" id="countdowntimer_block<?php echo $data->id ;?>">
                                    <div class="grace-period-note"><b>Note: </b>This countdown timer is the grace period in which you can make minor changes to your statement before other direct supporters are notified.</div>
                                   <div style="float: right"> 
                                       <div class="timer-dial" id="countdowntimer<?php echo $data->id ;?>"></div>
                                      <a href="<?php echo url('manage/camp/'.$data->id.'-update');?>" class="btn btn-historysmt">Update Statement</a>
                                      <a href="javascript:void(0)" onclick="notifyAndCloseTimer('<?php echo $data->id ;?>')"class="btn btn-historysmt">Stop</a>
                                   </div>
                                </div>
                                @endif
                             @endif
                            

                        </div>
                        <!-- change agreement form -->
                        <form id="changeAgreeForm" action="<?php echo url('statement/agreetochange') ?>" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="topic_num" value="{{ $topic->topic_num}}" />
                            <?php if(!empty($onecamp)){ ?> 
                                    <input type="hidden" name="camp_num" value="{{ $onecamp->camp_num}}" />
                            <?php }  ?>
                            <input type="hidden" name="camp_id" value="" id="agree_to_camp" />
                            <input type="hidden" name="nick_name_id" value="{{ $ifIamSupporter }}" />
                            <input type="hidden" name="change_for" value="camp" />
                        </form>


    <?php
    }
} else {

    echo "No camp history available.";
}
?>
            </form>
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
        $('#agree_to_camp').val(id);
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
            data:{type:"camp",id:id},
            url:"<?php echo  url('graceperiod/notify_change')?>",
            success:function(res){

            },
            error:function(res){ alert('error occured');}
        })
    }
</script>


@endsection

