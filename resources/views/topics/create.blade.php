@extends('layouts.app')
@section('content')
<div class="page-titlePnl">
    <h1 class="page-title">Create Topic</h1>
</div>       	
<div class="right-whitePnl">
<div class="col-sm-6 margin-btm-2">
    <form action="" method="post">
        <div class="form-group">
        	<label for="topic name">Topic Name </label>
             <input type="text" name="" class="form-control" id="" value="">
        </div>
        <div class="form-group">
            <label for="language">Language</label>
            <select class="form-control">
            	<option>English</option>
                <option>French</option>
            </select>
        </div>
        <div class="form-group">
            <label for="Topic Number">Parent Topic</label>
            <select class="form-control">
            	<option>1</option>
                <option>2</option>
            </select>
        </div>
        <div class="form-group">
            <label for="">Additional Note</label>
            <textarea class="form-control" rows="4"></textarea>
        </div>    
        
        <button type="submit" class="btn btn-login">Create Topic</button>
    </form>
</div>  <!-- /.right-whitePnl-->
@endsection