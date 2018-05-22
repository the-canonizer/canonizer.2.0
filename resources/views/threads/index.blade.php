@extends('layouts.app')

@section('content')
	<div class="camp top-head">
    <h3><b>Canonizer Forum Details:</b></h3>
    <h3><b>Topic Name  : {{ $topicGeneralName }}</b></h3>
    <h3><b>Camp Name  : {{ $campname }}</b></h3>
	<h3><b>Camp:</b>
		@php
			echo $parentcamp
		@endphp
	</h3>
	</div>
    <div class="right-whitePnl">
      			<div class="panel panel-group">
                    <div class="panel panel-title">
                        <h5>List of all the Threads</h5>
                    </div>

                    <div class="panel-body">
                        <table class="table">
                            @foreach ($threads as $thread)
                            <article></article>
                                <h5>
                                    <ul class = "list-group">
                                        <li class = "list-group-item">
                                            <a href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads/{{ $thread->id }}">
                                            {{ $thread->title }}
                                            </a>
                                        </li>

                                    </ul>
                                </h5>
                                {{--  <div class="body"> {{ $thread->body }} </div>  --}}
                            </article>
                            @endforeach
                        </table>

                    </div>

                </div>
    </div>
@endsection
