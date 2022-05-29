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
                <select onchange="changeNamespace(this)" id="namespace" class="namespace-selector">
                    @foreach($namespaces as $namespace)
                        <option data-namespace="{{ $namespace->name }}" value="{{ $namespace->id }}" {{ $namespace->id == session('defaultNamespaceId') ? 'selected' : ''}}>{{namespace_label($namespace)}}</option>
                    @endforeach


                </select>
            </h3>
            <div class="content">
            <div class="row">
         @if(count($topics))
          <div class="tree home-page-tree col-sm-12">
                    <ul class="mainouter" id="load-data">
                      <?php 
                      
                        $createCamp = 1;
                        session(['topic_on_page' => 0]);
                        $as_of_time = time();
                        if (isset($_REQUEST['asof']) && $_REQUEST['asof'] == 'bydate') {
                            $as_of_time = strtotime($_REQUEST['asofdate']);
                        }else if(session()->has('asofDefault') && session('asofDefault') == 'bydate' && !isset($_REQUEST['asof'])){
                            $as_of_time = strtotime(session('asofdateDefault'));
                        }
                        session(['topic_on_page' => 0]);
                        $filter = isset($_REQUEST['filter']) && is_numeric($_REQUEST['filter']) ? $_REQUEST['filter'] : 0.000;
        
                        if(session('filter')==="removed") {
                            $filter = 0.000;	
                        } else if(isset($_SESSION['filterchange'])) {
                            $filter = $_SESSION['filterchange'];
                        }

                         $dotCount = 0;
                         $dotCountForG8 = 0;

                      ###  if current date is greater than cron run date ###
                        if($previous > 0){ 
                           ## check that topics are exist in data
                          if(count($topics['data']['topic']) > 0){
                            foreach($topics['data']['topic'] as $k=>$topic){
                                $url = \App\Model\Camp::getTopicCampUrl($topic['topic_id'], 1);
                                if ($topic['topic_score'] < $filter) {
                                    continue;
                                }
                        ?>
                         
                         <li >
                            <span class="parent" title="Expand this branch">
                                <a style="cursor:pointer;" href="@php echo  $url; @endphp" bis_skin_checked="1"><i class="fa fa-arrow-right"></i> </a></span>
                            <div class="tp-title" bis_skin_checked="1">
                                <a style="" href="@php echo  $url; @endphp" bis_skin_checked="1">
                                    <?php echo $asOf == "review" ? $topic['tree_structure_1_review_title']: $topic['topic_name'] ; ?>
                                </a>
                                <div class="badge" bis_skin_checked="1">@php echo $topic['topic_score'] @endphp</div>
                            </div>
                         </li>
                        
                        <?php  }  ?>

                         <?php   
                         
                             ### Pagination ###   
                              $disabled = isset($page_no) && $page_no == 1 ? 'disabled' : '';
                              $next = $page_no+1;
                              $previous = $page_no - 1;
                              $nexDisabled = $next >= $topics['data']['number_of_pages'] ? 'disabled':'';
                         ?>
                        <ul class="pagination">

                           <!-- Disable the previous button if page is 1 or less -->
                            @if ($page_no > 1)
                                <li> <a href="@php echo url('/').'?page='.$previous;  @endphp">«</a></li>
                            @else
                                <li class="@php echo $disabled @endphp"><span>«</span></li>
                            @endif
    
                            <?php 
                               for($idx=1; $idx <= $topics['data']['number_of_pages']; $idx++){
                                    $active = $page_no == $idx ? 'active': '';
                             ?>

                               <!-- Start of if the page less than 8 -->
                               <!-- #################################### -->

                                @if($page_no < 8)
                                
                                    <!-- if the current selected page is less than 12 then show all in a row -->
                                    @if($topics['data']['number_of_pages'] <= 12 )
                                          <li><a href="@php echo url('/').'?page='.$idx;  @endphp"><?php echo $idx; ?></a></li>
                                    @endif

                                    <!-- 
                                        if the current selected page is greater than 12 
                                        then show first 8 and last two number
                                    
                                     -->
                                     @if($topics['data']['number_of_pages'] > 12)

                                         @if($idx <= 8)
                                            @if ($page_no == $idx)
                                               <li class="@php echo $active; @endphp"> <span><?php echo $idx; ?></span></li>
                                            @else
                                               <li><a href="@php echo url('/').'?page='.$idx;  @endphp"><?php echo $idx; ?></a></li>
                                            @endif
                                         @endif

                                         @if($idx > 8 && $idx <= $topics['data']['number_of_pages']-2 && $dotCount == 0 )
                                             @php  
                                                  $dotCount = 1 ;
                                             @endphp
                                              <li><span>...<span></li>
                                         @endif

                                         @if($idx >= $topics['data']['number_of_pages']-1)
                                             <li><a href="@php echo url('/').'?page='.$idx;  @endphp"><?php echo $idx; ?></a></li>
                                         @endif

                                     @endif
                                      
                                 @endif

                                 <!-- End of if page is less than 8 logic -->
                                 <!-- #################################### -->
                                  
                                 <!-- Start of if the current page is greater than 8 -->
                                  @if($page_no >= 8)

                                   <!-- show first two -->
                                    @if($idx <=2 )
                                          <li><a href="@php echo url('/').'?page='.$idx;  @endphp"><?php echo $idx; ?></a></li>
                                    @endif

                                     <!-- show dot -->
                                    @if($idx >2 && $idx <=5 && $dotCount == 0 )
                                             @php  
                                                  $dotCount = 1 ;
                                             @endphp
                                              <li><span>...<span></li>  
                                    @endif

                                     <!-- show 2 number less and 2 number greater than current page -->
                                    @if($idx > $page_no-3 && $idx < $page_no+3 && $idx <= $topics['data']['number_of_pages']-2 )
                                          @if ($page_no == $idx)
                                               <li class="@php echo $active; @endphp"> <span><?php echo $idx; ?></span></li>
                                          @else
                                               <li><a href="@php echo url('/').'?page='.$idx;  @endphp"><?php echo $idx; ?></a></li>
                                          @endif
                                    @endif

                                   <!-- if the remaining page less than equal to 2 then show dots again -->
                                    @if($idx >= $page_no+3 && ($topics['data']['number_of_pages']-($page_no+3) >= 2) && $dotCountForG8 ==0)
                                             @php  
                                                  $dotCountForG8 = 1 ;
                                             @endphp
                                              <li><span>...<span></li>  
                                    @endif

                                   <!-- Show last two -->
                                    @if($idx >= $topics['data']['number_of_pages']-1)
                                        @if ($page_no == $idx)
                                               <li class="@php echo $active; @endphp"> <span><?php echo $idx; ?></span></li>
                                          @else
                                               <li><a href="@php echo url('/').'?page='.$idx;  @endphp"><?php echo $idx; ?></a></li>
                                          @endif
                                    @endif

                                 @endif

                                 <!-- End of greater than 8 logic -->
                                <!--  ################################# -->
                            <?php } ?> 
                            <li>
                                @if ($next <= $topics['data']['number_of_pages'])
                                  <a href="@php echo url('/').'?page='.$next;  @endphp">»</a>
                                @else
                                 »
                                @endif
                            </li>
                       </ul>
                    
                        <?php  } else { ?>

                          <p>There are no topics on this page, given the current algorithm and filter setting.</p>
                         
                        <?php 
                           } 
                        } 
                          ###  End of above condition ###
                        
                          ###   if user select the previous date than cron run date then this part will execute ###
                        else 
                        { 
                            
                        ?>
                        
                         @foreach($topics as $k=>$topic)

                        <?php
                        
                         $topicData = \App\Model\Topic::where('topic_num','=',$topic->topic_num)->where('go_live_time', '<=', $as_of_time)->latest('submit_time')->get();
                         $url = \App\Model\Camp::getTopicCampUrl($topic->topic_num,$topic->camp_num);
                        // $campData = \App\Model\Camp::where('topic_num',$topic->topic_num)->where('camp_num',$topic->camp_num)->where('go_live_time', '<=', $as_of_time)->latest('submit_time')->first();
                        // $topic_name_space_id = isset($topicData[0]) ? $topicData[0]->namespace_id:1;
                        // $topic_name = isset($topicData[0]) ? $topicData[0]->topic_name:'';
                        // $request_namesapce = session('defaultNamespaceId', 1); 
                        if ($topic->score < $filter) {
                            $val = session('topic_on_page');
                            session(['topic_on_page' => $val+1]);
                            continue;
                        }
                       ?>
                                             
                        <li >
                            <span class="parent" title="Expand this branch">
                                <a style="cursor:pointer;" href="@php echo  $url; @endphp" bis_skin_checked="1"><i class="fa fa-arrow-right"></i> </a></span> </span>
                            <div class="tp-title" bis_skin_checked="1">
                                <a style="" href="@php echo  $url; @endphp" bis_skin_checked="1">
                                    <?php echo $topicData[0]->topic_name; ?>
                                </a>
                                <div class="badge" bis_skin_checked="1">@php echo $topic->score; @endphp</div>
                            </div>
                        </li>

                         {{-- {!! $campData->campTreeHtml($createCamp,false,false,'fa-arrow-right') !!} --}}
                       
                         <?php $createCamp = 0;?>
                       @endforeach
                    <a id="btn-more" class="remove-row" data-id="{{ $topic->id }}"></a>

                    </ul>
                    @if(session('topic_on_page') >=20)
                     <p>There are no topics on this page, given the current algorithm and filter setting.</p>
                    @endif
                    {!! $topics->links() !!}
                    <?php } ?>
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
            window.location.href="{{ url('/') }}"+"?page="+pageNo;
{{--            @if(env('APP_DEBUG'))--}}
{{--                 window.location.href="{{ url('/') }}"+"?page="+pageNo;//window.location.reload();--}}
{{--            @else--}}
{{--            try{--}}
{{--                window.location.href="{{ url('/') }}"+$(element).find('option:selected').attr('data-namespace')+"?page="+pageNo;--}}
{{--            }catch(err){--}}
{{--                window.location.href="{{ url('/') }}"+"?page="+pageNo;--}}
{{--            }--}}
{{--            @endif--}}
        }
    });
}

$(document).ready(function() {
    $('ul.pagination li:not(.active,.disabled)').click(function(e) {
        e.stopPropagation();
        // console.log($(this).children('a'));
        $(this).children('a')[0].click();
    });
})
</script>

@endsection

<style> 
.tree li::before, .tree li::after  {
    border: none !important;
} 
.tree li .tp-title {
    margin-left: 10px !important;
}
.tree ul.mainouter ul {
    padding-left: 10px !important;
}
ul.pagination li:not(.active,.disabled) {
    cursor: pointer;
}
</style>