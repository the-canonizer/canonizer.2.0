@extends('layouts.app')
@section('content')

<div class="camp top-head">
    <h3><b>Topic:</b>  {{ $topic->title}}</h3>
    <h3><b>Camp:</b> {!! $parentcamp !!}</h3>  
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
            <input type="hidden" id="topic_num" name="topic_num" value="{{ $topic->topic_num }}">
            <input type="hidden" id="parent_camp_num" name="parent_camp_num" value="{{ $parentcampnum }}">
            
            <div class="form-group">
                <label for="camp_name">Nick Name <span style="color:red">*</span></label>
                <select name="nick_name" id="nick_name" class="form-control">
                    @foreach($nickNames as $nick)
                    <option value="{{ $nick->id }}">{{ $nick->nick_name}}</option>
                    @endforeach
					
                </select>
                 @if ($errors->has('nick_name')) <p class="help-block">{{ $errors->first('nick_name') }}</p> @endif
				 <a href="<?php echo url('settings/nickname');?>">Add new nickname </a>
             </div> 
            
             <div class="form-group">
                <label for="camp_name">Camp Name ( Limit 30 Char ) <span style="color:red">*</span></label>
                <input type="text" name="camp_name" class="form-control" id="camp_name" value="{{ old('camp_name')}}">
                 @if ($errors->has('camp_name')) <p class="help-block">{{ $errors->first('camp_name') }}</p> @endif
             </div> 
           
            <div class="form-group">
                <label for="keywords">Keywords </label>
                <input type="text" name="keywords" class="form-control" id="keywords" value="{{ old('keywords') }}">
                @if ($errors->has('keywords')) <p class="help-block">{{ $errors->first('keywords') }}</p> @endif
            </div> 
           
           
            <div class="form-group">
                <label for="">Additional Note <span style="color:red">*</span></label>
                <textarea class="form-control" rows="4" name="note" id="note">{{ old('note')}}</textarea>
                @if ($errors->has('note')) <p class="help-block">{{ $errors->first('note') }}</p> @endif
            </div>   
            <div class="form-group">
                <label for="camp_about_url">Camp About URL </label>
                <input type="text" name="camp_about_url" class="form-control" id="camp_about_url" value="{{ old('camp_about_url') }}">
                @if ($errors->has('camp_about_url')) <p class="help-block">{{ $errors->first('camp_about_url') }}</p> @endif
            </div> 

             <div class="form-group">
			    <p style="color:red">The following fields are rarely used and are for advanced users only.</p>
                <label for="camp_about_nick_id">Camp About Nick Name </label>
                <select name="camp_about_nick_id" id="camp_about_nick_id" class="form-control">
                    <option value="0">--Select Camp About Nick Name--</option>
					@foreach($allNicknames as $aboutnick)
                    <option value="{{ $aboutnick->id }}">{{ $aboutnick->nick_name}}</option>
                    @endforeach
					
                </select>            
			</div>  			

            <button type="submit" id="submit" class="btn btn-login">Create Camp</button>
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

