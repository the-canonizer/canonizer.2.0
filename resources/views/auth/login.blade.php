@extends('layouts.app')
@section('content')
<div class="page-titlePnl">
    <h1 class="page-title">Login</h1>
</div>       	
<div class="right-whitePnl">
    <form action="{{ url('/login')}}" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <label for="firstname">Email</label>
            <input type="email" name="email" class="form-control" id="email" value="{{ old('email')}}">
            @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
        </div>
        
        <div class="form-group">
            <label>Password </label>
            <input type="password" name="password" class="form-control" id="password" value="{{ old('password')}}">
            @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
        </div>
        
        
        <div class="form-group">            
            <input type="checkbox" name="remember" class="form-control remember-me" id="remember"> Remember Me
        </div>

        <a href="{{ url('/')}}" class="btn btn-default" />
        <button type="submit" class="btn btn-prijmary">Submit</button>
    </form>
</div>  <!-- /.right-whitePnl-->
@endsection