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
            <h3 class="row">
            <div class="col-md-6">Select Namespace</div>
            <div class="col-md-6 pull-right">
                <form class="row">
                <div class="col-sm-8">
                <select onchange="submitBrowseForm(this)" name="namespace" id="namespace" class="namespace-select">
                    <option value="">All</option>
                    @foreach($namespaces as $namespace)
                        <option data-namespace="{{ $namespace->name }}" value="{{ $namespace->id }}" {{ $namespace->id == session('defaultNamespaceId') ? 'selected' : ''}}>{{namespace_label($namespace)}}</option>
                    @endforeach
                </select>
                </div>
                @if(Auth::check())
                    <div class="col-sm-4 checkbox pd-2-0">
                    <label><input type="checkbox" name="my" value="{{(session()->has('defaultNamespaceId')) ? session('defaultNamespaceId') : ''}}" {{ isset($_REQUEST['my']) &&  $_REQUEST['my'] == session('defaultNamespaceId') ? 'checked' : ''}} onchange="submitBrowseForm(this)"> Only My Topics</label>
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
              $namespace = \App\Model\Namespaces::find($topic->namespace_id);
						  $link = \App\Model\Camp::getTopicCampUrl($topic->topic_num,$topic->camp_num,time());
						 ?>
					 
					 <li id="outline_{{ $topic->topic_num }}" style="line-height: 2"> <a href="<?php echo $link; ?>"> {{ namespace_label($namespace) }} {{ $topic->topic_name }} </a></li>
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
$(document).ready(function(){
    var uri = window.location.toString();
    if (uri.indexOf("?") > 0) {
        var clean_uri = uri.substring(0, uri.indexOf("?"));
        window.history.replaceState({}, document.title, clean_uri);            
    }
});
function submitBrowseForm(element){
    if(($(element).val() == null) || ($(element).val() == "")){
        $("input[name='my']").val('');
        $(element).parents('form').submit();
    }else{
      changeNamespace(element);  
    }
}
function changeNamespace(element){
    $.ajax({
        url:"{{ url('/change-namespace') }}",
        type:"POST",
        data:{namespace:$(element).val()},
        success:function(response){
            var namespace = $("select[name='namespace']").val();
            var my = $("input[name='my']").val();
            if(namespace != my){
                if($("input[name='my']").is(":checked")){
                    $("input[name='my']").val(namespace);
                }else{
                    $("input[name='my']").attr('disabled','disabled');
                }
            }
            $(element).parents('form').submit();
        }
    });
}
</script>
@endsection
 