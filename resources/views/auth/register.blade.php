@extends('layouts.app')
@section('content')
<div class="page-titlePnl">
    <h1 class="page-title">Create Account</h1>
</div>       	
<div class="right-whitePnl">
<div class="col-sm-5 margin-btm-2">
    <form action="{{ url('/register')}}" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <label for="firstname">First Name <span style="color:red">*</span></label>
            <input type="text" name="first_name" class="form-control" id="firstname" value="{{ old('first_name')}}">
            @if ($errors->has('first_name')) <p class="help-block">{{ $errors->first('first_name') }}</p> @endif
        </div>
        
        <div class="form-group">
            <label>Middle Name </label>
            <input type="text" name="middlename" class="form-control" id="middlename" value="{{ old('middlename')}}">
            @if ($errors->has('middlename')) <p class="help-block">{{ $errors->first('middlename') }}</p> @endif
        </div>
        
        <div class="form-group">
            <label>Last Name <span style="color:red">*</span></label>
            <input type="text" name="last_name" class="form-control" id="lastname" value="{{ old('last_name')}}">
            @if ($errors->has('last_name')) <p class="help-block">{{ $errors->first('last_name') }}</p> @endif
        </div>

        <div class="form-group">
            <label>Email <span style="color:red">*</span></label>
            <input type="email" name="email" class="form-control" id="email" value="{{ old('email')}}">
            @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
        </div>
        <div class="form-group">
            <label for="pwd">Password <span style="color:red">*</span></label>
            <input type="password" name="password" class="form-control" id="password">
            @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
        </div>
        
        <div class="form-group">
            <label for="pwd">Confirm Password <span style="color:red">*</span></label>
            <input type="password" name="password_confirmation" class="form-control" id="pwd_confirm">
            
        </div>
        <button type="submit" id="submit" class="btn btn-login">Create your account</button>
    </form>
</div> 
<div class="col-sm-2 margin-btm-2"></div>
 <div class="col-sm-5 margin-btm-2 lg-signup">
           <div >Already have have account <a href="{{ url('/login') }}">Login Here</a></div>
     </div>

</div>  <!-- /.right-whitePnl-->
<script>
    window.onload = function() {
  var input = document.getElementById("firstname").focus();
}

    </script>
@endsection