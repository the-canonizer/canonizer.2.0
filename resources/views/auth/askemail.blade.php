@extends('layouts.app')
@section('content')
<div class="page-titlePnl">
    <h1 class="page-title">User Email Confirmation</h1>
</div>       
@if(Session::has('error'))
<div class="alert alert-danger">
    <strong>Error! </strong>{{ Session::get('error')}}    
</div>
@endif
<div class="right-whitePnl">
<div class="col-sm-5 margin-btm-2">
    <form action="{{ url('/social/verifyemail')}}" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
       
        <p style="color:red">Note : Your email address is not returned from social account. You have to enter the email address. </p>  
        <div class="form-group">
		    
            <label>Email <span style="color:red">*</span></label>
            <input type="text" name="email" class="form-control" id="email" value="{{ old('email')}}">
            @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
        </div>

        <button type="submit" id="submit" class="btn btn-login">Submit</button>
    </form>
 </div>

</div>  <!-- /.right-whitePnl-->
<script>
    window.onload = function() {
  var input = document.getElementById("email").focus();
}

    </script>
@endsection