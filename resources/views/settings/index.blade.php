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
                <li class="active"><a class="" href="{{ route('settings')}}">Manage Profile info</a></li>
                <li><a class="" href="{{ route('settings.nickname')}}" >Add & Manage Nick Names</a></li>
            </ul>

            <div id="myTabContent" class="" style="margin-top:20px;">
                    
                    <form action="{{ route('settings.profile.update',['id'=>$user->id])}}" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row">
                            <div class="col-sm-6 margin-btm-1">
                                <label for="topic name">First Name </label>
                                <input type="text" name="first_name" class="form-control" id="" value="{{ old('firstname',$user->first_name)}}">
                            </div>
                            <div class="col-sm-6 margin-btm-1">
                                <label for="namespace">Middle Name</label>
                                <input type="text" name="middle_name" class="form-control" id="" value="{{ old('middle_name', $user->middle_name)}}">
                            </div>   
                            <div class="col-sm-6 margin-btm-1">
                                <label for="last_name">Last Name</label>
                                <input type="text" name="last_name" class="form-control" id="" value="{{ old('last_name', $user->last_name)}}">
                            </div>
                            <div class="col-sm-6 margin-btm-1">
                                <label for="last_name">Email</label>
                                <input type="text" name="email" readonly="readonly" class="form-control" id="" value="{{ old('email', $user->email)}}">
                            </div>
                            <div class="col-sm-6 margin-btm-1">
                                <label for="last_name">Gender</label><br/>
                                <input type="radio" name="gender" value="male" {{ (old('gender',$user->gender) == 'male') ? 'checked' : ''}}/> Male
                                <input type="radio" name="gender" value="female" {{ (old('gender',$user->gender) == 'female') ? 'checked' : ''}}/> Female
                                <input type="radio" name="gender" value="other" {{ (old('gender',$user->gender) == 'other') ? 'checked' : ''}}/> Other
                            </div>
                            <div class="col-sm-6 margin-btm-1">
                                <label for="language">Languauge</label>
                                <select name="language" class="form-control">
                                    <option value="English">English</option>
                                    <option value="French">French</option>
                                    <option value="Spanish">Spanish</option>
                                    <option value="Hindi">Hindi</option>
                                </select>
                            </div>
                            <div class="col-sm-6 margin-btm-1">
                                <label for="address_1">Date Of Birth</label>
                                @if(old('birthday') != '')
                                <input type="text" name="birthday" id="birthday" class="form-control" id="" value="{{ date('m/d/Y',strtotime(old('birthday')))}}">
                                @else
                                <input type="text" name="birthday" id="birthday" class="form-control" id="" value="{{ (isset($user->birthday) && $user->birthday != '') ? date('m/d/Y',strtotime($user->birthday)) : '' }}">
                                @endif
                            </div>
                            <div class="col-sm-6 margin-btm-1">
                                <label for="address_1">Address Line 1</label>
                                <input type="text" name="address_1" class="form-control" id="" value="{{ old('address_1', $user->address_1)}}">
                            </div>
                            <div class="col-sm-6 margin-btm-1">
                                <label for="address_2">Address Line 2</label>
                                <input type="text" name="address_2" class="form-control" id="" value="{{ old('address_2', $user->address_2)}}">
                            </div>
                            <div class="col-sm-6 margin-btm-1">
                                <label for="city">City</label>
                                <input type="text" name="city" class="form-control" id="" value="{{ old('city', $user->city)}}">
                            </div>
                            <div class="col-sm-6 margin-btm-1">
                                <label for="state">State</label>
                                <input type="text" name="state" class="form-control" id="" value="{{ old('state', $user->state)}}">
                            </div>
                            <div class="col-sm-6 margin-btm-1">
                                <label for="country">Country</label>
                                <select name="country" class="form-control">
                                    <option value="US">United States</option>
                                </select>
                            </div>
                            <div class="col-sm-6 margin-btm-1">
                                <label for="postal_code">Zip Code</label>
                                <input type="text" name="postal_code" class="form-control" id="" value="{{ old('postal_code', $user->postal_code)}}">
                            </div>
                        </div>
                       
                        <button type="submit" class="btn btn-login">Update</button>
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

