@extends('layouts.app')

@section('content')
<div class="right-whitePnl">
  <h3 class="text-center">Edit title of the thread</h3>
  @if(Session::has('success'))
    <div class="alert alert-success">
        <strong>Success! </strong>{{ Session::get('success')}}    
    </div>
  @endif
  <form class="form-inline mt-5" method="POST" action="{{ URL::to('/')}}/forum/{{ $topicName }}/{{ $campNum }}/threads/{{ $thread->id }}/edit">
    {{ csrf_field() }}
    <div class="form-group  col-sm-8 mb-2">
      <label for="title" class="edit_label_star"> 
        <span style="color:red;margin-right:5px;">* </span> </label>
      <input type="text" 
        onkeydown="restrictTextField(event,100)" 
        class="form-control  col-sm-10" 
        id="title" 
        placeholder="Title of Thread ( Limit 100 Chars )"
        name="title" required
        value="{{ $thread->title }}"
      >
      <input name="thread_id" type="hidden" value={{ $thread->id }} required>
      <input name="thread_title_name" type="hidden" value="{{ $thread->title }}">
  
    </div>
    <div class="form-group col-sm-4">
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