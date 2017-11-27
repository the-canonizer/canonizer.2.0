@extends('layouts.app')
@section('content')


<div class="page-titlePnl">
    <h1 class="page-title">Reset New Password</h1>
</div>       	
<div class="right-whitePnl reset-link-sent">
    <form action="{{ url('/reset')}}" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <label for="email"> Email</label>
            <input type="email" disable name="email" class="form-control" id="email" value="{{ $email }}">
            @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
        </div>
        <div class="form-group">
            <label for="password">Enter New Password</label>
            <input type="password"  name="password" class="form-control" id="password" >
            @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password_confirmation" name="password_confirmation" class="form-control" id="password_confirmation">
            @if ($errors->has('password_confirmation')) <p class="help-block">{{ $errors->first('password_confirmation') }}</p> @endif
        </div>

        <a href="{{ url('/')}}" class="btn btn-default" />
        <button type="submit" class="btn btn-prijmary">Reset</button>
    </form>
</div>
@endsection