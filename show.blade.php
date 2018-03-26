@extends('layouts.forum')

@section('content')

    <div class="container">

        <div class="row">

            <div class="col-md-8 col-md-offset-1">

                <div class="panel panel-default">
                                          
                    <div class="panel-heading">
                        <a href="#">{{ $threads->creator->first_name }}  </a> started->
                        {{ $threads->title }}
                    </div>
                    
                    <div class="panel-body">
                        {{ $threads->body}}
                    </div>
                  
                </div>

<!-- Replies To Thread -->        
                <!-- ?php $replies = $threads->replies()->paginate('10'); ?-->

                @foreach ($replies as $reply)
                    @include('threads.replies')
                @endforeach

                {{ $replies->links() }}

                @if(auth()->check())

                    <form method="POST" action="/forum/{{ $topicname }}/{{ $campnum }}/threads/{{ $threads->id }}/replies">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <textarea name="body" id="body" class="form-control" placeholder="Reply to thread Here"> </textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>

                    </form>
                @else
                    Please <a href="{{ url('/login') }}">Sign In</a> to comment on this Thread
                @endif

            </div>
            
            <div class="col-md-3">

                <div class="panel panel-default">
                                        
                    <div class="panel-body">
                        <p> Thread Post at {{ $threads->created_at->diffForHumans() }} 
                            by <a href="#"> {{ $threads->creator->first_name }} </a> 
                        </p>

                        <p> 
                            Number of Post in this thread : {{ $threads->replies()->count() }}                            
                        </p>

                    </div>
                    
                </div>

            </div>

        </div>

    </div>
@endsection