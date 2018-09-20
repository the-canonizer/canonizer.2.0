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
                <li class="active"><a class="" href="{{ route('settings')}}">Manage Profile Info</a></li>
                <li><a class="" href="{{ route('settings.nickname')}}" >Add & Manage Nick Names</a></li>
				<li class=""><a class="" href="{{ route('settings.support')}}" >My Supports</a></li>
                <li><a class="" href="{{ route('settings.algo-preferences')}}">Default Algorithm</a></li>
                <li><a class="" href="{{ route('settings.changepassword')}}">Change Password</a></li>
				
            </ul>

            <div id="myTabContent" class="" style="margin-top:20px;">
                    
                    <form action="{{ route('settings.profile.update',['id'=>$user->id])}}" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
						<?php $private_flags = explode(",",$user->private_flags); ?>
                        <div class="row">
                            <div class="col-sm-6 margin-btm-1">
                                <label for="topic name">First Name <span style="color:red;">*</span></label></br>
                                <div style="width:300px;float:left">
								<input type="text" name="first_name" class="form-control" id="first_name" value="{{ old('firstname',$user->first_name)}}">
								@if ($errors->has('first_name')) <p class="help-block">{{ $errors->first('first_name') }}</p> @endif
								</div>
								<div style="width:95px;float:right">
								<select class="form-control" id="first_name_bit"  name="first_name_bit">
								 
								 <option value="0">Public</option>
								 <option {{ (in_array('first_name',$private_flags)) ? "selected='selected'" : '' }} value="first_name">Private</option>
								</select> 
								</div>
                            </div>
                            <div class="col-sm-6 margin-btm-1">
                                <label for="namespace">Middle Name</label><br/>
                                <div style="width:300px;float:left">
								<input type="text" name="middle_name" class="form-control" id="middle_name" value="{{ old('middle_name', $user->middle_name)}}">
                                @if ($errors->has('middle_name')) <p class="help-block">{{ $errors->first('middle_name') }}</p> @endif
								</div>
								<div style="width:95px;float:right">
								<select class="form-control" id="middle_name_bit" name="middle_name_bit">
								 
								 <option value="0">Public</option>
								 <option {{ (in_array('middle_name',$private_flags)) ? "selected='selected'" : '' }} value="middle_name">Private</option>
								</select> 
								</div> 
							</div>   
                            <div class="col-sm-6 margin-btm-1">
                                <label for="last_name">Last Name <span style="color:red;">*</span></label></br>
								<div style="width:300px;float:left">
                                <input type="text" name="last_name" class="form-control" id="last_name" value="{{ old('last_name', $user->last_name)}}">
                                @if ($errors->has('last_name')) <p class="help-block">{{ $errors->first('last_name') }}</p> @endif
								</div>
								<div style="width:95px;float:right">
								<select class="form-control" id="last_name_bit"  name="last_name_bit">
								 
								 <option value="0">Public</option>
								 <option {{ (in_array('last_name',$private_flags)) ? "selected='selected'" : '' }} value="last_name">Private</option>
								</select> 
								</div> 
							</div>
                            <div class="col-sm-6 margin-btm-1">
                                <label for="last_name">Email</label></br>
								<div style="width:300px;float:left">
                                <input type="text" name="email" readonly="readonly" class="form-control" id="email" value="{{ old('email', $user->email)}}">
                                </div>
								<div style="width:95px;float:right">
								<select class="form-control" id="email_bit"  name="email_bit">
								 
								 <option value="0">Public</option>
								 <option {{ (in_array('email',$private_flags)) ? "selected='selected'" : '' }} value="email">Private</option>
								</select> 
								</div> 
							</div>
                            <div class="col-sm-6 margin-btm-1">
                                <label for="last_name">Gender</label><br/>
                                <input type="radio" id="gender_male" name="gender" value="1" {{ (old('gender',$user->gender) == '1') ? 'checked' : ''}}/> Male
                                <input type="radio" id="gender_female" name="gender" value="2" {{ (old('gender',$user->gender) == '2') ? 'checked' : ''}}/> Female
                                <input type="radio" id="gender_other" name="gender" value="3" {{ (old('gender',$user->gender) == '3') ? 'checked' : ''}}/> Other
                               
							</div>
                            <div class="col-sm-6 margin-btm-1">
                                <label for="language">Languauge</label>
                                <select name="language" id="language" class="form-control">
                                    <option value="English" {{ (old('language',$user->language) == 'English') ? 'selected' : ''}}>English</option>
                                    <option value="French" {{ (old('language',$user->language) == 'French') ? 'selected' : ''}}>French</option>                                   
                                    <option value="Hindi" {{ (old('language',$user->language) == 'Hindi') ? 'selected' : ''}}>Hindi</option>
									 <option value="Spanish" {{ (old('language',$user->language) == 'Spanish') ? 'selected' : ''}}>Spanish</option>
                                </select>
                            </div>
                            <div class="col-sm-6 margin-btm-1">
                                <label for="address_1">Date Of Birth</label>
								</br>
								<div style="width:300px;float:left">
                                @if(old('birthday') != '')
                                <input type="text" readonly name="birthday" id="birthday" class="form-control" id="" value="{{ date('m/d/Y',strtotime(old('birthday')))}}">
                                @else
                                <input type="text" readonly name="birthday" id="birthday" class="form-control" id="" value="{{ (isset($user->birthday) && $user->birthday != '') ? date('m/d/Y',strtotime($user->birthday)) : '' }}">
                                @endif
								</div>
								<div style="width:95px;float:right">
								<select class="form-control" id="birthday_bit" name="birthday_bit">
								 
								 <option value="0">Public</option>
								 <option {{ (in_array('birthday',$private_flags)) ? "selected='selected'" : '' }} value="birthday">Private</option>
								</select> 
								</div> 
                            </div>
                            <div class="col-sm-6 margin-btm-1">
                                <label for="address_1">Address Line 1</label>
								</br>
								<div style="width:300px;float:left">
                                <input type="text" name="address_1" class="form-control" id="address_1" value="{{ old('address_1', $user->address_1)}}">
                                </div>
								<div style="width:95px;float:right">
								<select class="form-control" id="address_1_bit"  name="address_1_bit">
								 
								 <option value="0">Public</option>
								 <option {{ (in_array('address_1',$private_flags)) ? "selected='selected'" : '' }} value="address_1">Private</option>
								</select> 
								</div> 
							</div>
                            <div class="col-sm-6 margin-btm-1">
                                <label for="address_2">Address Line 2</label>
								</br>
								<div style="width:300px;float:left">
                                <input type="text" name="address_2" class="form-control" id="address_2" value="{{ old('address_2', $user->address_2)}}">
                                </div>
								<div style="width:95px;float:right">
								<select class="form-control" id="address_2_bit"  name="address_2_bit">
								 
								 <option value="0">Public</option>
								 <option {{ (in_array('address_2',$private_flags)) ? "selected='selected'" : '' }} value="address_2">Private</option>
								</select> 
								</div>  
							</div>
                            <div class="col-sm-6 margin-btm-1">

                                <label for="city">City</label>
								</br>
								<div style="width:300px;float:left">
                                <input type="text" name="city" class="form-control" id="city" value="{{ old('city', $user->city)}}">
                                </div>
								<div style="width:95px;float:right">
								<select class="form-control" id="city_bit"  name="city_bit">
								 
								 <option value="0">Public</option>
								 <option {{ (in_array('city',$private_flags)) ? "selected='selected'" : '' }} value="city">Private</option>
								</select> 
								</div> 
							</div>
                            <div class="col-sm-6 margin-btm-1">
                                <label for="state">State</label>
								</br>
								<div style="width:300px;float:left">
                                <input type="text" name="state" class="form-control" id="state" value="{{ old('state', $user->state)}}">
                                </div>
								<div style="width:95px;float:right">
								<select class="form-control" id="state_bit"  name="state_bit">
								 
								 <option value="0">Public</option>
								 <option {{ (in_array('state',$private_flags)) ? "selected='selected'" : '' }} value="state">Private</option>
								</select> 
								</div>  
							</div>
                            <div class="col-sm-6 margin-btm-1">
                                <label for="country">Country <span style="color:red;">*</span></label>
								</br>
								<div style="width:300px;float:left">
                                <select name="country" id="country" class="form-control">
                                    <option value="US">United States</option>
                                </select>
                               </div>
								<div style="width:95px;float:right">
								<select class="form-control" id="country_bit"  name="country_bit">
								 
								 <option value="0">Public</option>
								 <option {{ (in_array('country',$private_flags)) ? "selected='selected'" : '' }} value="country">Private</option>
								</select> 
								</div> 
							</div>
                            <div class="col-sm-6 margin-btm-1">
                                <label for="postal_code">Zip Code</label>
								</br>
								<div style="width:300px;float:left">
                                <input type="text" name="postal_code" class="form-control" id="postal_code" value="{{ old('postal_code', $user->postal_code)}}">
                               </div>
								<div style="width:95px;float:right">
								<select class="form-control" id="postal_code_bit"  name="postal_code_bit">
								 
								 <option value="0">Public</option>
								 <option {{ (in_array('postal_code',$private_flags)) ? "selected='selected'" : '' }} value="postal_code">Private</option>
								</select> 
								</div> 
							</div>
                        </div>
                       
                        <button type="submit" id="submit" class="btn btn-login">Update</button>
                    </form>  
        </div>
    </div>   
 </div></div>
</div>  <!-- /.right-whitePnl-->

    <script>
        $(document).ready(function () {
            $("#birthday").datepicker({
                changeMonth: true,
                changeYear: true,
				maxDate: 0
            });
        })
    </script>


    @endsection

