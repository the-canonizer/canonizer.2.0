@extends('layouts.app')

@section('content')
<div class="camp top-head">
  <h3><b>Canonizer Forum Details</b></h3>
  <h3><b>Topic: {{ $topicGeneralName }}</b></h3>
  <h3><b>Camp:</b>
    @php
    echo $parentcamp
    @endphp
  </h3>
</div>
<div class="right-whitePnl">
  <div class="panel panel-group">
    <div class="panel panel-title">
      <h5>List of All Camp Threads</h5>
    </div>
    @if(count($threads) >= 0)
    <div>
      <a class="btn btn-primary btn-margin" href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads"> 
        All Threads
      </a>

      @if(Auth::check())
      <a class="btn btn-primary btn-margin" href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads?by=me"> 
        My Threads 
      </a>

      <a class="btn btn-primary btn-margin" href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads?by=participate">
        My Participation 
      </a>

      <a class="btn btn-primary btn-margin" href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads?by=most_replies">
        Top 10 
      </a>
      @endif
      <a class="btn btn-primary btn-margin" href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads/create"> 
        Create Thread 
      </a>
      
    </div>
    @endif
    <br>

    <div class="panel-body">
      @if(count($threads) > 0)
      <table class="table">       
        <thead>
          <th>Thread Name</th>
          <th>Replies</th>
          <th>Most Recent Post Date</th>
        </thead>
        <tbody>
          @foreach ($threads as $thread)
          <?php $date  = $thread->updated_at;
              if($thread->replies->count()){
                $date  = $thread->replies[0]->updated_at;
              }
            ?>
          <tr>
            <td class="thread-title">
              @if ($myThreads) 
                <a href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads/{{ $thread->id }}/edit">
                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                </a>
              @endif
              <a href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads/{{ $thread->id }}" >
                {{ $thread->title }}
              </a>
            </td>
            <td>{{ $thread->replies->count() }}</td>
            <td>
              @if ( $thread->replies->count() )
                @php
                  $supportUrlPortion = $thread->replies[0]->user_id . '/' . '?topicnum=' . $thread->topic_id . '&campnum=' . $thread->camp_id . '&namespace=' . $namespace_id . '#camp_' . $thread->topic_id . '_' . $thread->camp_id;
                  $userSupportUrl = url('user/supports/' . $supportUrlPortion);
                @endphp
                <a href="{{ $userSupportUrl }}">{{ $thread->replies[0]->owner->nick_name }}</a> replied {{ Carbon\Carbon::createFromTimestamp( $date )->diffForHumans() }}
              ({{ to_local_time( $date )  }})
              @else
                This thread doesn't have any posts yet.
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @else 
        <hr>
        <p>
          @if ($request_by=='most_replies')
            No Top 10 threads available for this topic.
          @else
            No threads available for this topic.
          @endif

          Start <a href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads/create">New Thread.
          </a>
        </p>
        @endif
      <!-- For Pagination -->
      @if (count($threads) > 0)
      {{ $threads->links() }}
      @endif

      @if ($message = Session::get('success'))
      <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
      </div>
      @endif

    </div>

  </div>
</div>
@endsection