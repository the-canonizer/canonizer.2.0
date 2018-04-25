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
        <form action="{{ route('statement.save')}}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="topic_num" value="{{ $topic->topic_num }}">
            <input type="hidden" name="parent_camp_num" value="{{ $parentcampnum }}">
			<input type="hidden" name="camp_num" value="{{ $statement->camp_num }}">
			<input type="hidden" name="submitter" value="{{ $statement->submitter_nick_id }}">
            <?php if($objection=="objection") { ?>
			<input type="hidden" name="objection" value="1">
			<?php } ?>
               
             <div class="form-group">
                <label for="camp_name">Nick Name</label>
                <select name="nick_name" class="form-control">
                    @foreach($nickNames as $nick)
                    <option value="{{ $nick->id }}">{{ $nick->nick_name}}</option>
                    @endforeach
					
                </select>
                 @if ($errors->has('nick_name')) <p class="help-block">{{ $errors->first('nick_name') }}</p> @endif
				 <a href="<?php echo url('settings/nickname');?>">Add new nickname </a>
             </div> 			   
             <div class="form-group">
                <label for="">Statement Value</label>
                <textarea class="form-control" rows="6" name="statement">{{ $statement->value}}</textarea>
                @if ($errors->has('statement')) <p class="help-block">{{ $errors->first('statement') }}</p> @endif
             </div> 
            
            <div class="form-group">
                <label for="title">Note </label>
                 <textarea class="form-control" rows="4" name="note">{{ $statement->note}}</textarea>
                @if ($errors->has('note')) <p class="help-block">{{ $errors->first('note') }}</p> @endif
            </div> 
           
		   <div class="form-group">
                <label for="language">Language</label>
                <select class="form-control" name="language">
                    <option <?php if($statement->language=="English") echo "selected=selected";?> value="English">English</option>
                    <option <?php if($statement->language=="French") echo "selected=selected";?> value="French">French</option>
                </select>
            </div>
            <?php if($objection=="objection") { ?> 
            <div class="form-group">
                <label for="topic name">Your Objection Reason </label>
                <input type="text" name="object_reason" class="form-control" id="" value="">
				@if ($errors->has('object_reason')) <p class="help-block">{{ $errors->first('object_reason') }}</p> @endif
            </div> 				
            <?php } ?>  
            <button type="submit" class="btn btn-login">Submit Update</button>
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

