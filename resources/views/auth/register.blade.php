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
            <label for="firstname">First Name (Limit 100 Chars)<span style="color:red">*</span></label>
            <input type="text" name="first_name" onkeydown="restrictTextField(event,100)" class="form-control" id="firstname" value="{{ old('first_name')}}">
            @if ($errors->has('first_name')) <p class="help-block">{{ $errors->first('first_name') }}</p> @endif
        </div>
        
        <div class="form-group">
            <label>Middle Name (Limit 100 Chars)</label>
            <input type="text" name="middle_name" onkeydown="restrictTextField(event,100)" class="form-control" id="middle_name" value="{{ old('middle_name')}}">
            @if ($errors->has('middle_name')) <p class="help-block">{{ $errors->first('middle_name') }}</p> @endif
        </div>
        
        <div class="form-group">
            <label>Last Name (Limit 100 Chars)<span style="color:red">*</span></label>
            <input type="text" name="last_name" onkeydown="restrictTextField(event,100)" class="form-control" id="lastname" value="{{ old('last_name')}}">
            @if ($errors->has('last_name')) <p class="help-block">{{ $errors->first('last_name') }}</p> @endif
        </div>

        <div class="form-group">
            <label>Email (Limit 255 Chars)<span style="color:red">*</span></label>
            <input type="email" name="email" onkeydown="restrictTextField(event,255)" class="form-control" id="email" value="{{ old('email')}}">
            @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
        </div>
        <div class="form-group">
            <label for="pwd">Password <span style="color:red">*</span> 
                <div class="pass_info"><i class="fa fa-info-circle" aria-hidden="true"></i>
                    <ul class="ps_tooltp"><li>Password must be atleast 8 characters</li><li>Must have atleast one lower case letter</li><li>Must have atleast one digit</li><li>Must have atleast one special character(@,# !,$..)</li></ul>
                </div>
            </label>
            <input type="password" name="password" class="form-control" id="password">
             <!--<span style="display:none;" class="passStrengthCheck">Password must be atleast 8 characters, including atleast one digit and one special character(@,# !,$..)</span>-->
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
           <div >Already have an account?  <a href="{{ url('/login') }}">Login Here</a></div>
     </div>

</div>  <!-- /.right-whitePnl-->
<script>
    window.onload = function() {
  var input = document.getElementById("firstname").focus();
}

$('.pinfo').tooltip();

/*
function validatePassword(){
    var pass = $('#password').val();
    var pattern = /^(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^\w\s]).{8,}$/;
    if (!pattern.test(pass) && pass != '')
        {
           $('.passStrengthCheck').show();
            return false;
        }else{
            $('.passStrengthCheck').hide();
             return true;
        }
            
           
}
$('#password').on('change', validatePassword); */
    </script>
@endsection