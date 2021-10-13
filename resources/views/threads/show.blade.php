@extends('layouts.app')

@section('content')

<div class="camp top-head">
  <hr>
  <h3>
    <b>
      <a href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads">
        &laquo; List of All Camp Threads</a>

    </b>
  </h3>
  <?php
      $topic = App\Model\Topic::getLiveTopic($threads->topic_id); 
      $namespace_id = 1;
      if(isset($topic->topic_num)){
          $namespace_id = $topic->namespace_id;
      }
      $userUrl = route('user_supports',$threads->creator->id)."?topicnum=".$threads->topic_id."&campnum=".$threads->camp_id."&namespace=".$namespace_id."#camp_".$threads->topic_id."_".$threads->camp_id;
  ?>
  <h3><b>Camp:</b>
    @php
    echo $parentcamp
    @endphp
    <hr />

    <span class="forum-thread-title"><a href="{{$userUrl}}">{{ $threads->creator->nick_name }}
    </a> started this thread : "{{ $threads->title }}"</span>
  </h3>


</div>
<div class="right-whitePnl">


  <div style="margin-bottom:20px;">

    <div class="panel panel-default">
      <div class="panel-body">
        <span> Thread Created at {{ to_local_time($threads->created_at)}}
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

    <div class="form-group">
      <br>
      <textarea name="body" id="body" class="form-control" placeholder="Reply to thread Here" rows="5"></textarea>
      @if ($errors->has('body')) <p class="help-block">The reply field is required.</p> @endif
    </div>

    <div class="form-group">
      <label for="camp_name">Nick Name <span style="color:red">*</span>
        <p class="help-block">(Once you pick a nick name, for anything about a topic, you must always use the same nick
          name.)</p>
      </label>
      <select name="nick_name" id="nick_name" class="form-control">
        @foreach($userNicknames as $nick)
        <option value="{{ $nick->id }}">{{ $nick->nick_name}}</option>
        @endforeach

      </select>
      @if ($errors->has('nick_name')) <p class="help-block">{{ $errors->first('nick_name') }}</p> @endif
      <?php if(count($userNicknames) == 0) { ?>
      <p style="color:red" class="help-block">Note:You have not yet added a nick name. A public or private nick name
        must be added then selected here when contributing.</p>
      <a href="<?php echo url('settings/nickname');?>">Add New Nick Name </a>
      <?php } ?>
    </div>

    <button type="submit" id="postSubmitBtn" class="btn btn-primary">Submit</button>

  </form>
  <!-- Added Here -->

  <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.js"></script>
  <script>
    $(document).ready(function () {

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

  

</div>

</div>
@endsection