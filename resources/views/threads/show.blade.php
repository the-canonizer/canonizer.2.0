@extends('layouts.forum')

@section('content')

    <div class="container">

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                                          
                    <div class="panel-heading">
                        <a href="#">{{ $threads->creator->first_name }}  </a> started->
                        {{ $threads->title }}</div>
                    <div class="panel-body">
                        {{ $threads->body}}
                    </div>
                  
                </div>
            </div>
        </div>

<!-- Replies To Thread -->
        
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @foreach ($threads->replies as $reply)
                    @include('threads.replies')
                @endforeach
            </div>
        </div>

        @if(auth()->check())

            <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <form method="POST" action="/forum/{{ $topicname }}/{{ $campnum }}/threads/{{ $threads->id }}/replies">
                            {{ csrf_field() }}

                            <div class="form-group">
                               <textarea name="body" id="body" class="form-control" placeholder="Reply to thread Here"> </textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
            </div>
            @else
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    Please <a href="{{ url('/login') }}">Sign In</a> to comment on this Thread
                </div>
            </div>
            
        @endif
    </div>

@endsection