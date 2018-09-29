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
            <h3>Browse Topic
            <div class="col-md-6 pull-right">
                <form class="row">
                <div class="col-sm-8">
                <select onchange="submitForm(this)" name="namespace" id="namespace" class="namespace-select">
                    <option value="">All</option>
                    @foreach($namespaces as $namespace)
                        <option data-namespace="{{ $namespace->label }}" value="{{ $namespace->id }}" {{ isset($_REQUEST['namespace']) && $namespace->id == $_REQUEST['namespace'] ? 'selected' : ''}}>{{$namespace->label}}</option>
                    @endforeach
                </select>
                </div>
                @if(Auth::check())
                    <div class="col-sm-4 checkbox pd-l-0">
                    <label><input type="checkbox" name="my" value="1" {{ isset($_REQUEST['my']) &&  $_REQUEST['my'] == 1 ? 'checked' : ''}} onchange="submitForm(this)"> Only My Topics</label>
                    </div>
                @endif
                </form>
            </div>
            </h3>
            <div class="content">
             <p>
              This is the top level browse page. Currently, this page only lists all topics in all namespaces, alphabetically. When there are more topics more browsing abilities will be added; including an automatic hierarchical category system, an ability to include and exclude namespaces from listings (only the main namespace will be listed by default) and a link to a hierarchical "list of lists" topic pages (in the /topic/ namespace) which may include such things as a link to a hierarchical scientific taxonomy classification set of topics or listings of elements and so on.
             </p>			  
            <div class="row">
			   
			    <div class="tree col-sm-12">
                    <ul class="mainouter" id="load-data">
                                        @if(count($topics))
					@foreach($topics as $topic)
					
					     <?php 
						  $title = preg_replace('/[^A-Za-z0-9\-]/', '-', $topic->topic_name);
						   
						  $topic_id = $topic->topic_num."-".$title;
						  
						 ?>
					 
					 <li id="outline_{{ $topic->topic_num }}" style="line-height: 2"> <a href="<?php echo url('topic/'.$topic_id.'/'.$topic->camp_num) ?>"> {{ $topic->label }} {{ $topic->topic_name }} </a></li>
					 @endforeach
                                         @else
                                         @if(isset($_REQUEST['asof']) && isset($_REQUEST['asofdate']))
                                         <li><span class="no-record">No records found for selected as of date.</span></li>
                                         @else
                                         <li><span class="no-record">No records found.</span></li>
                                         @endif
                                         @endif
					</ul>
			    </div>
			</div>  
            </div>
        </div>
        
    </div>
    <!-- /.container-fluid-->
</div>  <!-- /.right-whitePnl-->
<script>
function submitForm(element){
    $(element).parents('form').submit();
}
</script>
@endsection
 