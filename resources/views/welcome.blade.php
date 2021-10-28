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

<div class="page-titlePnl">
    <h1 class="page-title">Canonizer Main Page</h1>
    <small>( This is a free <a href="https://github.com/the-canonizer/canonizer.2.0" target="_blank">open source</a> prototype being developed by volunteers. <br>
        Please be patient with what we have so far and/or be willing to help. )</small>
</div>
<div class="right-whitePnl">
    <div class="container-fluid">
        <div class="Gcolor-Pnl">
            <h3>Help us bring the world together by canonizing what you believe is right.</h3>
            <div class="content">

                <iframe src="https://player.vimeo.com/video/307590745" style="position: relative;width: 100%;height:400px;border:none;" id="homeiframe">
                </iframe>

            </div>
        </div>
        <div class="Lcolor-Pnl">
          <div class="row">
            <div class="col-sm-6 sameHeight">
                  <h3>Canonized list for
                <select onchange="changeNamespace(this)" id="namespace">
                    @foreach($namespaces as $namespace)
                        <option data-namespace="{{ $namespace->name }}" value="{{ $namespace->id }}" {{ $namespace->id == session('defaultNamespaceId') ? 'selected' : ''}}>{{namespace_label($namespace)}}</option>
                    @endforeach


                </select>
            </h3>
            <div class="content">
            <div class="row">
         @if(count($topics))
          <div class="tree col-sm-12">
                    <ul class="mainouter" id="load-data">
                      <?php $createCamp = 1;
                        session(['topic_on_page' => 0]);
                       $as_of_time = time();
                        if (isset($_REQUEST['asof']) && $_REQUEST['asof'] == 'bydate') {
                            $as_of_time = strtotime($_REQUEST['asofdate']);
                        }else if(session()->has('asofDefault') && session('asofDefault') == 'bydate' && !isset($_REQUEST['asof'])){
                            $as_of_time = strtotime(session('asofdateDefault'));
                        }
                        session(['topic_on_page' => 0]);
                        ?>
                       @foreach($topics as $k=>$topic)

                       <?php

                        $topicData = \App\Model\Topic::where('topic_num','=',$topic->topic_num)->where('go_live_time', '<=', $as_of_time)->latest('submit_time')->get();
                        $campData = \App\Model\Camp::where('topic_num',$topic->topic_num)->where('camp_num',$topic->camp_num)->where('go_live_time', '<=', $as_of_time)->latest('submit_time')->first();
                        $topic_name_space_id = isset($topicData[0]) ? $topicData[0]->namespace_id:1;
                        $topic_name = isset($topicData[0]) ? $topicData[0]->topic_name:'';
                        $request_namesapce = session('defaultNamespaceId', 1); 
                       
                        
                        $as_of_time = time();
                        if(isset($_REQUEST['asof']) && $_REQUEST['asof']=='date'){
                            $as_of_time = strtotime($_REQUEST['asofdate']);
                        } 
                       ?>
                         {!! $campData->campTreeHtml($createCamp,false,false,'fa-arrow-right') !!}
                         <?php $createCamp = 0;?>
                       @endforeach
                    <a id="btn-more" class="remove-row" data-id="{{ $topic->id }}"></a>

                    </ul>
                    @if(session('topic_on_page') >=20)
                     <p>There are no topics on this page, given the current algorithm and filter setting.</p>
                    @endif
                    {!! $topics->links() !!}
                </div>
        @else
         <h6 style="margin-left:30px;"> No topic available.</h6>
                @endif
              
              </div>
            </div>

            </div>
            <div class="col-sm-6 sameHeight">
              <div class="videopodcast_div">
                {!!$videopodcast->html_content!!}

              </div>
                  
            </div>
          </div>
            

        </div>
    </div>
    <!-- /.container-fluid-->
</div>  <!-- /.right-whitePnl-->

<script>
<?php  $page_no = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;  ?>
var request = false;
var offset = '<?php echo config('app.front_page_limit'); ?>';
   $('#loadtopic').click(function(e){
       var id = $('#btn-more').data('id');
       var queryString = "{!! Request::getQueryString() !!}";
	   var scrollTop = $(document).scrollTop();
       $(this).hide();
	  // scrollTop = scrollTop + 650;
		 // if ( scrollTop > $('.sticky-footer').offset().top && request==false) {

			   $("#btn-more").html("Please wait loading all topic tree......");
			   request = true;
              // alert(offset);
			   $.ajax({
				   url : '{{ url("loadtopic") }}?'+queryString,
				   method : "POST",
				   data : {id:id,offset:offset, _token:"{{csrf_token()}}"},
				   dataType : "text",
				   success : function (data)
				   {
					  if(data != '')
					  {
						  $('.remove-row').remove();
						  $('#load-data').append(data);
						  camptree();
						  request = false;
						  offset = offset + 11;

					  }
					  else
					  {
						  $('#btn-more').html("No more topic available.");
					  }
				   }
			   });
		  //}
		  e.stopImmediatePropagation();
});

function changeNamespace(element){
    $.ajax({
        url:"{{ url('/change-namespace') }}",
        type:"POST",
        data:{namespace:$(element).val()},
        success:function(response){
            var pageNo= <?php echo $page_no; ?>;
            if(pageNo > 1){
              pageNo = 1;
            }
            @if(env('APP_DEBUG'))
                 window.location.href="{{ url('/') }}"+"?page="+pageNo;//window.location.reload();
            @else
            try{
                window.location.href="{{ url('/') }}"+$(element).find('option:selected').attr('data-namespace')+"?page="+pageNo;
            }catch(err){
                window.location.href="{{ url('/') }}"+"?page="+pageNo;
            }
            @endif
        }
    });
}
</script>

@endsection
