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

<div class="camp top-head">
    <h3><b>Topic:</b>  {{ $topic->title}}</h3>
    <h3><b>Camp:</b> {{ $parentcamp }}</h3>  
</div>      	
<div class="right-whitePnl">
    <div class="container-fluid">
        
         <div class="Gcolor-Pnl">
            <h3>Canonizer Sorted Camp Tree
            <a href="#" class="pull-right"><i class="fa fa-question"></i></a>
            </h3>
            <div class="content">
            <div class="row">
                <div class="tree treeview col-sm-12">
                    <ul class="mainouter">
                       <li>
                        <?php
                         $childs = $topic->childrens($topic->topic_num,$topic->camp_num); ?>
                         <span class="<?php if(count($childs) > 0) echo 'parent'; ?>"><i class="fa fa-arrow-right"></i> 
						 <?php 
						  $title      = preg_replace('/[^A-Za-z0-9\-]/', '-', $topic->title);						  
						  $topic_id  = $topic->topic_num."-".$title;
						 
						 ?>
						 <a href="<?php echo url('topic/'.$topic_id.'/'.$topic->camp_num) ?>">
						 {{ $topic->title}} 
						 </a>
						 <div class="badge">48.25</div></span>
                         <?php
                        if(count($childs) > 0){
                            echo $topic->campTree($topic->topic_num,$topic->camp_num,null,$camp->camp_num);
                        }else{
                            echo '<li class="create-new-li"><span><a href="'.route('camp.create',['topicnum'=>$topic->topic_num,'campnum'=>$topic->camp_num]).'">< Create A New Camp ></a></span></li>';
                        }?>
                           </li>
                       
                    </ul>
                    
                </div>
              
            </div>    
            </div>
        </div>
        
        <div class="Lcolor-Pnl">
            <h3><?php echo ($parentcamp=="Agreement") ? $parentcamp : "Camp"; ?> Statement
            </h3>
            <div class="content">
            <div class="row">
                <div class="tree col-sm-12">
                    <?php $statement = $camp->statement($camp->topic_num,$camp->camp_num);
					
					  echo (isset($statement->value)) ? $statement->value : "No statement available";
					?>
				</div>
              
            </div>    
            </div>
            <div class="footer">
            	<a class="btn btn-success">Manage/Edit Camp Statement</a>
                <a class="btn btn-warning">Topic Forum</a>
            </div>
        </div>
        
        <div class="Gcolor-Pnl">
            <h3>Support Tree for "<?php echo $topic->camp_name;?>" Camp
             <a href="#" class="pull-right"><i class="fa fa-question"></i></a>
            </h3>
            <div class="content">
            <div class="row">
                <div class="tree col-sm-12">
                    Total Support for This Camp (including sub-camps): 40.25
				</div>
              
            </div>    
            </div>
            <div class="footer">
                <a class="btn btn-warning">Join or Directly Support This Camp</a>
            </div>
        </div>
   
        <div class="Lcolor-Pnl">
            <h3>Current Topic Record:
            </h3>
            <div class="content">
            <div class="row">
                <div class="tree col-sm-12">
                    Topic Name : <?php echo $topic->topic->topic_name;?> <br/>
					Name Space : <?php echo $topic->topic->namespace;?>
                </div>
              
            </div>    
            </div>
            <div class="footer">
            	<a class="btn btn-success">Manage/Edit Camp Statement</a>
            </div>
        </div>
   
        <div class="Gcolor-Pnl">
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
            	<a class="btn btn-success">Manage/Edit Camp Statement</a>
            </div>
        </div>
    </div>
	
</div>  <!-- /.right-whitePnl-->
	

@endsection
