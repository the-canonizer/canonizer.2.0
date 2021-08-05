@extends('layouts.app')

@section('content')
<div class="right-whitePnl" style="height: 10px; width: 68%;">
  <h3 class="text-center">Edit title of the thread</h3>
  <form class="form-inline mt-5" method="POST" action="{{ URL::to('/')}}/forum/{{ $topicName }}/{{ $campNum }}/threads/{{ $thread->id }}/edit">
    {{ csrf_field() }}
    <div class="form-group  col-sm-8 mb-2">
      <label for="title"> <span style="color:red">*</span> </label> &nbsp;
      <input type="text" 
        onkeydown="restrictTextField(event,100)" 
        class="form-control  col-sm-10" 
        id="title" 
        placeholder="Title of Thread ( Limit 100 Chars )"
        name="title" required
        value="{{ $thread->title }}"
      >
      <input name="thread_id" type="hidden" value={{ $thread->id }} required>
  
    </div>
    <div class="form-group">
      <button type="submit" class="btn btn-primary mb-2">Submit</button>
    </div>
  </form>
  @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
  @endif
  <a style="margin: 25px; text-decoration: underline" href="{{ URL::to('/')}}/forum/{{ $topicName }}/{{ $campNum }}/threads?by=me">List of All Camp Threads</a>
</div>



@endsection