<?php include(app_path() . '/Library/wiki_parser/wikiParser.class.php'); ?>
@extends('layouts.app')
@section('content')
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

<div id="camp_subscription_notify"  style="display:none;" class="alert alert-success">
    <div calss="row">
                <strong>Success!</strong> <span id="subscription_msg" ></span>
        </div>       
</div>

<?php 
if(isset($topic) && count($topic) > 0 ) { ?>

<div class="camp top-head">
    <h3><b>Topic:</b> {{ $topic->topic_name}}</h3>
    <h3><b>Camp:</b> {!! $parentcamp !!}</h3> 
</div>      	
<div class="right-whitePnl">
    <div class="container-fluid">
         @if(count($news) > 0)
        <div class="news-pnl Scolor-Pnl">
            <h3>News Feeds
                @if($editFlag)<a class="pull-right rgWT" href="{{  url('editnews/' . $id . '/' . $parentcampnum)}}"><i class="fa fa-pencil-square-o"></i> Edit News</a>@endif
            </h3>
            <div class="content">
                <ul>
            @foreach($news as $feed)
            <li class="news-list">
                <a target="_blank" href="{{ $feed->link}}">
                    <span>{{$feed->display_text}}</span>
                </a>
            </li>
            @endforeach
            </ul>
            </div>
        </div>
         @endif
        
         <div class="Scolor-Pnl">
            <h3>Canonizer Sorted Camp Tree
			<a href="#" class="pull-right" data-toggle="tooltip" data-placement="left" title="This section is a table of contents for this topic. It is in outline or tree form, with supporting sub camps indented from the
            parent camp.  If you are in a sub camp, you are also counted in all
            parent camps including the agreement camp at the top.  The numbers are
            canonized scores derived from the people in the camps based on your
            currently selected canonizer on the side bar.  The camps are sorted
            according to these canonized scores.  Each entry is a link to the camp
            page which can contain a statement of belief.  The green line
            indicates the camp page you are currently on and the statement below
            is for that camp."><i class="fa fa-question"></i></a>
            <input type="hidden" id="subs_id" value="<?php echo ($camp_subscription_data && count($camp_subscription_data) > 0) ? $camp_subscription_data[0]->id: null; ?>" />
             <a class="pull-right news-feed" href="{{ url('/addnews/' . $id . '/' . $parentcampnum)}}">Add News</a>
             <?php if(Auth::check() && Auth::user()->id && $camp_subscriptions == 1){  ?>
                <a style="float: right;font-size: medium; margin-right: 20px; margin-top: 5px;"><input id="camp_subscription" type="checkbox" name="subscribe" checked="checked" /> Subscribe</a>
            <?php }else if(Auth::check() && Auth::user()->id && isset($subscribedCamp) && isset($subscribedCamp->topic_num)  && $camp_subscriptions == 2){ 
                 $title = preg_replace('/[^A-Za-z0-9\-]/', '-', $subscribedCamp->topic->topic_name);
                 $topic_id = $subscribedCamp->topic_num . "-" . $title;
             ?> 
                <a href="{{ url('topic/'.$topic_id.'/'.$subscribedCamp->camp_num)}}"  data-toggle="tooltip" data-placement="top" title="You are subscribed to  {{$subscribedCamp->camp_name}} camp" style="float: right;font-size: medium; margin-right: 20px; margin-top: 5px;"><input disabled="true" id="camp_subscription" type="checkbox" name="subscribe" checked="checked" /> Subscribe</a>
            <?php }else if(Auth::check() && Auth::user()->id){ ?>
                <a style="float: right;font-size: medium; margin-right: 20px; margin-top: 5px;"><input id="camp_subscription" type="checkbox" name="subscribe" /> Subscribe</a>
            <?php } ?>
            
            </h3>
			
            <div class="content">
            <div class="row">
                <div class="tree treeview col-sm-12">
                    <ul class="mainouter">
                    <?php 
					    session(['supportCountTotal'=>0]);
                        $title      = preg_replace('/[^A-Za-z0-9\-]/', '-', $topic->topic_name);						  
                        $topic_id  = $topic->topic_num."-".$title;
						 ?>
                     {!! $topic->campTreeHtml($parentcampnum,1,true) !!} 
                    </ul>
                    
                </div>
              
            </div>    
            </div>
        </div>
        <?php if(count($camp) > 0) { ?>
        <div class="Scolor-Pnl" id="statement">
		     <?php $statement = $camp->statement($camp->topic_num,$camp->camp_num);  ?>
            <h3><?php echo ($parentcamp=="Agreement") ? $parentcamp : "Camp";?> Statement
			<?php if(isset($statement->go_live_time)) { ?><span style="float:right; font-size:14px"><b>Go live Time :</b> {{ to_local_time($statement->go_live_time) }}</span>
            <?php } ?>
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
			
			if($statementCount > 0) { ?>
            	<a id="edit_camp_statement" class="btn btn-success" href="<?php echo url('statement/history/'.$topic_id.'/'.$camp->camp_num);?>">Manage/Edit Camp Statement</a>
			<?php } else { ?>
                <a id="add_camp_statement" class="btn btn-success" href="<?php echo url('create/statement/'.$camp->topic_num.'/'.$camp->camp_num);?>">Add Camp Statement</a>
			<?php } ?>			
                <a id="camp_forum" href="<?php echo url('forum/'.$topic_id.'/'.$camp->camp_num.'/threads');?>" class="btn btn-danger">Camp Forum</a>
            </div>
			
        </div>
        
        <div class="Scolor-Pnl">
            <h3>Support Tree for "<?php echo $camp->camp_name;?>" Camp
             <a href="#" class="pull-right" data-toggle="tooltip" data-placement="left" title="Supporters can delegate their support to others.  Direct supporters
receive email notifications of proposed camp changes, while delegated
supporters don’t.  People delegating their support to others are shown
below and indented from their delegates in an outline form.  If a
delegate changes camp, everyone delegating their support to them will
change camps with them."><i class="fa fa-question"></i></a>
            </h3>
            <div class="content">
            <div class="row">
                <div class="col-sm-12">
                    Total Support for This Camp (including sub-camps): 
					
					<div class="badge" id="selected_camp_support">
					 {{ session('supportCountTotal',0) }}
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
               <a id="join_support_camp" class="btn btn-warning" href="<?php echo url('support/'.$topic_id.'/'.$camp->camp_num);?>">Directly Join or Manage Support</a>
            </div>
        </div>
   
        <div class="Scolor-Pnl">
            <h3>Current Topic Record:
            </h3>
            <div class="content">
            <div class="row">
                <div class="tree col-sm-12">
                    Topic Name : <?php echo $topic->topic_name;?> <br/>
					Namespace : <?php echo (isset($topic->namespace_name)) ? $topic->label : 'N/A';?>
                </div>
              
            </div>    
            </div>
            <div class="footer">
            	<a id="edit_topic" class="btn btn-success" href="<?php echo url('topic-history/'.$topic_id);?>">Manage/Edit This Topic</a>
            </div>
        </div>
   
        <div class="Scolor-Pnl">
            <h3>Current Camp Record:
            </h3>
            <div class="content">
            <div class="row">
                <div class="tree col-sm-12">
                    Camp Name : <?php echo $camp->camp_name;?> <br/>
					Keywords : <?php echo $camp->key_words;?><br/>
					Camp About URL : <?php echo $camp->camp_about_url;?><br/>
					
					
					Camp About Nick Name : <?php echo (isset($camp->nickname->nick_name)) ? $camp->nickname->nick_name : "No nickname associated";?> <br/>
                </div>
              
            </div>    
            </div>
            <div class="footer">
            	<a id="edit_camp" class="btn btn-success"href="<?php echo url('camp/history/'.$camp->topic_num.'/'.$camp->camp_num);?>">Manage/Edit This Camp</a>
            </div>
        </div>
     <?php } else { ?>
	  <div>
	   No camp data available.
	  </div>
	 <?php } ?>
    </div>
	<div class="post"> </div>
	
</div>  <!-- /.right-whitePnl-->

<?php } else { ?>
    <div class="right-whitePnl">
    <div class="container-fluid">
            <div class="Scolor-Pnl">
                <form name="as_of" id="as_of_form" method="GET">
                 <!-- <input type="hidden" id="filter" name="filter" value="{{ isset($_REQUEST['filter']) && !empty($_REQUEST['filter']) ? $_REQUEST['filter'] : '0.00' }}"/> -->
                   <input type="hidden" name="_token" value="{{ csrf_token() }}">                   
                   <input type="hidden"  name="asof"  value="bydate">
                   <input hidden type="text" id="asofdatenew" name="asofdate" value="<?php echo $topicData[0]->go_live_time; ?>"/>
                    <h3>This topic was first created on <a href="javascript:void(0);" onClick="submitAsOfForm()">
                        <?php (count($topicData) > 0) ? to_local_time($topicData[0]->submit_time) :'' ;?></a></h3>
                </form>            
            </div>
    </div>              
    </div>
<?php } ?>
<script>
function submitAsOfForm(){
    var dateVal = $('#asofdatenew').val();
    var dateString = new Date(dateVal * 1000).toUTCString();
     $('#asofdatenew').val(dateString);
    $('#as_of_form').submit();
}
var type = window.location.hash.substr(1);
if(type=="statement") {
  $('html, body').animate({
        scrollTop: $("#statement").offset().top
    }, 2000);
}	
<?php if(isset($topic) && count($topic) > 0) { ?>
$('#camp_subscription').click(function(){
    var isChecked = $(this).prop('checked');
    var userId = <?php echo (Auth::check()) ?  Auth::user()->id: null; ?>;
    var topic_num = <?php echo $topic->topic_num; ?>;
    var camp_num = <?php echo $parentcampnum; ?>;
    var subscrip_id = $("#subs_id").val();
    $.ajax({
        type:'POST',
        url:"{{ route('camp.subscription')}}",
        data:{id:subscrip_id,userid:userId,camp_num:camp_num,topic_num:topic_num,checked:isChecked, _token:"{{csrf_token()}}"},
        success:function(res){
          $('#subscription_msg').html(res.message);
          $('#camp_subscription_notify').show().fadeOut(5000);
          $("#subs_id").val(res.id);
        }
    })

})
<?php } ?>

</script>	
@endsection
	
