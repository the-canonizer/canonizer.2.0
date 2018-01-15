@extends('layouts.forum')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Canonizer Forum</div>
                    <div class="panel-body">
                        @foreach ($threads as $thread)
                            <article>
                                <h4>
                                    <a href="/forum/{{ $topicname }}/{{ $campnum }}/threads/{{ $thread->id }}">
                                        {{ $thread->title }}
                                    </a>
                                </h4>
                                <div class="body"> {{ $thread->body }} </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection