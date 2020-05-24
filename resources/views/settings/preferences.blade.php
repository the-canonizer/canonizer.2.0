@extends('layouts.app')
@section('content')
<div class="page-titlePnl">
    <h1 class="page-title">Default Algorithm</h1>
</div> 

@if(Session::has('error'))
<div class="alert alert-danger">
    <strong>Error! </strong>{{ Session::get('error')}}    
</div>
@endif

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
                <li><a class="" href="{{ route('settings.changepassword')}}">Change Password</a></li>
                <li><a class="" href="{{ route('settings.nickname')}}" >Nick Names</a></li>
                <li class=""><a class="" href="{{ route('settings.support')}}" >Supported Camps</a></li>
                <!-- <li><a class="" href="{{ route('settings.algo-preferences')}}">Default Algorithm</a></li> -->
                <li class="active"><a class="" href="{{ route('settings.blockchain')}}">Crypto Verification (was Metamask Account)</a></li>
            </ul>
         
        
         <div id="myTabContent" class="add-nickname-section">  
                <form action="{{ route('settings.algo-preferences-save') }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <div class="form-group">
                        <label>Choose default algorithm preferences</label>
                        <select name="default_algo" id="default_algo" class="form-control">
                        @foreach(\App\Model\Algorithm::getList() as $key=>$algo)
                            <option value="{{$key}}" {{ $user->default_algo == $key ? 'selected' : ''}}>{{$algo}}</option>
                        @endforeach
                        </select>
                    </div>
                    <button type="submit" id="submit" class="btn btn-login">Confirm Support</button>
                    
                </form>  
        </div>
	           
    </div>   
 </div></div>
</div>  <!-- /.right-whitePnl-->



    @endsection

