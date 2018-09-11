@extends('layouts.app')
@section('content')
<div class="page-titlePnl">
    <h1 class="page-title">Registration Verification</h1>
</div>       
@if(Session::has('error'))
<div class="alert alert-danger">
    <strong>Error! </strong>{{ Session::get('error')}}    
</div>
@endif
<div class="right-whitePnl">
<div class="col-sm-5 margin-btm-2">
    <form action="{{ url('/register/verify-otp')}}" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="user" value="{{ $user }}">
       

        <div class="form-group">
            <label>One Time Verification Code <span style="color:red">*</span></label>
            <input type="text" name="otp" class="form-control" id="otp" value="{{ old('otp')}}">
            @if ($errors->has('otp')) <p class="help-block">{{ $errors->first('otp') }}</p> @endif
        </div>

        <button type="submit" id="submit" class="btn btn-login">Submit</button>
    </form>
 </div>

</div>  <!-- /.right-whitePnl-->
<script>
    window.onload = function() {
  var input = document.getElementById("otp").focus();
}

    </script>
@endsection