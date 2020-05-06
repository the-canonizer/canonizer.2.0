@extends('layouts.app')
@section('content')
<div class="page-titlePnl">
    <h1 class="page-title">Login</h1>
</div>     
@if(Session::has('social_error'))
<div class="alert alert-danger">
    <strong>Error! </strong>{{ Session::get('social_error')}} 
</div>
@endif  	
<div class="right-whitePnl">
<div class="col-sm-5 margin-btm-2">
    <form id="login_form" action="{{ url('/login')}}" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <label for="firstname">Email <span style="color:red">*</span></label>
            <input id="email" type="email" name="email" class="form-control" id="email" value="{{ old('email')}}">
            @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
        </div>

        <div class="form-group">
            <label>Password <span style="color:red">*</span></label>
            <input type="password" name="password" class="form-control" id="password" value="{{ old('password')}}">
            @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
        </div>


        <div class="form-group">            
            <input type="checkbox" name="remember" class="form-control remember-me" id="remember"> Remember Me
            <a href="{{ url('/forgetpassword') }}" class="pull-right">Forgot Password</a>
        </div>
        <button type="submit" id="submit" onclick="submitForm(this)"  class="btn btn-login">Log in</button>
        <div id="loggingin" style="display:none;" class="btn btn-login">Logging in..</div>
    </form>
 </div>
 <div class="col-sm-2 margin-btm-2"></div>
 <div class="col-sm-5 margin-btm-2 lg-signup">
           <div >Don't have an account? <a href="{{ url('/register') }}">Signup Now</a></div>
     </div>
 <div class="col-sm-12 margin-btm-2 ">
    <p>Login or Signup with social accounts.</p>
     <div class="form-group row">
            <div class="col-md-2">
                <a href="{{ url('/login/google') }}" class="btn google btn-google-plus"><i class="fa fa-google fa-fw">
          </i> Google+</a>                 
            </div>
            <div class="col-md-2">
                <a href="{{ url('/login/facebook') }}" class="btn fb btn-facebook"> <i class="fa fa-facebook fa-fw"></i> Facebook</a>                 
            </div>
            <div class="col-md-2">
                <a href="{{ url('/login/twitter') }}" class="btn twitter btn-twitter"><i class="fa fa-twitter fa-fw"></i> Twitter</a>
            </div>
            <div class="col-md-2">
                <a href="{{ url('/login/github') }}" class="btn github btn-github"><i class="fa fa-github fa-fw"></i> GitHub</a>
            </div>
            <div class="col-md-2">
                <a href="{{ url('/login/linkedin') }}" class="btn linkedin btn-linkedin"><i class="fa fa-linkedin fa-fw"></i> Linkedin</a>
            </div>
        </div>
 </div>
</div>  <!-- /.right-whitePnl-->
<script>
    window.onload = function() {
      var input = document.getElementById("email").focus();
    }

    function submitForm(btn){
        $('#submit').hide();
        $('#loggingin').show();
        return true;
    }

    </script>
@endsection