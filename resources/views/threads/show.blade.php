@extends('layouts.app')

@section('content')

    <div class="camp top-head">
        <hr>
        <h3>
            <b>
                <a href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads">
                    &laquo; List of All Threads</a>
                <hr>
                <a href="#">{{ $threads->creator->nick_name }}
                </a> started this thread : "{{ $threads->title }}"
            </b>
        </h3>
	</div>
    <div class="right-whitePnl">


            <div style="margin-bottom:20px;">

                <div class="panel panel-default">
                   <div class="panel-body">
                        <span> Thread Post at {{ $threads->created_at->diffForHumans() }}
                            by <a href="#"> {{ $threads->creator->nick_name }} </a>
                        </span><br />

                        <span>
                            Number of Post in this thread : {{ $threads->replies()->count() }}
                        </span>

                    </div>
                   {{--  <div class="panel-body">
                        {{ $threads->body}}
                    </div>  --}}

                </div>

<!-- Replies To Thread -->
                <div class="pagination">
                    <a class="active item">
                        <ul class ="list-group">
                            @foreach ($replies as $reply)
                            <li class = "list-group-item">
                                @include('threads.replies')
                            </li>
                            @endforeach
                        </ul>
                    </a>

                </div>


                {{ $replies->links() }}

                @if(auth()->check())

                    <form method="POST" action="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads/{{ $threads->id }}/replies">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <br>
                            <textarea name="body" id="body" class="form-control" placeholder="Reply to thread Here" rows="5"></textarea>
                            @if ($errors->has('body')) <p class="help-block">The reply field is required.</p> @endif
                        </div>

						<div class="form-group">
							<label for="camp_name">Nick Name <span style="color:red">*</span> <p class="help-block">(Once you pick a nick name, for anything about a topic, you must always use the same nick name.)</p></label>
							<select name="nick_name" id="nick_name" class="form-control">
								@foreach($userNicknames as $nick)
								<option value="{{ $nick->id }}">{{ $nick->nick_name}}</option>
								@endforeach

							</select>
							 @if ($errors->has('nick_name')) <p class="help-block">{{ $errors->first('nick_name') }}</p> @endif
							 <a href="<?php echo url('settings/nickname');?>">Add New Nick Name </a>
						</div>

                        <button type="submit" class="btn btn-primary">Submit</button>

                    </form>

                    @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session()->get('message') }}
                        </div>
                    @endif

                @else
                    Please <a href="{{ url('/login') }}">Sign In</a> to comment on this Thread
                @endif

            </div>

    </div>
@endsection
