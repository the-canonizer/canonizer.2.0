@extends('layouts.app')
@section('content')
@if(Session::has('error'))
<div class="alert alert-danger">
    <strong>Error!</strong>{{ Session::get('error')}}    
</div>
@endif

@if(Session::has('success'))
<div class="alert alert-success">
    <strong>Success!</strong>{{ Session::get('success')}}    
</div>
@endif
      	
<div class="right-whitePnl">
    <div class="container-fluid">
        <?php $user = $nickName->getUser();
            $privateFlags = explode(',',$user->private_flags);
            $supportedCamps = $nickName->getSupportCampList($_REQUEST['namespace'],['nofilter'=>true]);
            $camp_num = app('request')->input('campnum');
            $topic_num = app('request')->input('topicnum');
         ?>
         <address class="user-address">
         @if(!in_array('last_name',$privateFlags) || !in_array('first_name',$privateFlags))
        <p><strong>Name : </strong>{{ !in_array('first_name',$privateFlags) ? $user->first_name : '' }} {{ !in_array('last_name',$privateFlags) ? $user->last_name : '' }}</p>
        @endif
        @if(!in_array('email',$privateFlags))
        <p><strong>Email : </strong>{{$user->email}}</p>
        @endif
        @if(!in_array('birthday',$privateFlags))
        <p><strong>Birthday : </strong>{{$user->birthday}}</p>
        @endif
        @if(!in_array('address_1',$privateFlags))
        <p><strong>Address : </strong>{{$user->address_1}}</p>
        @endif
        @if(!in_array('city',$privateFlags))
        <p><strong>City : </strong>{{$user->city}}</p>
        @endif
        @if(!in_array('state',$privateFlags))
        <p><strong>State : </strong>{{$user->state}}</p>
        @endif
        @if(!in_array('postal_code',$privateFlags))
        <p><strong>Postal Code : </strong>{{$user->postal_code}}</p>
        @endif
        @if(!in_array('country',$privateFlags))
        <p><strong>Country : </strong>{{$user->country}}</p>
        @endif
        </address>
        <div class="Gcolor-Pnl">
            <h3 style="word-break: break-all; min-height: 60px;">
			<div class="pull-left">Nick Name : {{ $nickName->nick_name}} </div>
            <div class="pull-right col-md-4">
            <form>
                <input type="hidden" name="campnum" value="{{$camp_num}}" />
                <input type="hidden" name="topicnum" value="{{$topic_num}}" />
                <select onchange="submitForm(this)" name="namespace" id="namespace" class="namespace-select">
                    @foreach($namespaces as $namespace)
                        <option data-namespace="{{ $namespace->label }}" value="{{ $namespace->id }}" {{ isset($namespace_id) && $namespace->id == $namespace_id ? 'selected' : ''}}>{{$namespace->label}}</option>
                    @endforeach
                </select>
                </form>
            </div>
            </h3>
            <div class="content">	  
            <h5>List of supported camps</h5>
            @if(count($supportedCamps) > 0)
			   @foreach($supportedCamps as $key=>$supports)
                           
               <?php

                                 $delegate_flag = 0;    
                                $topic = \App\Model\Topic::where('topic_num','=',$key)->where('objector_nick_id', '=', NULL)->where('go_live_time', '<=', time())->latest('submit_time')->get();
                                $topic_name = isset($topic[0]) ? $topic[0]->topic_name:'';
                                $topic_name_space_id = isset($topic[0]) ? $topic[0]->namespace_id:1;
                                $request_namesapce = isset($_REQUEST['namespace']) ? $_REQUEST['namespace'] :'1';
                                if($topic_name_space_id !='' && $topic_name_space_id != $request_namesapce){
                                    continue;
                                }
                            ?>
                <ul>
                    <li id="camp_{{$key}}_{{$camp_num}}"><a href="{{ (array_key_exists('link',$supports)  && isset($supports['link'])) ? $supports['link'] : '' }}">{{ (array_key_exists('camp_name',$supports)  && isset($supports['camp_name'])) ? ($topic_name!='')? $topic_name:$supports['camp_name'] : ''}}</a></li>
                    <?php if(isset($supports['delegate_nick_name_id']) && $supports['delegate_nick_name_id'] !=0 && !isset($supports['array'])){ 
                                    $topic = \App\Model\Topic::where('objector_nick_id', '=', NULL)->where('topic_num','=',$key)->latest('submit_time')->get();
                                    $delegatedNick = new \App\Model\Nickname();
                                    $topic_name_space_id = isset($topic[0]) ? $topic[0]->namespace_id:1;
                                    $delegatedNickDetail  = $delegatedNick->getNickName($supports['delegate_nick_name_id']);
                                    $nickName = \App\Model\Nickname::find($supports['delegate_nick_name_id']);
                                    $supported_camp = $nickName->getDelegatedSupportCampList($topic_name_space_id,['nofilter'=>true]);
                                    $supported_camp_list = $nickName->getSupportCampListNames($supported_camp,$key);

                      ?>
                      <ul>
                          <li style="list-style:none;">
                                    Support delegated to {{$delegatedNickDetail->nick_name }}
                                    <?php if($supported_camp_list != '' && $supported_camp_list!= null){ ?>
                                      <span style="font-size:10px; width:100%; float:left;"><b>Supported camp list</b> : {!!$supported_camp_list !!}</span>
                                  <?php } ?>
                                </li>

                      </ul>
                    <?php } ?>
                    <ul>
                        @if(isset($supports['array']))
                        <?php ksort($supports['array']);
                         ?>
                        @foreach($supports['array'] as $support_order)
                            @foreach($support_order as $support)

                            <?php 
                             if(isset($support['delegate_nick_name_id']) && $support['delegate_nick_name_id'] !=0 && $delegate_flag){
                                continue;
                             }
                            if(isset($support['delegate_nick_name_id']) && $support['delegate_nick_name_id'] !=0 && !$delegate_flag){ 

                                    $topic = \App\Model\Topic::where('topic_num','=',$key)->latest('submit_time')->get();
                                    $delegatedNick = new \App\Model\Nickname();
                                    $topic_name_space_id = isset($topic[0]) ? $topic[0]->namespace_id:1;
                                    $delegatedNickDetail  = $delegatedNick->getNickName($support['delegate_nick_name_id']);
                                    $nickName = \App\Model\Nickname::find($support['delegate_nick_name_id']);
                                    $supported_camp = $nickName->getDelegatedSupportCampList($topic_name_space_id,['nofilter'=>true]);
                                    $supported_camp_list = $nickName->getSupportCampListNames($supported_camp,$key);
                                    $delegate_flag = 1;
                                ?>
                                <li style="list-style:none;">
                                    Support delegated to {{$delegatedNickDetail->nick_name }}
                                    <?php if($supported_camp_list != '' && $supported_camp_list!= null){ ?>
                                      <span style="font-size:10px; width:100%; float:left;"><b>Supported camp list</b> : {!!$supported_camp_list !!}</span>
                                  <?php } ?>
                                </li>
                            <?php } else { ?>    
                                <li id="camp_{{$key}}_{{$support['camp_num']}}">
                                    <a href="{{ (array_key_exists('link',$support)  && isset($support['link'])) ? $support['link'] : ''  }}" style="{{ ($support['camp_num'] == $camp_num && $key == $topic_num) ? 'font-weight:bold; font-size:16px;' : '' }}">{{(array_key_exists('camp_name',$support)  && isset($support['camp_name'])) ? $support['camp_name'] : ''}}</a></li>
                            <?php } ?>
                            @endforeach
                        @endforeach
                        @endif
                    </ul>
                </ul>
               @endforeach
            @else
                <p>No data available!</p>
            @endif
            
               
            </div>
        </div>
        
    </div>
    <!-- /.container-fluid-->
</div>  <!-- /.right-whitePnl-->
<script>
var camp_num = '<?php echo $camp_num; ?>';
var topic_num = '<?php echo $topic_num; ?>';
window.scrollTo($("#camp_"+topic_num+"_"+camp_num));
function submitForm(element){
    $(element).parents('form').submit();
}
</script>
@endsection
 