@extends('layouts.app')
@section('content')
<div class="page-titlePnl">
    <h1 class="page-title">Change Password</h1>
</div> 

@if(Session::has('success'))
<div class="alert alert-success">
    <strong>Success! </strong>{{ Session::get('success')}}    
</div>
@endif



<div class="right-whitePnl">
   <div class="row justify-content-between">
    <div class="col-sm-12 margin-btm-2">
        <div class="well">
            <ul class="nav prfl_ul">
                <li class=""><a class="" href="{{ route('settings')}}">Profile Info</a></li>
                <li class=""><a class="" href="{{ route('settings.sociallinks')}}">Social Oauth Verification</a></li>                
                <li class="active"><a class="" href="{{ route('settings.changepassword')}}">Change Password</a></li>
                <li><a class="" href="{{ route('settings.nickname')}}" >Nick Names</a></li>
                <li class=""><a class="" href="{{ route('settings.support')}}" >Supported Camps</a></li>
                <!-- <li><a class="" href="{{ route('settings.algo-preferences')}}">Default Algorithm</a></li> -->
                <li ><a class="" href="{{ route('settings.blockchain')}}">Crypto Verification (was MetaMask Account)</a></li>
            </ul>

            <div id="myTabContent" class="add-nickname-section">  
               
                <form action="{{ route('settings.changepassword.save')}}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row">
                        <div class="col-sm-6 margin-btm-1">
                            <label for="nick_name">Enter current password <span style="color:red">*</span></label>
                            <input type="password" name="current_password" class="form-control" id="current_password" value="{{ old('current_password')}}">
                            @if ($errors->has('current_password')) <p class="help-block">{{ $errors->first('current_password') }}</p> @endif
                            @if(Session::has('error')) <p class="help-block">{{ Session::get('error')}} </p>@endif
                        
                        </div>
                        <div class="col-sm-6 margin-btm-1">
                            <label for="nick_name">Enter new password <span style="color:red">*</span></label>
                            <input type="password" name="new_password" class="form-control" id="new_password" value="{{ old('new_password')}}">
                            @if ($errors->has('new_password')) <p class="help-block">{{ $errors->first('new_password') }}</p> @endif
                        </div>
                        <div class="col-sm-6 margin-btm-1">
                            <label for="nick_name">Confirm password <span style="color:red">*</span></label>
                            <input type="password" name="confirm_password" class="form-control" id="confirm_password" value="{{ old('confirm_password')}}">
                            @if ($errors->has('confirm_password')) <p class="help-block">{{ $errors->first('confirm_password') }}</p> @endif
                        </div>
                    </div>
                    
                    <button type="submit" id="submit_create" class="btn btn-login">Save</button>
                    <!--button type="submit" id="submit_cancel" class="btn btn-default">Cancel</button-->
                </form>  
        </div>
    </div>   
 </div></div>
</div>  <!-- /.right-whitePnl-->

    <script>
        $(document).ready(function () {
            $("#birthday").datepicker({
                changeMonth: true,
                changeYear: true
            });
        })
    </script>


    @endsection

