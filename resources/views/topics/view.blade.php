<?php include(app_path() . '/Library/wiki_parser/wikiParser.class.php'); ?>

@php
if(!empty($topic)) {
    session(['supportCountTotal'=>0]);
    $title      = preg_replace('/[^A-Za-z0-9\-]/', '-', $topic->topic_name);						  
    $topic_id  = $topic->topic_num."-".$title;
    $getCampTree = $topic->campTreeHtml($parentcampnum, 1, false, 'fa-arrow-down', $topic, $fetchTopicHistory);
    $campTree = $getCampTree[0];
    $showCreateCampLink = $getCampTree[1] ?? 1;
}
@endphp

@extends('layouts.app')
@section('content')
@if(Session::has('error'))
<div class="alert alert-danger">
    <strong>Error! </strong>{{ Session::get('error')}}    
</div>
@endif

@if(Session::has('warningDelegate'))
<div class="alert alert-danger">
    <strong>Error! </strong>{{ Session::get('warningDelegate')}}    
</div>
@endif

@if(Session::has('success'))
<div class="alert alert-success">
    <strong>Success! </strong>{{ Session::get('success')}}    
</div>
@endif

<div id="camp_subscription_notify"  style="display:none;" class="alert alert-success">
    <div calss="row">
        <strong>Success!</strong><span id="subscription_msg" ></span>
    </div>       
</div>
<div id="topic_subscription_notify"  style="display:none;" class="alert alert-success">
    <div calss="row">
        <strong>Success!</strong><span id="topic_subscription_msg" ></span>
    </div>       
</div>

<?php 
if(isset($topic) && count($topic) > 0 ) {?>

<div class="camp top-head">
    <h3><b>Topic:</b> {{ $topic->topic_name}}</h3>
    <h3><b>Camp:</b> {!! $parentcamp !!}</h3> 
</div>  
<div class="right-whitePnl">
    <div class="container-fluid">
        @if(count($news) > 0)
        <div class="news-pnl Scolor-Pnl">
            <h3 class="news-feeds-heading">
                <span>News Feeds</span>
                @if($editFlag)
                <div class="nf-button-wrap">
                    <a class="rgWT mr-3" href="{{  url('editnews/' . $id . '/' . $parentcampnum)}}"><i class="fa fa-pencil-square-o"></i> Edit News</a>
                    <a onClick="enableDeleteNews()" class="pull-right rgWT delete-news-btn" href="javascript:void(0);"><i class="fa fa-trash"></i> Delete News</a>
                </div>
                @endif
            </h3>
            <div class="content">
                <ul>
                    @foreach($news as $feed)
                    <li class="news-list">
                        <a target="_blank" href="{{ ( strpos ($feed->link, 'http') === 0 ) ? $feed->link : 'https://' . $feed->link  }}">
                            <span>{{$feed->display_text}}</span>
                        </a>
                         <span style="display:none;cursor:pointer;" class="delete-newsfeeds" onClick="deleteNewsFeed({{$feed->id}})"><i class="fa fa-trash"></i></span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
         @endif
        
         <div class="Scolor-Pnl">
            <h3 class="row">
            <div class="col-md-4">Canonizer Sorted Camp Tree</div>
            <div class="col-md-8">
                <a href="#" class="pull-right" data-toggle="tooltip" data-placement="auto" title="This section is a table of contents for this topic. It is in outline or tree form, with supporting sub camps indented from the
            parent camp.  If you are in a sub camp, you are also counted in all
            parent camps including the agreement camp at the top.  The numbers are
            canonized scores derived from the people in the camps based on your
            currently selected canonizer on the side bar.  The camps are sorted
            according to these canonized scores.  Each entry is a link to the camp
            page which can contain a statement of belief.  The green line
            indicates the camp page you are currently on and the statement below
            is for that camp."><i class="fa fa-question" style="font-size: smaller"></i></a>
            <input type="hidden" id="subs_id" value="<?php echo ($camp_subscription_data && count($camp_subscription_data) > 0) ? $camp_subscription_data[0]->id: null; ?>" />
            <input type="hidden" id="topic_subs_id" value="<?php echo ($topic_subscriptionsData['id'] && count($topic_subscriptionsData['id']) > 0) ? $topic_subscriptionsData['id']: null; ?>" />
            <a class="pull-right news-feed" href="{{ url('/addnews/' . $id . '/' . $parentcampnum)}}" style="font-size: smaller">Add News</a>
             <?php if(Auth::check() && Auth::user()->id && $camp_subscriptions == 1){  ?>
                <a style="float: right;font-size: smaller; margin-right: 20px; margin-top: 5px;"><input id="camp_subscription" type="checkbox" name="subscribe" checked="checked" /> Subscribe to the Camp</a>
            <?php }else if(Auth::check() && Auth::user()->id && isset($subscribedCamp) && isset($subscribedCamp->topic_num)  && $camp_subscriptions == 2){ 
                 $title = preg_replace('/[^A-Za-z0-9\-]/', '-', $subscribedCamp->topic->topic_name);
                 $topic_id = $subscribedCamp->topic_num . "-" . $title;
                 $link = \App\Model\Camp::getTopicCampUrl($subscribedCamp->topic_num,$subscribedCamp->camp_num);
             ?> 
                <a href="<?php echo $link; ?>"  data-toggle="tooltip" data-placement="top" title="You are subscribed to  {{$subscribedCamp->camp_name}} camp" style="float: right;font-size: smaller; margin-right: 20px; margin-top: 5px;"><input disabled="true" id="camp_subscription" type="checkbox" name="subscribe" checked="checked" /> Subscribe to the Camp</a>
            <?php }else if(Auth::check() && Auth::user()->id){ ?>
                <a style="float: right;font-size: smaller; margin-right: 20px; margin-top: 5px;"><input id="camp_subscription" type="checkbox" name="subscribe" /> Subscribe to the Camp</a>
            <?php } ?>
             <?php if(Auth::check() && Auth::user()->id && $topic_subscriptions == 1){  ?>
                <a style="float: right;font-size: smaller; margin-right: 20px; margin-top: 5px;"><input id="topic_subscription" type="checkbox" name="topic_subscription" checked="checked" />Subscribe to entire Topic</a>
            <?php }else if(Auth::check() && Auth::user()->id){ ?>
                <a style="float: right;font-size: smaller; margin-right: 20px; margin-top: 5px;"><input id="topic_subscription" type="checkbox" name="topic_subscription" />Subscribe to entire Topic</a>
            <?php } ?>
            </div>
			
            
            </h3>
			
            <div class="content">
            <div class="row">
                <div class="tree treeview col-sm-12">
                    <ul class="mainouter">
                        {!! $campTree !!} 
                    </ul>
                </div>
              
            </div>    
            </div>
        </div>
        <?php if(count($camp) > 0) { ?>
        <div class="Scolor-Pnl" id="statement">
		     <?php $statement = $camp->statement($camp->topic_num,$camp->camp_num);  ?>
            <h3 class="row">
             <div class="col-md-4"><?php echo ($parentcamp=="Agreement") ? $parentcamp : "Camp";?> Statement</div>
             <div class="col-md-8">                    
                <?php if(isset($statement->go_live_time)) { ?><span class="pull-right" style="font-size:14px"><b>Go live Time :</b> {{ to_local_time($statement->go_live_time) }}</span>
                <?php } ?>
             </div>
			</h3>
            <div class="content" style="width:100%;" id="camp_statement">
                    <?php 
					
					$rootUrl = str_replace("/public","",Request::root());
					$rootUrl = str_replace("/index.php","",$rootUrl);
					

                    if(isset($statement->value)) {
                              $input=$statement->value;
							  
							  //$finalStatement  = $wiky->parse($input); 
							  
							  /// echo $finalStatement;
                              //html_entity_decode($input,ENT_QUOTES, "UTF-8")
							  $WikiParser = new wikiParser;
							  $output = $WikiParser->parse($input);
							  $finalStatement = str_replace("http://canonizer.com",$rootUrl,$output);
							  $finalStatement = str_replace("http://www.canonizer.com",$rootUrl,$finalStatement);
							  $finalStatement = str_replace("https://www.canonizer.com",$rootUrl,$finalStatement);
							  $finalStatement = str_replace("https://canonizer.com",$rootUrl,$finalStatement);
							  echo $finalStatement;
							 
							
                        } else {
                            echo "No statement available";
                        }
                        
                ?>
            </div>
            <div class="footer">
			<?php 
			$statementCount = count($camp->anystatement($camp->topic_num,$camp->camp_num));
			$url_portion = \App\Model\Camp::getSeoBasedUrlPortion($camp->topic_num,$camp->camp_num);
			if($statementCount > 0) { 
                
                ?>
            	<a id="edit_camp_statement" class="btn btn-success" href="<?php echo url('statement/history/'.$url_portion);?>">Manage/Edit Camp Statement</a>
			<?php } else { ?>
                <a id="add_camp_statement" class="btn btn-success" href="<?php echo url('create/statement/'.$url_portion);?>">Add Camp Statement</a>
			<?php } ?>			
                <a id="camp_forum" href="<?php echo url('forum/'.$url_portion.'/threads');?>" class="btn btn-danger">Camp Forum</a>
            </div>
			
        </div>
        
        <div class="Scolor-Pnl">
            <h3 class="row">
            <div class="col-md-6">Support Tree for "<?php echo $camp->camp_name;?>" Camp</div>
             <div class="col-md-6">
                 <a  style="color:#0000ff;" class="pull-right" data-toggle="tooltip" data-placement="auto" title="Supporters can delegate their support to others.  Direct supporters
receive email notifications of proposed camp changes, while delegated
supporters don’t.  People delegating their support to others are shown
below and indented from their delegates in an outline form.  If a
delegate changes camp, everyone delegating their support to them will
change camps with them."><i class="fa fa-question"></i></a>
             </div>

            </h3>
            <div class="content">
            <div class="row">
                <div class="col-sm-12">
                    Total Support for This Camp (including sub-camps): 
					
					<div class="badge" id="selected_camp_support">
					 {{ round(session('supportCountTotal',0),2) }}
					</div>
					
                    <ul class="support-tree" id="support-tree">
					  <?php

                    $support_tree = \App\Model\TopicSupport::topicSupportTree(session('defaultAlgo','blind_popularity'),$camp->topic_num,$camp->camp_num); ?>
                    {!! $support_tree !!}
					 </ul>  
				</div>
              
            </div>    
            </div>
            <div class="footer">
                <?php 
                if ((isset($_REQUEST['asof']) && $_REQUEST['asof'] == "bydate" && $_REQUEST['asofdate'] && strtotime($_REQUEST['asofdate']) < time())  || (session()->has('asofDefault') && session('asofDefault') == 'bydate' && !isset($_REQUEST['asof']))) {
                    $href =  "javascript:void(0)";
                    $style =  "cursor: default;";
                    $class =  "disable-btn";
                    $tooltip = " <i data-toggle='tooltip' data-placement='auto' title='History cannot be modified. In order to modify your current support select the default option in the as-of box.' class='fa fa-info-circle'></i>";
                }else {
                    $href = url('support/'.$url_portion);
                    $tooltip = "";
                    $style = "";
                    $class =  "btn-warning";
                }
                 if(isset($ifIamSupporter) && !($ifIamSupporter)){ ?>
                    <a id="join_support_camp" style="{{$style}}" class="btn {{$class}}" href="{{$href}}">Directly Join and Support {!! $tooltip !!}</a>
                <?php }else{ ?>
                    <a id="join_support_camp" style="{{$style}}" class="btn {{$class}}"  href="{{$href}}">Manage Support {!! $tooltip !!}</a>
                <?php } ?>
               
            </div>
        </div>
   
        <div class="Scolor-Pnl">
            <h3 class="row">
                <div class="col-md-6">Current Topic Record</div>
            </h3>
            <div class="content">
            <div class="row">
                <div class="tree col-sm-12">
                    <?php $namespace = \App\Model\Namespaces::find($topic->namespace_id); ?>
                    Topic Name : <?php echo $topic->topic_name;?> <br/>
					Namespace : {{namespace_label($namespace)}}
                </div>
              
            </div>    
            </div>
            <div class="footer">
            	<a id="edit_topic" class="btn btn-success" href="<?php echo url('topic-history/'.$topic_id);?>">Manage/Edit This Topic</a>
            </div>
        </div>
   
        <div class="Scolor-Pnl">
            <h3 class="row"><div class="col-md-6">Current Camp Record</div></h3>
            <div class="content">
            <div class="row">
                <div class="tree col-sm-12">
                    Camp Name : <?php echo $camp->camp_name;?> <br/>
                    @if (!empty($camp->parent_camp_num))
                        @php
                            $parentCampTitle = '';
                            $parentCampData = \App\Model\Camp::getLiveCamp($camp->topic_num, $camp->parent_camp_num);
                            if (!empty($parentCampData)) {
                                $parentCampTitle = $parentCampData->title;
                            }
                        @endphp
                        Parent Camp : {{ $parentCampTitle }}<br/>
                    @endif
					Keywords : <?php echo $camp->key_words;?><br/>
                    @if( $camp->camp_about_url )
                        Camp About URL : <a href="<?php echo (( strpos ($camp->camp_about_url, 'http') === 0 ) ||( strpos ($camp->camp_about_url, 'https') === 0 )) ? $camp->camp_about_url : 'http://' . $camp->camp_about_url; ?>" target="_blank" >
                        {{ (( strpos ($camp->camp_about_url, 'http') === 0 ) ||( strpos ($camp->camp_about_url, 'https') === 0 )) ? $camp->camp_about_url : 'http://' . $camp->camp_about_url  }} 
                        </a>
                        <br/>
                    @else
                        Camp About URL :  <br/>
                    @endif

					Camp About Nick Name : <?php echo (isset($camp->nickname->nick_name)) ? $camp->nickname->nick_name : "No nickname associated";?> <br/>
                    Disable additional sub camps : {{  ($camp->is_disabled == 1) ? 'Yes' : 'No' }} <br/>
                    Single level camps only : {{ ($camp->is_one_level == 1) ? 'Yes' : 'No' }} <br/>
                </div>
              
            </div>    
            </div>
            <div class="footer">
            	<a id="edit_camp" class="btn btn-success"href="<?php echo url('camp/history/'.$url_portion );?>">Manage/Edit This Camp</a>
            </div>
        </div>
     <?php } else { ?>
	  <div class="right-whitePnl">
        <div class="container-fluid">
                <div class="Scolor-Pnl">
                    <form name="as_of" id="as_of_form" method="GET">
                     <input type="hidden"  name="filter" value="0.00"/>
                       <input type="hidden" name="_token" value="{{ csrf_token() }}">                   
                       <input type="hidden"  name="asof"  value="bydate">
                       <input hidden type="text" id="asofdatenew" name="asofdate" value="<?php echo date("Y-m-d H:i:s",$campData[0]->go_live_time); ?>"/>
                        <h3>This camp was first created on <a href="javascript:void(0);" onClick="submitAsOfForm()">
                            <?php (count($campData) > 0) ? to_local_time($campData[0]->submit_time) :'' ;?></a></h3>
                    </form>            
                </div>
        </div>              
        </div>
	 <?php } ?>
    </div>
	
	
</div>  <!-- /.right-whitePnl-->

<?php } else { ?>
    <div class="right-whitePnl">
    <div class="container-fluid">
            <div class="Scolor-Pnl">
                <form name="as_of" id="as_of_form" method="GET">
                 <input type="hidden"  name="filter" value="0.00"/>
                   <input type="hidden" name="_token" value="{{ csrf_token() }}">                   
                   <input type="hidden"  name="asof"  value="bydate">
                   <input hidden type="text" id="asofdatenew" name="asofdate" value="<?php echo date("Y-m-d H:i:s",$topicData[0]->go_live_time); ?>"/>
                    <h3>This topic was first created on <a href="javascript:void(0);" onClick="submitAsOfForm()">
                        <?php (count($topicData) > 0) ? to_local_time($topicData[0]->submit_time) :'' ;?></a></h3>
                </form>            
            </div>
    </div>              
    </div>
<?php }
    $dateModified = false;
    if (strtotime(session('asofdateDefault')) > time() && session('asofDefault') == 'bydate') {
        session([
            'asofdateDefault' => date("Y/m/d H:i:s")
        ]);
        $dateModified = true;
    }
?>
@if (!empty($dateModified))
   <script>
    $(document).ready(function() {
        $('#asofdate').val("{{ session('asofdateDefault') }}");
    });
   </script>
@endif
<script>
$('.singleClick').click(function(e) {
    e.stopPropagation();
    //do other stuff when a click happens
    $('.singleClick').addClass('disabled'); //1225 ticket
});
function enableDeleteNews(){
    $('.delete-newsfeeds').show();
}

function deleteNewsFeed(id){
    
   var crfm = confirm('Are you sure you want to delete this news feed');
   if(crfm){
        window.location.href="{{ url('/newsfeed/delete')}}/"+id;
   }
}

function submitAsOfForm(){
    var dateVal = $('#asofdatenew').val();
    var dateString = new Date(dateVal * 1000).toLocaleString();
    $('#asofdatenew').val(dateVal);
    $('#as_of_form').submit();
}

<?php if(count($camp) > 0) { ?>
    var type = window.location.hash.substr(1);
if(type=="statement") {
  $('html, body').animate({
        scrollTop: $("#statement").offset().top
    }, 2000);
}
<?php } ?>	
<?php if(isset($topic) && count($topic) > 0) { ?>
$('#camp_subscription').click(function(){
    var isChecked = $(this).prop('checked');
    var userId = '<?php echo (Auth::check()) ?  Auth::user()->id: null; ?>';
    var topic_num = "<?php echo $topic->topic_num; ?>";
    var camp_num = "<?php echo $parentcampnum; ?>";
    var subscrip_id = $("#subs_id").val();
    $.ajax({
        type:'POST',
        url:"{{ route('camp.subscription')}}",
        data:{id:subscrip_id,userid:userId,camp_num:camp_num,topic_num:topic_num,checked:isChecked, _token:"{{csrf_token()}}"},
        success:function(res){
          $('#subscription_msg').html(res.message);
          $('#camp_subscription_notify').show().fadeOut(5000);
          $("#subs_id").val(res.id);
        },
        error:function(err){
            if(err.status == 401) {
                window.location.href = "{{ route('login')}}"
            }
        }
    })

});
$('#topic_subscription').click(function(){
    var isChecked = $(this).prop('checked');
    var userId = '<?php echo (Auth::check()) ?  Auth::user()->id: null; ?>';
    var topic_num = "<?php echo $topic->topic_num; ?>";
    var subscrip_id = $("#topic_subs_id").val();
    $.ajax({
        type:'POST',
        url:"{{ route('topic.subscription')}}",
        data:{id:subscrip_id,userid:userId,topic_num:topic_num,checked:isChecked, _token:"{{csrf_token()}}"},
        success:function(res){
          $('#topic_subscription_msg').html(res.message);
          $('#topic_subscription_notify').show().fadeOut(5000);
          $("#topic_subs_id").val(res.id);
        },
        error:function(err){
            if(err.status == 401) {
                window.location.href = "{{ route('login')}}"
            }
        }
    })

});
<?php } ?>
</script>	
@endsection
	
