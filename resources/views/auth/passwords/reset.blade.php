@extends('layouts.app')
@section('content')


<div class="page-titlePnl">
    <h1 class="page-title">Reset New Password</h1>
</div>       	
<div class="right-whitePnl reset-link-sent">
<div class="col-sm-5 margin-btm-2">
    <form action="{{ url('/reset')}}" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <label for="email"> Email <span style="color:red">*</span></label>
            <input type="email" disable name="email" class="form-control" id="email" value="{{ $email }}">
            @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
        </div>
        <div class="form-group">
            <label for="password">Enter New Password <span style="color:red">*</span>
            <div class="pass_info"><i class="fa fa-info-circle" aria-hidden="true"></i>
                    <ul class="ps_tooltp"><li>Password must be atleast 8 characters</li><li>Must have atleast one lower case letter</li><li>Must have atleast one digit</li><li>Must have atleast one special character(@,# !,$..)</li></ul>
                </div>
            </label>
            <input type="password"  name="password" class="form-control" id="password" >
            @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirm Password <span style="color:red">*</span></label>
            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
            @if ($errors->has('password_confirmation')) <p class="help-block">{{ $errors->first('password_confirmation') }}</p> @endif
        </div>
        <button type="submit" id="reset_password" class="btn btn-login">Reset</button>
    </form>
</div>    
</div>
@endsection