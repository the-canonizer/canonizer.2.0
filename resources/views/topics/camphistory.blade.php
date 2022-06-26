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
            <div class="notifySignPNL topic-status">
                <div class="col">
                    <div class="red-circle"></div>
                    <div class="circle-txt">Objected</div>
                </div>
                <div class="col">
                    <div class="green-circle"></div>
                    <div class="circle-txt">Live</div>
                </div>
                <div class="col">
                    <div class="yellow-circle"></div>
                    <div class="circle-txt">In Review</div>
                </div>
                <div class="col">
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
                    //$currentLive = 0;
                    $currentTime = time();
                    $ifIamDelegatedSupporter = 0;
                    foreach ($camps as $key => $data) {
                        
                        $liveCamp = \App\Model\Camp::getLiveCamp($data->topic_num,$data->camp_num);
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
                        $pCamp = App\Model\Camp::getLiveCamp($data->topic_num,$data->parent_camp_num);
                        $nickNamesData = \App\Model\Nickname::personNicknameArray();
                            $supported_camps = [];
                            if(sizeof($nickNamesData) > 0){
                              foreach ($nickNamesData as $key => $value) {
                                   $nickName = \App\Model\Nickname::find($value);
                                   $supported_camp = $nickName->getSupportCampList();
                                  // echo "<pre>"; print_r($supported_camp); die;
                                   $supported_camps = $supported_camps+$supported_camp; //array_merge($supported_camps,$supported_camp);
                              }
                            }

                            $ifSupportingThisCamp = 0;
                            if(isset($supported_camps) && sizeof($supported_camps) > 0){
                                $flag = false; 
                                 foreach ($supported_camps as $key => $value) {
                                     if($key == $data->topic_num){
                                        if(isset($value['array'])){
                                          foreach($value['array'] as $i => $supportData ){
                                              foreach($supportData as $j => $support){
                                                   if($support['camp_num'] == $data->camp_num){
                                                     $ifSupportingThisCamp = 1;
                                                      break;
                                                    }
                                                  }
                                              }
                                          }else{
                                            $ifSupportingThisCamp = 1;
                                            break;
                                          }
                                     }                
                                }
                            }      
                                         
                         if(!$ifSupportingThisCamp){
                          $camp = \App\Model\Camp::where('camp_num','=',$data->camp_num)->where('topic_num','=',$data->topic_num)->get();
                          $delegatedUsers = \App\Model\Support::where('topic_num',$data->topic_num)->where('delegate_nick_name_id','!=',0)->orderBy('support_order','ASC')->get();
                          if(count($delegatedUsers) > 0){
                              foreach ($delegatedUsers as $key => $value) {
                                  if(in_array($value->nick_name_id,$nickNamesData)){
                                        $ifIamDelegatedSupporter =  $nickNamesData[array_search($value->nick_name_id,$nickNamesData,true)];
                                  }
                              }
                          }
                          
                          $allChildren = \App\Model\Camp::getAllChildCamps($camp[0]);
                          if(sizeof($allChildren) > 0 ){
                          foreach($allChildren as $campnum){
                              if($submit_time){
                                  $support = \App\Model\Support::where('topic_num',$data->topic_num)->where('camp_num',$campnum)->whereIn('nick_name_id',$nickNamesData)->whereIn('delegate_nick_name_id',$nickNamesData)->where('end','=',0)->where('start','<=',$submit_time)->orderBy('support_order','ASC')->get();
                              }else{
                                $support = \App\Model\Support::where('topic_num',$data->topic_num)->where('camp_num',$campnum)->whereIn('nick_name_id',$nickNamesData)->whereIn('delegate_nick_name_id',$nickNamesData)->where('end','=',0)->orderBy('support_order','ASC')->get();
                              }
                              
                                 if(sizeof($support) > 0){
                                      if(!$ifIamSupporter){
                                        $ifIamSupporter = $support[0]->nick_name_id;
                                      }
                                      break;
                                  }
                              }
                          }
                          
                        }

                           
                       if ($data->objector_nick_id !== NULL)
                            $bgcolor = "rgba(255, 0, 0, 0.5);"; //red
                        else if ($currentTime < $data->go_live_time && $currentTime >= $data->submit_time) {
                            $bgcolor = "rgba(255, 255, 0, 0.5);"; //yellow
                            $isagreeFlag = true;
                            $isGraceFlag = true;
                            if ($ifIamImplicitSupporter) {
                                $isAgreed = App\Model\ChangeAgreeLog::isAgreed($data->id, $ifIamImplicitSupporter,'camp');
                            }
                            $IFNOtSubmissterNotSupporterAndInGracePeriod = false;
                            if($ifIamSupporter && $interval > 0 && $data->grace_period > 0  && Auth::user()->id != $submitterUserID){
                                $IFNOtSubmissterNotSupporterAndInGracePeriod = true;
                            }else if(Auth::check() && $data->grace_period > 0 && $interval > 0 && $currentTime < $data->go_live_time && Auth::user()->id != $submitterUserID){
                                $IFNOtSubmissterNotSupporterAndInGracePeriod = true;
                            }else if(!Auth::check() && $data->grace_period > 0 && $interval > 0 && $currentTime < $data->go_live_time){
                                $IFNOtSubmissterNotSupporterAndInGracePeriod = true;
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
                            <?php } }else{
                                $IFNOtSubmissterNotSupporterAndInGracePeriod = true;
                            } 
                         if($IFNOtSubmissterNotSupporterAndInGracePeriod){
                                continue;
                            }
                        } else if ($liveCamp->id == $data->id) {
                            // $currentLive != 1 && $currentTime >= $data->go_live_time
                            //$currentLive = 1;
                            $bgcolor = "rgba(0, 128, 0, 0.5);"; // green
                        } else {
                            $bgcolor = "#4e4ef3;"; //blue
                        }
                        
                        ?>
                        <div class="form-group CmpHistoryPnl" style="background-color:{{ $bgcolor }}">
                            <div>
                                <b>Camp Name :</b> {{ $data->camp_name }} <br/>
								 @if(!empty($pCamp))<b>Parent Camp : </b>{{$pCamp->camp_name }}<br>@endif
                                <b>Keywords :</b> {{ $data->key_words }} <br/>
                                <b>Edit summary :</b> {{ $data->note }} <br/>

                                <b>Camp About URL :</b> 
                                <?php if(isset($data->camp_about_url) && $data->camp_about_url){?>
                                <a href="<?php echo (( strpos ($data->camp_about_url, 'http') === 0 ) ||( strpos ($data->camp_about_url, 'https') === 0 )) ? $data->camp_about_url : 'http://' . $data->camp_about_url; ?>" target="_blank" class="CmpHistoryPnl_a">
                                  {{ (( strpos ($data->camp_about_url, 'http') === 0 ) ||( strpos ($data->camp_about_url, 'https') === 0 )) ? $data->camp_about_url : 'http://' . $data->camp_about_url  }}
                                </a>
                                <?php } ?>
                                
                                <br/>
                                <b>Submitter Nick Name :</b> 
                                <?php
                                  $namespace_id = 1;
                                  if(isset($topic) && isset($topic->namespace_id)){
                                    $namespace_id = $topic->namespace_id;
                                  }
                                  $userUrl = '';
                                  $objectUsrUrl = '';
                                  if(isset($data->submitternickname->nick_name)){
                                     $userUrl = route('user_supports',$data->submitter_nick_id)."?topicnum=".$data->topic_num."&campnum=".$data->camp_num."&namespace=".$namespace_id."#camp_".$data->topic_num."_".$data->camp_num;  
                                  }
                                  if(isset($data->objectornickname->nick_name)){
                                     $objectUsrUrl = route('user_supports',$data->objector_nick_id)."?topicnum=".$data->topic_num."&campnum=".$data->camp_num."&namespace=".$namespace_id."#camp_".$data->topic_num."_".$data->camp_num;  
                                  }
                                ?>
                                <a href="{{$userUrl}}" class="site-regular-text">{{ isset($data->submitternickname->nick_name) ? $data->submitternickname->nick_name : 'N/A' }} </a><br/>
                                <b>Submitted on :</b> {{ to_local_time($data->submit_time) }} <br/>
                                <b>Go live Time :</b> {{ to_local_time($data->go_live_time)}}<br/>
                                @if($data->objector_nick_id !=null)
                                <b>Object Reason :</b> {{ $data->object_reason}} <br/>	
                                <b>Objector Nick Name :</b> <a href="{{$objectUsrUrl}}" class="site-regular-text">{{ $data->objectornickname->nick_name }} </a><br/> 			  
                                @endif 	 				 
                            </div>    
                            <div class="CmpHistoryPnl-footer">
                               
        <?php if ($currentTime < $data->go_live_time && $currentTime >= $data->submit_time && ($ifIamImplicitSupporter) ) { ?> 
                                    <a id="object" class="btn btn-historysmt mb-1" href="<?php echo url('manage/camp/' . $data->id . '-objection'); ?>">Object</a>
                                <?php }else if($currentTime < $data->go_live_time && $currentTime >= $data->submit_time && $ifSupportDelayed){ ?>
                                    <button type="button" onClick="disagreementPopup()" class="btn btn-historysmt mb-1 disable-btn"  >Object &nbsp;<i title="Only direct supporters at the time this change was submitted can object." class="fa fa-info-circle" aria-hidden="true"></i></button>
                                <?php }else if($currentTime < $data->go_live_time && $currentTime >= $data->submit_time){ ?>
                                    <button type="button" onClick="disagreementPopup()"  class="btn btn-historysmt mb-1 disable-btn"  >Object &nbsp;<i title="Only direct supporters at the time this change was submitted can object.." class="fa fa-info-circle" aria-hidden="true"></i></button>
                                <?php } ?> 
                                <a id="update" class="btn btn-historysmt mb-1" href="<?php echo url('manage/camp/' . $data->id); ?>">Submit Camp Update Based On This</a>		  
                                <?php
                                  $link = \App\Model\Camp::getTopicCampUrl($data->topic_num,$data->camp_num);
                                ?>
                                 <a id="version" class="btn btn-historysmt mb-1" href="<?php echo $link. '?asof=bydate&asofdate=' . date('Y/m/d H:i:s', $data->go_live_time) . '&topic_history=1' ?>">View This Version</a>
                                 <script>
                                     var href = $('#version').attr('href');
                                     var date = new Date(<?= $data->go_live_time ?> * 1000).toLocaleString();
                                     href = href+date;
                                    // $('#version').attr('href',href);
                                 </script>

                            </div> 	

                            @if(Auth::check())
                            @if($isagreeFlag && $ifIamImplicitSupporter && $data->submit_time > $liveCamp->submit_time && Auth::user()->id != $submitterUserID)
                            <div class="CmpHistoryPnl-footer">
                                <div>
                                    <input {{ (isset($isAgreed) && $isAgreed) ? 'checked' : '' }} {{ (isset($isAgreed) && $isAgreed) ? 'disabled' : '' }} class="agree-to-change" type="checkbox" name="agree" value="" onchange="agreeToChannge(this,'{{ $data->id}}')"> I agree with this camp change</form>
                                </div>
                            </div>
                            @endif
                              
                              @php
                                //$data->submit_time > $liveCamp->submit_time
                              @endphp
                             
                                @if(Auth::user()->id == $submitterUserID  &&  $isGraceFlag &&  $data->grace_period && $interval > 0)
                                <div class="CmpHistoryPnl-footer" id="countdowntimer_block<?php echo $data->id ;?>">
                                    <div class="grace-period-note"><b>Note: </b>This countdown timer is the grace period in which you can make minor changes to your camp before other direct supporters are notified.</div>
                                   <div style="float: right"> 
                                       <div class="timer-dial" id="countdowntimer<?php echo $data->id ;?>"></div>
                                      <a href="<?php echo url('manage/camp/'.$data->id.'-update');?>" class="btn btn-historysmt mb-1">Edit Change</a>
                                      <a href="javascript:void(0)" onclick="notifyAndCloseTimer('<?php echo $data->id ;?>')"class="btn btn-historysmt mb-1">Commit Change</a>
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
                            <input type="hidden" name="nick_name_id" value="{{ $ifIamImplicitSupporter }}" />
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
            console.log('uncheck - ' + id);
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
            error:function(res){ console.log('error occured');}
        })
    }
</script>


@endsection

