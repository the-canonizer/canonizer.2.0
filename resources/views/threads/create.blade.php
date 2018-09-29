{{--  @extends('layouts.forum')  --}}

@extends('layouts.app')

@section('content')

            <div class="camp top-head">
                <hr>
                <h3>
                    <b>
                        <a
                        href="{{ URL::to('/') }}/forum/{{ $topicname }}/{{ $campnum }}/threads">
                            &laquo; List of All Threads
                        </a>
                    </b>
                </h3>
    			<h3>Create a new thread for Topic : {{ $topicGeneralName }}</h3>
			</div>
            <div class="right-whitePnl">
            	 <div class="panel panel-group">

                    <div class="panel-body">
                        <form method="POST" action="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads">
                            {{ csrf_field() }}

                            <div class="form-group">

                                <label for="title">Title of Thread: <span style="color:red">*</span> </label>

                                <input type="text" class="form-control" id="title" placeholder="Title" name="title">

                            </div>

                            {{--  <div class="form-group">

                                <label for="body">Content</label>

                                <textarea name="body" id="body" class="form-control" rows="5" placeholder="Write Your Content Here"></textarea>

                            </div> --}}

							<div class="form-group">
				                <label for="camp_name">Nick Name <span style="color:red">*</span><p class="help-block">(Once you pick a nick name, for anything about a topic, you must always use the same nick name.)</p></label>
				                <select name="nick_name" id="nick_name" class="form-control">
				                    @foreach($userNicknames as $nick)
				                    <option value="{{ $nick->id }}">{{ $nick->nick_name}}</option>
				                    @endforeach

				                </select>
				                 {{-- @if ($errors->has('nick_name')) <p class="help-block">{{ $errors->first('nick_name') }}</p> @endif --}}
								  <?php if(count($userNicknames) == 0) { ?>
								  <a href="<?php echo url('settings/nickname');?>">Add New Nick Name </a>
								  <?php } ?>
				            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>

                            @if (count($errors))
                                <ul class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif

                        </form>

                    </div>

                </div>

            </div>


@endsection
