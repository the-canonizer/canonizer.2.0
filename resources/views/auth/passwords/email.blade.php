@extends('layouts.app')
@section('content')
<div class="page-titlePnl">
    <h1 class="page-title">Forget Password</h1>
</div>       	
<div class="right-whitePnl">
    <form action="{{ url('/forgetpassword')}}" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <label for="firstname">Enter Email</label>
            <input type="email" name="email" class="form-control" id="email" value="{{ old('email')}}">
            @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
        </div>       

        <a href="{{ url('/')}}" class="btn btn-default" />
        <button type="submit" class="btn btn-prijmary">Submit</button>
    </form>
</div>  <!-- /.right-whitePnl-->
@endsection