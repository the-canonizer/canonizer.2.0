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
    <small>( This is a free open source prototype being developed by volunteers. <br>
        Please be patient with what we have so far and/or be willing to help. )</small>
</div>
<div class="right-whitePnl">
    <div class="container-fluid">
        <div class="Gcolor-Pnl">
            <h3>Canonizer Blog</h3>
            <div class="content">

                <iframe src="{{ url('/') }}/blog" style="position: relative;width: 100%;height:400px;border:none;"></iframe>
                <!-- <iframe src="http://staging.canonizer.com/blog/" style="position: relative;width: 100%;height:400px;border:none;"></iframe> -->

            </div>
        </div>
        <div class="Lcolor-Pnl">
            <h3>Canonized list for
                <select onchange="changeNamespace(this)" id="namespace">
                    @foreach($namespaces as $namespace)
                        <option data-namespace="{{ $namespace->label }}" value="{{ $namespace->id }}" {{ $namespace->id == session('defaultNamespaceId') ? 'selected' : ''}}>{{$namespace->label}}</option>
                    @endforeach


                </select>
            </h3>
            <div class="content">
            <div class="row">
			   @if(count($topics))
			    <div class="tree col-sm-12">
                    <ul class="mainouter" id="load-data">
                      <?php $createCamp = 1; ?>

                       @foreach($topics as $k=>$topic)
                       <?php
                       $as_of_time = time();
                        if(isset($_REQUEST['asof']) && $_REQUEST['asof']=='date'){
                            $as_of_time = strtotime($_REQUEST['asofdate']);
                        }

                       ?>
                         {!! $topic->campTreeHtml($createCamp) !!}
                         <?php $createCamp = 0;?>
                       @endforeach
					   <a id="btn-more" class="remove-row" data-id="{{ $topic->id }}"></a>
                    </ul>

                </div>
				@else
				 <h6 style="margin-left:30px;"> No topic available.</h6>
                @endif
              </div>
            </div>

        </div>
    </div>
    <!-- /.container-fluid-->
</div>  <!-- /.right-whitePnl-->

<script>
var request = false;
var offset = 10;
   $(document).scroll(function(e){
       var id = $('#btn-more').data('id');
       var queryString = "{!! Request::getQueryString() !!}";
	   var scrollTop = $(window).scrollTop();

	   scrollTop = scrollTop + 650;
		  if ( scrollTop > $('.sticky-footer').offset().top && request==false) {

			   $("#btn-more").html("Please wait loading tree......");
			   request = true;

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
						  offset = offset + 10;

					  }
					  else
					  {
						  $('#btn-more').html("No more topic available.");
					  }
				   }
			   });
		  }
		  e.stopImmediatePropagation();
});

function changeNamespace(element){
    $.ajax({
        url:"{{ url('/change-namespace') }}",
        type:"POST",
        data:{namespace:$(element).val()},
        success:function(response){
            @if(env('APP_DEBUG'))
                window.location.reload();
            @else
            try{
                window.location.href="{{ url('/') }}"+$(element).find('option:selected').attr('data-namespace');
            }catch(err){
                window.location.href="{{ url('/') }}";
            }
            @endif
        }
    });
}
</script>
@endsection
