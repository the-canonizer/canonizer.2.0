@extends('layouts.app')

@section('content')
@if(Session::has('warning'))
  <div class="alert alert-danger">
      <strong>Warning! </strong>{{ Session::get('warning')}}    
  </div>
@endif
<div class="camp top-head">
  <hr>
  <h3><b>Topic: <a href="{{ URL::to('/')}}/topic/{{ $topicname }}"> {{ $topicGeneralName }}</a></b></h3>
  <h3><b>Camp:</b>
    @php
    echo $parentcamp
    @endphp
  </h3>
  <h3>
    <b> Thread: 
      <a href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads" style="color:#08b608;">{{ $threads->title }} </a>/
      <a href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads">
        &laquo; List of All Camp Threads</a>

    </b>
    <hr />

<span class="forum-thread-title"><a href="{{$userUrl}}">{{ $threads->creator->nick_name }}
</a> started this thread : "{{ $threads->title }}"</span>
  </h3>
  <?php
      $topic = App\Model\Topic::getLiveTopic($threads->topic_id); 
      $namespace_id = 1;
      if(isset($topic->topic_num)){
          $namespace_id = $topic->namespace_id;
      }
      $userUrl = route('user_supports',$threads->creator->id)."?topicnum=".$threads->topic_id."&campnum=".$threads->camp_id."&namespace=".$namespace_id."#camp_".$threads->topic_id."_".$threads->camp_id;
  ?>


</div>
<div class="right-whitePnl">


  <div style="margin-bottom:20px;">
    <div class="panel panel-default">
      <div class="panel-body">
        <span> Thread Created at {{ to_local_time($threads->created_at) }}
          by <a href="{{$userUrl}}"> {{ $threads->creator->nick_name }} </a>
        </span><br />

        <span>
          Number of Post in this thread : {{ $threads->replies()->count() }}
        </span>

      </div>

  </div> 

  @if(auth()->check())

  <form method="POST" id="postForm"
    action="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads/{{ $threads->id }}/replies">
    {{ csrf_field() }} 
    @if($reply_id != null)
      <input name="reply_id" type="hidden" value="{{$reply_id}}"  required>
    @else
      <input name="reply_id" type="hidden" value="" required>
    @endif 
    <div class="form-group">
      <br>
      <textarea name="body" id="body" class="form-control" placeholder="Reply to thread Here" rows="5">@if(sizeof(old()) > 0) {{ old('body') }} @elseif($reply_id != null){{ $replies->body }}@endif</textarea>
        @if ($errors->has('body')) <p class="help-block">The reply field is required.</p> @endif


    </div>

    <div class="form-group">
      <label for="camp_name">Nick Name <span style="color:red">*</span>
        <p class="help-block">(Once you pick a nick name, for anything about a topic, you must always use the same nick
          name.)</p>
      </label>
      <select name="nick_name" id="nick_name" class="form-control">
        @foreach($userNicknames as $nick)
        <option value="{{ $nick->id }}" @if(sizeof(old()) > 0 && old('nick_name') == $nick->id) selected @endif>{{ $nick->nick_name}}</option>
        @endforeach
      </select>
      @if ($errors->has('nick_name')) <p class="help-block">{{ $errors->first('nick_name') }}</p> @endif
      <?php if(count($userNicknames) == 0) { ?>
      <p style="color:red" class="help-block">Note:You have not yet added a nick name. A public or private nick name
        must be added then selected here when contributing.</p>
      <a href="<?php echo url('settings/nickname');?>">Add New Nick Name </a>
      <?php } ?>
    </div>

    <button type="submit" id="postSubmitBtn" class="btn btn-primary"><?php if($reply_id==null) { ?> Submit <?php } else {?> Update <?php }?></button>

  </form>
  <!-- Added Here -->

  <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.js"></script>
  <script>
    $(document).ready(function () {
      $('#postSubmitBtn').css('cursor', 'pointer');
        $("#postForm").submit(function (e) {          
            //disable the submit button
            $("#postSubmitBtn").attr("disabled", true);

            return true;

        });
    });
</script>
  <script>
    $(document).ready(function () {
      $('#body').summernote({
        tabsize: 2,
        height: 150,
        minHeight: null,
        maxHeight: null,
        focus: true,
        disableDragAndDrop: true,
        placeholder: 'Post Your Message Here',
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'italic', 'underline', 'clear']],
          ['fontname', ['fontname']],
          ['color', ['color']],
          ['para', ['ul', 'ol']],
          ['insert', ['link', 'hr']],
          ['view', ['fullscreen', 'codeview']],
          ['help', ['help']]
        ],
      });
    });
  </script>
  <!-- Upto Here -->
  @if(session()->has('message'))
  <div class="alert alert-success">
    {{ session()->get('message') }}
  </div>
  @endif

  @else
  Please <a href="{{ url('/login') }}">Sign In</a> to comment on this Thread
  @endif
  @if($reply_id==null)
    <!-- Replies To Thread -->
    <div class="pagination">
      <a class="active item">
        <ul class="list-group">
          @foreach ($replies as $reply)
          <li class="list-group-item">
            @include('threads.replies')
          </li>
          @endforeach
        </ul>
      </a>
    </div>
    {{ $replies->links() }}
  @endif

</div>

</div>
@endsection