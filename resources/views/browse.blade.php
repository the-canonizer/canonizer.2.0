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
        <div class="Gcolor-Pnl">
            <h3>Browse</h3>
            <div class="content">
             <p>
              This is the top level browse page. Currently, this page only lists all topics in all namespaces, alphabetically. When there are more topics more browsing abilities will be added; including an automatic hierarchical category system, an ability to include and exclude namespaces from listings (only the main namespace will be listed by default) and a link to a hierarchical "list of lists" topic pages (in the /topic/ namespace) which may include such things as a link to a hierarchical scientific taxonomy classification set of topics or listings of elements and so on.
             </p>			  
            <div class="row">
			   
			    <div class="tree col-sm-12">
                    <ul class="mainouter" id="load-data">
					@foreach($topics as $topic)
					
					     <?php 
						  $title = preg_replace('/[^A-Za-z0-9\-]/', '-', $topic->title);
						   
						  $topic_id = $topic->topic_num."-".$title;
						  
						 ?>
					 
					 <li style="line-height: 2"> <a href="<?php echo url('topic/'.$topic_id.'/'.$topic->camp_num) ?>"> {{ $topic->topic_name }} </a> {{ $topic->title }}</li>
					 @endforeach
					</ul>
			    </div>
			</div>  
            </div>
        </div>
        
    </div>
    <!-- /.container-fluid-->
</div>  <!-- /.right-whitePnl-->

@endsection
 