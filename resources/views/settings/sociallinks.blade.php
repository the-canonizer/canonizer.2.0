@extends('layouts.app')
@section('content')
<div class="page-titlePnl">
    <h1 class="page-title">Profile</h1>
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
                <li class=""><a class="" href="{{ route('settings')}}">Manage Profile Info</a></li>
                <li><a class="" href="{{ route('settings.nickname')}}" >Manage Nick Names</a></li>
                <li class=""><a class="" href="{{ route('settings.support')}}" >My Supports</a></li>
                <li><a class="" href="{{ route('settings.algo-preferences')}}">Default Algorithm</a></li>
                <li><a class="" href="{{ route('settings.changepassword')}}">Change Password</a></li>
                <li class=""><a class="" href="{{ route('settings.blockchain')}}">Metamask Account</a></li>
                <li class="active"><a class="" href="{{ route('settings.sociallinks')}}">Social Oauth Links</a></li>
                
            </ul>
             
            <div id="myTabContent" class="add-nickname-section" style="margin-top:20px;">
            <div id="savedAddress">
               <table class="table">
               <thead class="thead-default">
                <tr>
                    <th>Sr</th>                    
                    <th>Provider</th>
                    <th>Email</th>
                    <th>ProviderId</th>
                    <th>Action</th>
                </tr>                                        
             </thead>
                @if(count($providers) > 0)    
               <tbody>
                    @foreach($providers as $key=>$provider)

                        @if(isset($sociallinks[$provider]) && isset($sociallinks[$provider]['social_email']))
                           <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$sociallinks[$provider]['provider']}}</td>
                            <td>{{$sociallinks[$provider]['social_email']}}</td>                            
                            <td>{{$sociallinks[$provider]['provider_id']}}</td>
                            <td>
                                <div class="col-md-2"><a  class="btn {{$provider}} btn-{{$provider}}">
                                Already linked with {{$provider}} <i class="fa fa-{{$provider}} fa-fw"></i></a></div>
                             </td>
                        </tr>

                        @else 
                            <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$provider}}</td>
                            <td></td>                            
                            <td></td>
                            <td>
                                <div class="col-md-2"><a href="{{ url('/login/'.$provider) }}" class="btn {{$provider}} fb btn-{{$provider}}">
                                    Link with {{$provider}} <i class="fa fa-{{$provider}} fa-fw"></i></a></div>
                             </td>
                        </tr>

                        @endif
                        
                    @endforeach
                  </tbody>
                  @else
                    <tbody>
                        <tr>
                            <td colspan="2"> No data found</td>
                        </tr>
                    </tbody>
                  @endif
               </table>

             </div>
                     
        </div>
    </div>   
 </div></div>
</div>  <!-- /.right-whitePnl-->
@endsection

