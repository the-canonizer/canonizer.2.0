@extends('layouts.app')
@section('content')
    <div class="page-titlePnl">
        <h1 class="page-title">Log in</h1>
    </div>

    <div class="error-section">
        @if (Session::has('social_error'))
            <div class="alert alert-danger">
                <strong>Error! </strong>{{ Session::get('social_error') }}
            </div>
        @endif

        @if (Session::has('erro_login'))
            <div class="alert alert-danger">
                <strong>Error! </strong>{{ Session::get('erro_login') }}
            </div>
        @endif
    </div>

<div class="right-whitePnl">
    <div class="col-xl-5 margin-btm-2">
        @if (Session::has('tokenerror'))
        <div class="alert alert-danger">
            {{Session::get('tokenerror')}}
        </div>
        @endif
        <form id="login_form" action="{{ url('/login')}}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label for="firstname">Enter Email / Phone Number <span style="color:red">*</span></label>
                <input id="email" type="text" name="email" class="form-control" id="email" value="{{ old('email')}}" placeholder="Email / Phone Number">
                @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
            </div>

            <div class="form-group">
                <label>Password <span style="color:red">*</span></label>
            <!--  <label><input type="checkbox"  name="request_opt"  id="request_opt">Request OTP</label> -->
                <input type="password" name="password" class="form-control" id="password" value="{{ old('password')}}" placeholder="Password">
                @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
            </div>

            <div class="form-group">            
                <input type="checkbox" name="remember" class="form-control remember-me" id="remember"> Remember Me
                <a href="{{ url('/forgotpassword') }}" class="pull-right">Forgot Password</a>
            </div>

            <div class="form-group">
                <button type="submit" id="submit" onclick="submitLoginForm(this)"  class=" form-control btn btn-login">Log in</button>
                <div id="loggingin" style="display:none;" class=" form-control btn btn-login">Logging in..</div>
            </div>

            <div class="form-group">
                <label class="text-center col-sm-12">OR <input style="display:none;" type="checkbox"  name="request_opt"  id="request_opt_checkbox"></label>
            </div>
            <div class="form-group">
                <button type="submit" style="white-space: unset"  id="request_opt" class="form-control btn btn-login">Request One Time Verification Code</button>
            
                <div id="requesting_otp" style="display:none;" class="form-control btn btn-login">Requesting One Time Verification Code..</div>
            </div>
        </form>
    </div>

    <div class="col-xl-2 margin-btm-2"></div>
    <div class="col-xl-5 margin-btm-2 lg-signup">
        <div >Don't have an account? <a href="{{ url('/register') }}">Signup Now</a></div>
    </div>

    <div class="col-12 margin-btm-2 ">
        <p>Log in or Signup with social accounts.</p>
        <div class="form-group row">
            <div class="col-12">
                <a href="{{ url('/login/google') }}" class="btn google btn-google-plus social-login-btn"><i class="fa fa-google fa-fw"></i> Google+</a>
                <a href="{{ url('/login/facebook') }}" class="btn facebook btn-facebook social-login-btn"> <i class="fa fa-facebook fa-fw"></i> Facebook</a>
                <a href="{{ url('/login/twitter') }}" class="btn twitter btn-twitter social-login-btn"><i class="fa fa-twitter fa-fw"></i> Twitter</a>
                <a href="{{ url('/login/github') }}" class="btn github btn-github social-login-btn"><i class="fa fa-github fa-fw"></i> GitHub</a>
                <a href="{{ url('/login/linkedin') }}" class="btn linkedin btn-linkedin social-login-btn"><i class="fa fa-linkedin fa-fw"></i> Linkedin</a>
            </div>
        </div>
    </div>
</div>  <!-- /.right-whitePnl-->
<script>
    $('#request_opt').click(function() {
        $('#request_opt_checkbox').prop('checked', 'checked');
        $('#request_opt').hide();
        $('#requesting_otp').show();
        return true;
    });

    window.onload = function() {
        var input = document.getElementById("email").focus();
    }

    function submitLoginForm(btn) {
        $('#submit').hide();
        $('#loggingin').show();
        return true;
    }
</script>
@endsection
