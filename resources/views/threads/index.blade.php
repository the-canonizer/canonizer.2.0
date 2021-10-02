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
    @if(count($threads))
    <div>
      <a class="btn btn-primary" href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads"> 
        All Threads
      </a>

      @if(Auth::check())
      <a class="btn btn-primary" href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads?by=me"> 
        My Threads 
      </a>

      <a class="btn btn-primary" href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads?by=participate">
        My Participation 
      </a>

      <a class="btn btn-primary" href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads?by=most_replies">
        Top 10 
      </a>
      @endif
      <a class="btn btn-primary" href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads/create"> 
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
            <td>
              @if ($myThreads) 
                <a href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads/{{ $thread->id }}/edit">
                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                </a>
              @endif
              <a href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads/{{ $thread->id }}">
                {{ $thread->title }}
              </a>
            </td>
            <td>{{ $thread->replies->count() }}</td>
            <td>replied on {{ date('d/m/Y, H:i:s', strtotime($date)) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @else 
        <hr>
        <p>No threads available for this topic.
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