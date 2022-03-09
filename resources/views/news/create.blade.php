@extends('layouts.app')
@section('content')
<div class="page-titlePnl">
    <h1 class="page-title">Add News</h1>
</div> 

@if(Session::has('error'))
<div class="alert alert-danger">
    <strong>Error!</strong>{{ Session::get('error')}}    
</div>
@endif

@if(Session::has('success'))
<div class="alert alert-success">
    <strong>Success!</strong>{{ Session::get('success')}}    
</div>
@endif


<div class="right-whitePnl">
   <div class="row col-sm-12 justify-content-between">
    <div class="col-sm-6 margin-btm-2">
        <form action="{{ url('/newsfeed/save')}}" onsubmit="submitForm(this)" method="post" id="topicForm">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="topic_num" value="{{$topicnum}}"/>
            <input type="hidden" name="camp_num" value="{{$campnum}}" />
            <input type="hidden" name="topic_slug" value="{{$topic}}" />
           
            <div class="form-group">
                <label for="topic name">Display Text ( Limit 256 Chars ) <span style="color:red">*</span></label>
                <textarea type="text" onkeydown="restrictTextField(event,256)" name="display_text" class="form-control" id="display_text">{{ old('display_text')}}</textarea>
				@if ($errors->has('display_text')) <p class="help-block">{{ $errors->first('display_text') }}</p> @endif
            </div>            
            <div  class="form-group">
                <label for="namespace">Link ( Limit 2000 Chars ) <span style="color:red">*</span></label>
                <input type="text"  onkeydown="restrictTextField(event,2000)" maxlength="2000" name="link" class="form-control" id="link" value="{{old('link')}}">
                @if ($errors->has('link')) <p class="help-block">{{ $errors->first('link') }}</p> @endif
	    </div>
            
            <div  class="form-group">
                <input type="checkbox" name="available_for_child" value="1">Available for child camps
            </div>
            
            <button type="submit" id="submit" class="btn btn-login">Create News</button>
            <?php 
               $link = \App\Model\Camp::getTopicCampUrl($topicnum,$camp_num,time());
            ?>
            <a href="<?php echo $link; ?>" class="btn btn-default">Cancel</a>
        </form>
    </div>
 </div>   
</div>  <!-- /.right-whitePnl-->
@endsection

