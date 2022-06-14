{{-- @extends('layouts.forum')  --}}

@extends('layouts.app')

@section('content')

<div class="camp top-head">
  <hr>
  <h3><b>Topic: <a href="{{ URL::to('/')}}/topic/{{ $topicname }}"> {{ $topicGeneralName }}</a></b></h3>
  <h3><b>Camp:</b>
  @php
  echo $parentcamp
  @endphp
  </h3>
  <h3>
  <b> Thread: 
  <a href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads" style="color:#08b608;">{{ $threads->title }} </a>/
  <a href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads">
  &laquo; List of All Camp Threads</a>
  </b>
  <h3>Create a new thread for Camp : @php echo $parentcamp @endphp
  </h3>
</div>
<div class="right-whitePnl">
  <div class="panel panel-group">
     @if(Session::has('success'))
        <div class="alert alert-success">
            <strong>Success! </strong>{{ Session::get('success')}}    
        </div>
      @endif
    <div class="panel-body">
      <form id="threadForm" method="POST" action="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads">
        {{ csrf_field() }}

        <div class="form-group">

          <label for="title">Title of Thread ( Limit 100 Chars ) <span style="color:red">*</span> </label>

          <input type="text" onkeydown="restrictTextField(event,100)" class="form-control" id="title"
            placeholder="Title" name="title">

        </div>

        <div class="form-group">
          <label for="camp_name">Nick Name <span style="color:red">*</span>
            <p class="help-block">(Once you pick a nick name, for anything about a topic, you must always use the same
              nick name.)</p>
          </label>
          <select name="nick_name" id="nick_name" class="form-control">
            @foreach($userNicknames as $nick)
            <option value="{{ $nick->id }}">{{ $nick->nick_name}}</option>
            @endforeach

          </select>
          {{-- @if ($errors->has('nick_name')) <p class="help-block">{{ $errors->first('nick_name') }}</p> @endif --}}
          <?php if(count($userNicknames) == 0) { ?>
          <p class="help-block" style="color:red">Note:You have not yet added a nick name. A public or private nick name
            must be added then selected here when contributing.</p>
          <a href="<?php echo url('settings/nickname');?>">Add New Nick Name </a>
          <?php } ?>
        </div>

        <div class="form-group">
          <button type="submit" id="threadSubmitBtn" class="btn btn-primary">Submit</button>
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
    @if ($message = Session::get('error'))
    <div class="alert alert-danger alert-block">
      <button type="button" class="close" data-dismiss="alert">×</button>
      <strong>{{ $message }}</strong>
    </div>
    @endif

  </div>

</div>

<script>
    $(document).ready(function () {

        $("#threadForm").submit(function (e) {          
            //disable the submit button
            $("#threadSubmitBtn").attr("disabled", true);
            return true;

        });
    });
</script>


@endsection