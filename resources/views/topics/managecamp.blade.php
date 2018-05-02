@extends('layouts.app')
@section('content')

<div class="camp top-head">
    <h3><b>Topic:</b>  {{ $topic->title}}</h3>
    <h3><b>Camp:</b> {!! $parentcamp !!}</h3>  
</div>


<div class="page-titlePnl">
    <h1 class="page-title">Submit update</h1>
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
    <div class="col-sm-5 margin-btm-2">
        <form action="{{ route('camp.save')}}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="topic_num" name="topic_num" value="{{ $topic->topic_num }}">
            <input type="hidden" id="parent_camp_num" name="parent_camp_num" value="{{ $parentcampnum }}">
			<input type="hidden" id="camp_num" name="camp_num" value="{{ $camp->camp_num }}">
			<input type="hidden" id="submitter" name="submitter" value="{{ $camp->submitter_nick_id }}">
			<?php if($objection=="objection") { ?>
			 <input type="hidden" name="objection" id="objection" value="1">
			<?php } ?>
           
            <div class="form-group">
                <label for="camp_name">Nick Name</label>
                <select name="nick_name" id="nick_name" class="form-control">
                    @foreach($nickNames as $nick)
                    <option <?php if($camp->submitter_nick_id==$nick->id) echo "selected=selected";?> value="{{ $nick->id }}">{{ $nick->nick_name}}</option>
                    @endforeach
					
                </select>
                 @if ($errors->has('nick_name')) <p class="help-block">{{ $errors->first('nick_name') }}</p> @endif
				 <a href="<?php echo url('settings/nickname');?>">Add new nickname </a>
             </div> 
            
             <div class="form-group">
                <label for="camp_name">Camp Name ( Limit 30 Char )</label>
                <input type="text" name="camp_name" class="form-control" id="camp_name" value="{{ $camp->camp_name}}">
                 @if ($errors->has('camp_name')) <p class="help-block">{{ $errors->first('camp_name') }}</p> @endif
             </div> 
            
            <div class="form-group">
                <label for="title">Title </label>
                <input type="text" name="title" class="form-control" id="title" value="{{ $camp->title }}">
                @if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
            </div> 
           
            <div class="form-group">
                <label for="keywords">Keywords </label>
                <input type="text" name="keywords" class="form-control" id="keywords" value="{{ $camp->key_words }}">
                @if ($errors->has('keywords')) <p class="help-block">{{ $errors->first('keywords') }}</p> @endif
            </div> 
            <div class="form-group">
                <label for="language">Language</label>
                <select class="form-control" name="language" id="language">
                    <option <?php if($camp->language=="English") echo "selected=selected";?> value="English">English</option>
                    <option <?php if($camp->language=="French") echo "selected=selected";?> value="French">French</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="">Additional Note</label>
                <textarea class="form-control" rows="4" name="note" id="note">{{ $camp->note}}</textarea>
                @if ($errors->has('note')) <p class="help-block">{{ $errors->first('note') }}</p> @endif
            </div>   
            <div class="form-group">
                <label for="camp_about_url">Camp About URL </label>
                <input type="text" name="camp_about_url" class="form-control" id="camp_about_url" value="{{ $camp->camp_about_url }}">
                @if ($errors->has('camp_about_url')) <p class="help-block">{{ $errors->first('camp_about_url') }}</p> @endif
            </div>
            <div class="form-group">
                <label for="camp_about_nick_id">Camp About Nick Name </label>
                <select name="camp_about_nick_id" id="camp_about_nick_id" class="form-control">
                    <option value="0">--Select Camp About Nick Name--</option>
					@foreach($allNicknames as $aboutnick)
                    <option <?php if($camp->camp_about_nick_id==$aboutnick->id) echo "selected=selected";?> value="{{ $aboutnick->id }}">{{ $aboutnick->nick_name}}</option>
                    @endforeach
					
                </select>            
			</div> 			
            <?php if($objection=="objection") { ?>
            <div class="form-group">
                <label for="topic name">Your Objection Reason </label>
                <input type="text" name="object_reason" class="form-control" id="object_reason" value="">
				@if ($errors->has('object_reason')) <p class="help-block">{{ $errors->first('object_reason') }}</p> @endif
            </div> 				
            <?php } ?>
            <button type="submit" id="submit" class="btn btn-login">Submit Update</button>
        </form>
</div>
</div>
</div>  <!-- /.right-whitePnl-->
    

    <script>
        $(document).ready(function () {
            $("#datepicker").datepicker({
                changeMonth: true,
                changeYear: true
            });
        })
    </script>


    @endsection

