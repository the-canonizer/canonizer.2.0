@extends('layouts.app')
@section('content')


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
    <strong>Success! </strong>{{ Session::get('success')}} {{ (Session::has('objection') && Session::get('objection') == 1 ) ? '' : to_local_time(Session::get('go_live_time')) }}    
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
                foreach ($topics as $key => $data) {
                    $isagreeFlag = false;
                    if ($data->objector_nick_id !== NULL)
                        $bgcolor = "rgba(255, 0, 0, 0.5);"; //red
                    else if ($currentTime < $data->go_live_time && $currentTime >= $data->submit_time) {
                        $bgcolor = "rgba(255, 255, 0, 0.5);"; //yellow
                        $isagreeFlag = true;
                        if ($ifIamSupporter) {
                            $isAgreed = App\Model\ChangeAgreeLog::isAgreed($data->id, $ifIamSupporter, 'topic');
                        }
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
                            <b>Note :</b> {{ $data->note }} <br/>

                            <b>Namespace :</b> {{ $data->topicnamespace->label }} <br/>
                            <b>Submitter Nickname :</b> {{ isset($data->submitternickname->nick_name) ? $data->submitternickname->nick_name : 'N/A' }} <br/>
                            <b>Submitted on :</b> {{ to_local_time($data->submit_time) }} <br/>
                            <b>Go live Time :</b> {{ to_local_time($data->go_live_time)}} <br/>
                            @if($data->objector_nick_id !=null)
                            <b>Object Reason :</b> {{ $data->object_reason}} <br/>	
                            <b>Objector Nickname :</b> {{ $data->objectornickname->nick_name }} <br/> 			  
                            @endif 	 				 
                        </div>    
                        <div class="CmpHistoryPnl-footer">
        <?php if ($currentTime < $data->go_live_time && $currentTime >= $data->submit_time) { ?>
                                <a id="object" class="btn btn-historysmt" href="<?php echo url('manage/topic/' . $data->id . '-objection'); ?>">Object</a>
                            <?php } ?>  
                            <a id="update" class="btn btn-historysmt" href="<?php echo url('manage/topic/' . $data->id); ?>">Submit Topic Update Based On This</a>				  
                            <a id="version" class="btn btn-historysmt" href="<?php echo url('topic/' . $data->topic_num . '/' . $data->camp_num . '?asof=bydate&asofdate=' . date('Y/m/d H:i:s', $data->submit_time)); ?>">View This Version</a>

                        </div> 	
                         @if(($isagreeFlag && $ifIamSupporter))
                            <div class="CmpHistoryPnl-footer">
                                @if($isagreeFlag && $ifIamSupporter)
                                <div>
                                    <input {{ (isset($isAgreed) && $isAgreed) ? 'checked' : '' }} {{ (isset($isAgreed) && $isAgreed) ? 'disabled' : '' }} class="agree-to-change" type="checkbox" name="agree" value="" onchange="agreeToChannge(this,'{{ $data->id}}')"> I agree with this change</form>
                                </div>
                                @endif

                            </div>
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
</script>


@endsection

