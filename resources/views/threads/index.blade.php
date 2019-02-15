@extends('layouts.app')

@section('content')
	<div class="camp top-head">
    <h3><b>Canonizer Forum Details</b></h3>
    <h3><b>Topic Name  : {{ $topicGeneralName }}</b></h3>
	<h3><b>Camp Name :</b>
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

					<div>
						<a class="btn btn-primary" href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads"> All Threads </a>

						@if(Auth::check())
							<a class="btn btn-primary" href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads?by=me"> My Threads </a>

							<a class="btn btn-primary" href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads?by=participate"> My Participation </a>

							<a class="btn btn-primary" href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads?by=most_replies"> Top 10 </a>

							<a class="btn btn-primary" href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads/create"> Create Thread </a>

						@endif
					</div>
					<br>

                    <div class="panel-body">
                        <table class="table">

							@if (count($threads) == 0)
								<hr>
								<p>No threads available for this topic.
									Start <a href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads/create">New Thread.
									</a>
								</p>
							@endif

                            @foreach ($threads as $thread)
                            <article>

								<div class="level" style="word-break:break-all">
									<h5>
	                                    <ul class = "list-group">
	                                        <li class = "list-group-item">
	                                            <a href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads/{{ $thread->id }}">
	                                            {{ $thread->title }}
	                                            </a>

												@if (  $participateFlag  == 1)
													<hr>
													{!! html_entity_decode($thread->body) !!}

												@else
													<strong style="float: right">  {{ $thread->replies->count() }} {{ str_plural('reply', $thread->replies->count() ) }} </strong>
												@endif


	                                        </li>

	                                    </ul>

	                                </h5>

								</div>

                                {{--  <div class="body"> {{ $thread->body }} </div>  --}}
                            </article>
                            @endforeach

							<!-- For Pagination -->
							@if (count($threads) > 0)
								{{ $threads->links() }}
							@endif

                        </table>

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
