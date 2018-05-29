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

<?php if(count($topic) > 0 ) { ?>

<div class="camp top-head">
    <h3><b>Topic:</b> {{ $topic->topic_name}}</h3>
    <h3><b>Camp:</b> {!! $parentcamp !!}</h3> 
</div>      	
<div class="right-whitePnl">
    <div class="container-fluid">
        
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
            </h3>
            <div class="content">
            <div class="row">
                <div class="tree treeview col-sm-12">
                    <ul class="mainouter">
                    <?php 
                        $title      = preg_replace('/[^A-Za-z0-9\-]/', '-', $topic->title);						  
                        $topic_id  = $topic->topic_num."-".$title;
						 ?>
                     {!! $topic->campTreeHtml($parentcampnum,1,true) !!} 
                    </ul>
                    
                </div>
              
            </div>    
            </div>
        </div>
        
        <div class="Scolor-Pnl">
            <h3><?php echo ($parentcamp=="Agreement") ? $parentcamp : "Camp";?> Statement
            </h3>
            <div class="content" style="width:100%;" id="camp_statement">
                    <?php 
					
					$rootUrl = str_replace("/public","",Request::root());
					
					
                    $statement = $camp->statement($camp->topic_num,$camp->camp_num);
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
			<?php if(isset($statement->value)) { ?>
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
                <a id="join_support_camp" class="btn btn-warning" href="<?php echo url('support/'.$topic_id.'/'.$camp->camp_num);?>">Join or Directly Support This Camp</a>
            </div>
        </div>
   
        <div class="Scolor-Pnl">
            <h3>Current Topic Record:
            </h3>
            <div class="content">
            <div class="row">
                <div class="tree col-sm-12">
                    Topic Name : <?php echo $topic->topic_name;?> <br/>
					Name Space : <?php echo (isset($topic->namespace_name)) ? $topic->label : 'N/A';?>
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
					Title : <?php echo $camp->title;?><br/>
					Keywords : <?php echo $camp->key_words;?><br/>
					Related URL : <?php echo $camp->url;?><br/>
					
					
					Related Nicknames : <?php echo (isset($camp->nickname->nick_name)) ? $camp->nickname->nick_name : "No nickname associated";?> <br/>
                </div>
              
            </div>    
            </div>
            <div class="footer">
            	<a id="edit_camp" class="btn btn-success"href="<?php echo url('camp/history/'.$camp->topic_num.'/'.$camp->camp_num);?>">Manage/Edit This Camp</a>
            </div>
        </div>
    </div>
	<div class="post"> </div>
	
</div>  <!-- /.right-whitePnl-->

@endsection

<?php } ?>
