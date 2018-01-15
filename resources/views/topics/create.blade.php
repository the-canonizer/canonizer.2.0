@extends('layouts.app')
@section('content')
<div class="page-titlePnl">
    <h1 class="page-title">Create Topic</h1>
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
        <form action="{{ url('/topic')}}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label for="topic name">Topic Name </label>
                <input type="text" name="topic_name" class="form-control" id="" value="">
				@if ($errors->has('topic_name')) <p class="help-block">{{ $errors->first('topic_name') }}</p> @endif
            </div>            
            <div class="form-group">
                <label for="namespace">Name Space</label>
                <input type="text" name="namespace" class="form-control" id="" value="">
                <span class="note-label"><strong>Note</strong>: Name space is categorization of your topic, it can be something like: General,crypto_currency etc.</span>
                @if ($errors->has('namespace')) <p class="help-block">{{ $errors->first('namespace') }}</p> @endif
			</div>
            <div class="form-group">
                <label for="language">Language</label>
                <select class="form-control" name="language">
                    <option value="English">English</option>
                    <option value="French">French</option>
                </select>
            </div>
            <div class="form-group">
                <label for="Topic Number">To Go Live Date</label>
                <input type="text" name="go_live_time" value="" id="datepicker" class="form-control"/>
				@if ($errors->has('go_live_time')) <p class="help-block">{{ $errors->first('go_live_time') }}</p> @endif
            </div>
            <div class="form-group">
                <label for="">Additional Note</label>
                <textarea class="form-control" rows="4" name="note"></textarea>
            </div>    

            <button type="submit" class="btn btn-login">Create Topic</button>
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

