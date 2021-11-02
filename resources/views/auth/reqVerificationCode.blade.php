@extends('layouts.app')
@section('content')
<div class="page-titlePnl">
    <h1 class="page-title">Get Verification Code</h1>
</div>
<div class="error-section">
    @if(Session::has('verification_code_error'))
        <div class="alert alert-danger">
            <strong>Error! </strong>{!! Session::get('verification_code_error') !!} 
        </div>
    @endif
</div>
<div class="right-whitePnl">
    <div class="col-sm-5 margin-btm-2">
        <form action="{{ url('/verifyCode')}}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label for="email">Enter Email/Phone Number <span style="color:red">*</span></label>
                <input type="text" name="email" class="form-control" id="email" value="{{ old('email')}}" placeholder="Email/Phone Number">
                @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
            </div>       
            <button type="submit" id="submit" class="btn btn-login">Submit</button>
        </form>
    </div>   
</div>  <!-- /.right-whitePnl-->
@endsection