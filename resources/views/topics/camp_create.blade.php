@extends('layouts.app')
@section('content')

<div class="camp top-head">
    <h3><b>Topic:</b>  {{ $topic->title}}</h3>
    <h3><b>Camp:</b> {{ $parentcamp }}</h3>  
</div>


<div class="page-titlePnl">
    <h1 class="page-title">Create New Camp</h1>
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
            <input type="hidden" name="topic_num" value="{{ $topic->topic_num }}">
            <input type="hidden" name="parent_camp_num" value="{{ $parentcampnum }}">
            
            <div class="form-group">
                <label for="camp_name">Nick Name</label>
                <select name="nick_name" class="form-control">
                    @foreach($nickNames as $nick)
                    <option value="{{ $nick->nick_name_id }}">{{ $nick->nick_name}}</option>
                    @endforeach
					
                </select>
                 @if ($errors->has('nick_name')) <p class="help-block">{{ $errors->first('nick_name') }}</p> @endif
				 <a href="<?php echo url('settings/nickname');?>">Add new nickname </a>
             </div> 
            
             <div class="form-group">
                <label for="camp_name">Camp Name </label>
                <input type="text" name="camp_name" class="form-control" id="" value="{{ old('camp_name')}}">
                 @if ($errors->has('camp_name')) <p class="help-block">{{ $errors->first('camp_name') }}</p> @endif
             </div> 
            
            <div class="form-group">
                <label for="title">Title </label>
                <input type="text" name="title" class="form-control" id="" value="{{ old('title') }}">
                @if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
            </div> 
            <div class="form-group">
                <label for="statement">Camp Statement</label>
                <textarea class="form-control" rows="6" name="statement">{{ old('statement')}}</textarea>
                @if ($errors->has('statement')) <p class="help-block">{{ $errors->first('statement') }}</p> @endif
            </div> 
            <div class="form-group">
                <label for="keywords">Keywords </label>
                <input type="text" name="keywords" class="form-control" id="" value="{{ old('keywords') }}">
                @if ($errors->has('keywords')) <p class="help-block">{{ $errors->first('keywords') }}</p> @endif
            </div> 
            <div class="form-group">
                <label for="language">Language</label>
                <select class="form-control" name="language">
                    <option value="English">English</option>
                    <option value="French">French</option>
                </select>
            </div>
            <div class="form-group">
                <label for="Topic Number">To Go Live Date / Time</label>
                <input type="text" name="go_live_time" value="{{ (old('go_live_time') != '') ?  date('m/d/Y',strtotime(old('go_live_time'))) : ''}}" id="datepicker" class="form-control"/>
                @if ($errors->has('go_live_time')) <p class="help-block">{{ $errors->first('go_live_time') }}</p> @endif
            </div>
            <div class="form-group">
                <label for="">Additional Note</label>
                <textarea class="form-control" rows="4" name="note">{{ old('note')}}</textarea>
                @if ($errors->has('note')) <p class="help-block">{{ $errors->first('note') }}</p> @endif
            </div>   
            <div class="form-group">
                <label for="url">URL </label>
                <input type="text" name="url" class="form-control" id="" value="{{ old('url') }}">
                @if ($errors->has('url')) <p class="help-block">{{ $errors->first('url') }}</p> @endif
            </div> 			

            <button type="submit" class="btn btn-login">Create Camp</button>
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

