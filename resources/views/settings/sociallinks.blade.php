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
@if(Session::has('social_error'))
<div class="alert alert-danger">
    <strong>Error! </strong>{{ Session::get('social_error')}} 
</div>
@endif 
@if(Session::has('already_exists'))
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Multiple User Warning</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
          <div class="modal-body">
            <p>It seems that there is already a user linked with this email id in canonizer. If that user also belongs to you than it is a violation of canonizer's agreement. So we suggest you to deactivate your another account and keep only single master account active. If you donot want to deactivate than click on cancel and try linking with aanother email id for this social account.If you wish to deactivate than select the account below and click submit</p>
            <div class="row">
                <div class="col-md-12"><input type="radio" name="user_deactivate" value="{{Session::get('another_user')->id}}" />
                    {{Session::get('another_user')->first_name." ".Session::get('another_user')->last_name}}
                </div>

                <div class="col-md-12"><input type="radio" name="user_deactivate" value="{{Auth::user()->id}}" />
                    {{Auth::user()->first_name." ".Auth::user()->last_name}}</div>
            </div>
            <p>Note: If you select the current user then you will be logged out of the canonizer</p>
          </div>
          <div class="modal-footer">
            <button type="button" onClick="deActivateUser()" class="btn btn-success" data-dismiss="modal">Submit</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
        </div>

      </div>
    </div>
  <script type="text/javascript">
        function deActivateUser(){
            var user_id = $('input[name="user_deactivate"]:checked').val();
            
        }
        $(window).on('load',function(){
            $('#myModal').modal('show');
        });
    </script>
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

