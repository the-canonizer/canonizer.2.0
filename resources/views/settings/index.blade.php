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
                <li class="active"><a class="" href="{{ route('settings')}}">Profile Info</a></li>
                <li class=""><a class="" href="{{ route('settings.sociallinks')}}">Social Oauth Verification</a></li>                
                <li><a class="" href="{{ route('settings.changepassword')}}">Change Password</a></li>
                <li><a class="" href="{{ route('settings.nickname')}}" >Nick Names</a></li>
                <li class=""><a class="" href="{{ route('settings.support')}}" >Supported Camps</a></li>
                <!-- <li><a class="" href="{{ route('settings.algo-preferences')}}">Default Algorithm</a></li> -->
                <li class=""><a class="" href="{{ route('settings.blockchain')}}">Crypto Verification (was Metamask Account)</a></li>
				
            </ul>

            <div id="myTabContent" class="" style="margin-top:20px;">
			<form name="verify_number" action="{{ route('settings.phone.verify',['id'=>$user->id])}}" method="post">
                   <input type="hidden" name="_token" value="{{ csrf_token() }}">
				   <input type="hidden" name="verify_phone" value="1">
                   <div class="row">
                            <div class="col-sm-6 margin-btm-1">
                                <label for="phone_number">Phone Number <span style="color:red;">*</span></label>
								</br>
                                <div style="width:300px;float:left">
								<input type="text" onkeydown="restrictTextField(event,10);" onkeyup="onlyNumbers(event);" name="phone_number" class="form-control" id="phone_number" value="{{ old('phone_number',$user->phone_number)}}">
								@if ($errors->has('phone_number')) <p class="help-block">{{ $errors->first('phone_number') }}</p> @endif
								
								</div>
								<?php if ($user->mobile_verified) { ?>
								<div style="width:95px;float:right">
								<img style="width:35px" src="{{ URL::asset('/img/tick_sended.png')}}">
								</div>
								<?php } ?>
								
                            </div>
							<div class="col-sm-6 margin-btm-1">
                                <label for="mobile_carrier">Mobile Carrier <span style="color:red;">*</span></label></br>
                                <div style="width:300px;float:left">
								 <select class="form-control" id="mobile_carrier"  name="mobile_carrier">
								 <option value="sms.alltelwireless.com" <?php echo ($user->mobile_carrier=="sms.alltelwireless.com") ? "selected='selected'" : ""; ?> >Alltel</option>
								 <option value="txt.att.net" <?php echo ($user->mobile_carrier=="txt.att.net") ? "selected='selected'" : ""; ?>>AT&T</option>
								 <option value="sms.myboostmobile.com" <?php echo ($user->mobile_carrier=="sms.myboostmobile.com") ? "selected='selected'" : ""; ?>>Boost Mobile</option>
								 <option value="mms.cricketwireless.net" <?php echo ($user->mobile_carrier=="mms.cricketwireless.net") ? "selected='selected'" : ""; ?>>Cricket Wireless</option>
								 <option value="mymetropcs.com" <?php echo ($user->mobile_carrier=="mymetropcs.com") ? "selected='selected'" : ""; ?>>MetroPCS</option>
								 <option value="text.republicwireless.com" <?php echo ($user->mobile_carrier=="text.republicwireless.com") ? "selected='selected'" : ""; ?>>Republic Wireless</option>
								 <option value="messaging.sprintpcs.com" <?php echo ($user->mobile_carrier=="messaging.sprintpcs.com") ? "selected='selected'" : ""; ?>>Sprint</option>
								 <option value="tmomail.net" <?php echo ($user->mobile_carrier=="tmomail.net") ? "selected='selected'" : ""; ?>>T-Mobile</option>
								 <option value="email.uscc.net" <?php echo ($user->mobile_carrier=="email.uscc.net") ? "selected='selected'" : ""; ?>>U.S. Cellular</option>
								 <option value="vtext.com" <?php echo ($user->mobile_carrier=="vtext.com") ? "selected='selected'" : ""; ?>>Verizon Wireless</option>
								 <option value="vmobl.com" <?php echo ($user->mobile_carrier=="vmobl.com") ? "selected='selected'" : ""; ?>>Virgin Mobile</option>
								 <option value="non_usa" <?php echo ($user->mobile_carrier=="non_usa") ? "selected='selected'" : ""; ?>>Non USA</option>
                                 </select> 
								</div>
								
                            </div>
							
							@if(Session::has('otpsent'))
							<div class="alert alert-danger" style="width: 80%;margin-left: 10%;text-align: center">
								@if ($errors->has('verify_code')) 
									<strong>Verification : </strong>{{ $errors->first('verify_code') }}
								@else 
									<strong>Verification : </strong>{{ Session::get('otpsent')}} 
								@endif
								  
							</div>
							<br/> 
							<div class="col-sm-6 margin-btm-1">
                                <label for="verify_code">Verify Code <span style="color:red;">*</span> </label>
                                
								<input type="text" onkeydown="restrictTextField(event,6);" onkeyup="onlyNumbers(event);" name="verify_code" class="form-control" id="verify_code" value="">
															
                            </div>
							@endif
							
							<div style="text-align:center; width:90%">							
							@if(Session::has('otpsent'))
							<button type="submit" id="confirm_phone_email" class="btn btn-login">Confirm</button>
						    @else
							<button id="verifyButton" type="submit" id="verify_phone_email" class="btn btn-login">Verify</button>
                            @endif 	
							</div>
					</div>
					</form>
					<hr/>
                    <form action="{{ route('settings.profile.update',['id'=>$user->id])}}" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
						<?php $private_flags = explode(",",$user->private_flags); ?>
                        <div class="row">
                            <div class="col-sm-6 margin-btm-1">
                                <label for="topic name">First Name ( Limit 100 Chars ) <span style="color:red;">*</span></label></br>
                                <div style="width:300px;float:left">
								<input type="text" onkeydown="restrictTextField(event,100)" name="first_name" class="form-control" id="first_name" value="{{ old('firstname',$user->first_name)}}">
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
                                <label for="namespace">Middle Name ( Limit 100 Chars )</label><br/>
                                <div style="width:300px;float:left">
								<input type="text" onkeydown="restrictTextField(event,100)" name="middle_name" class="form-control" id="middle_name" value="{{ old('middle_name', $user->middle_name)}}">
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
                                <label for="last_name">Last Name ( Limit 100 Chars ) <span style="color:red;">*</span></label></br>
								<div style="width:300px;float:left">
                                <input type="text"  onkeydown="restrictTextField(event,100)" name="last_name" class="form-control" id="last_name" value="{{ old('last_name', $user->last_name)}}">
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
                                <label for="language">Language</label>
                                <select name="language" id="language" class="form-control">
                                    <option value="English" {{ (old('language',$user->language) == 'English') ? 'selected' : ''}}>English</option>
                                    <option value="French" {{ (old('language',$user->language) == 'French') ? 'selected' : ''}}>French</option>                                   
                                    <option value="Hindi" {{ (old('language',$user->language) == 'Hindi') ? 'selected' : ''}}>Hindi</option>
									 <option value="Spanish" {{ (old('language',$user->language) == 'Spanish') ? 'selected' : ''}}>Spanish</option>
                                </select>
                            </div>
                            <div class="col-sm-6 margin-btm-1">
                                <label for="dob">Date Of Birth</label>
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
                                <label for="address_1">Address Line 1 (Limit 255 Chars) </label>
								</br>
								<div style="width:300px;float:left">
                                <input type="text" onkeyup="blankAddress();" onkeydown="restrictTextField(event,255);" onfocus="initAutocomplete()" onfocusout="emptyAddress()" name="address_1" class="form-control" id="address_1" value="{{ old('address_1', $user->address_1)}}">
                                <span style="color:red;">Note*: To update address please select a address from suggesstion box</span>
                                </div>
								<div style="width:95px;float:right">
								<select class="form-control" id="address_1_bit"  name="address_1_bit">
								 
								 <option value="0">Public</option>
								 <option {{ (in_array('address_1',$private_flags)) ? "selected='selected'" : '' }} value="address_1">Private</option>
								</select> 
								</div> 
							</div>
                            <div class="col-sm-6 margin-btm-1">
                                <label for="address_2">Address Line 2 (Limit 255 Chars)</label>
								</br>
								<div style="width:300px;float:left">
                                <input type="text" readonly name="address_2" onkeydown="restrictTextField(event,255)" class="form-control" id="address_2" value="{{ old('address_2', $user->address_2)}}">
                                </div>
								<div style="width:95px;float:right">
								<select class="form-control" id="address_2_bit"  name="address_2_bit">
								 
								 <option value="0">Public</option>
								 <option {{ (in_array('address_2',$private_flags)) ? "selected='selected'" : '' }} value="address_2">Private</option>
								</select> 
								</div>  
							</div>
                            <div class="col-sm-6 margin-btm-1">

                                <label for="city">City (Limit 255 Chars)</label>
								</br>
								<div style="width:300px;float:left">
                                <input type="text" readonly onkeydown="restrictTextField(event,255)" name="city" class="form-control" id="city" value="{{ old('city', $user->city)}}">
                                </div>
								<div style="width:95px;float:right">
								<select class="form-control" id="city_bit"  name="city_bit">
								 
								 <option value="0">Public</option>
								 <option {{ (in_array('city',$private_flags)) ? "selected='selected'" : '' }} value="city">Private</option>
								</select> 
								</div> 
							</div>
                            <div class="col-sm-6 margin-btm-1">
                                <label for="state">State (Limit 255 Chars) </label>
								</br>
								<div style="width:300px;float:left">
                                <input type="text" readonly onkeydown="restrictTextField(event,255)" name="state" class="form-control" id="state" value="{{ old('state', $user->state)}}">
                                </div>
								<div style="width:95px;float:right">
								<select class="form-control" id="state_bit"  name="state_bit">
								 
								 <option value="0">Public</option>
								 <option {{ (in_array('state',$private_flags)) ? "selected='selected'" : '' }} value="state">Private</option>
								</select> 
								</div>  
							</div>
                            <div class="col-sm-6 margin-btm-1">
                                <label for="country">Country <span style="color:red;"></span></label>
								</br>
								<div style="width:300px;float:left">
							    <input type="hidden" name="country" id="country" value="{{$user->country}}" />
                                <select name="country_select" disabled id="country_select" class="form-control">
                                	<option value=""> -- Select -- </option>
                                    <option value="AF" {{ ($user->country == 'AF') ? "selected='selected'" : '' }} >Afghanistan</option>
                                    <option value="AL" {{ ($user->country == 'AL') ? "selected='selected'" : '' }}>Albania</option>
                                    <option value="DZ" {{ ($user->country == 'DZ') ? "selected='selected'" : '' }}>Algeria</option>
                                    <option value="AS" {{ ($user->country == 'AS') ? "selected='selected'" : '' }}>American Samoa</option>
                                    <option value="AD" {{ ($user->country == 'AD') ? "selected='selected'" : '' }}>Andorra</option>
                                    <option value="AO" {{ ($user->country == 'AO') ? "selected='selected'" : '' }}>Angola</option>
                                    <option value="AI" {{ ($user->country == 'AI') ? "selected='selected'" : '' }}>Anguilla</option>
                                    <option value="AQ" {{ ($user->country == 'AQ') ? "selected='selected'" : '' }}>Antarctica</option>
                                    <option value="AG" {{ ($user->country == 'AG') ? "selected='selected'" : '' }}>Antigua And Barbuda</option>
                                    <option value="AR" {{ ($user->country == 'AR') ? "selected='selected'" : '' }}>Argentina</option>
                                    <option value="AM" {{ ($user->country == 'AM') ? "selected='selected'" : '' }}>Armenia</option>
                                    <option value="AW" {{ ($user->country == 'AW') ? "selected='selected'" : '' }}>Aruba</option>
                                    <option value="AU" {{ ($user->country == 'AU') ? "selected='selected'" : '' }}>Australia</option>
                                    <option value="AT" {{ ($user->country == 'AT') ? "selected='selected'" : '' }}>Austria</option>
                                    <option value="AZ" {{ ($user->country == 'AZ') ? "selected='selected'" : '' }}>Azerbaijan</option>
                                    <option value="BS" {{ ($user->country == 'BS') ? "selected='selected'" : '' }}>Bahamas</option>
                                    <option value="BH" {{ ($user->country == 'BH') ? "selected='selected'" : '' }}>Bahrain</option>
                                    <option value="BD" {{ ($user->country == 'BD') ? "selected='selected'" : '' }}>Bangladesh</option>
                                    <option value="BB" {{ ($user->country == 'BB') ? "selected='selected'" : '' }}>Barbados</option>
                                    <option value="BY" {{ ($user->country == 'BY') ? "selected='selected'" : '' }}>Belarus</option>
                                    <option value="BE" {{ ($user->country == 'BE') ? "selected='selected'" : '' }}>Belgium</option>
                                    <option value="BZ" {{ ($user->country == 'BZ') ? "selected='selected'" : '' }}>Belize</option>
                                    <option value="BJ" {{ ($user->country == 'BJ') ? "selected='selected'" : '' }}>Benin</option>
                                    <option value="BM" {{ ($user->country == 'BM') ? "selected='selected'" : '' }}>Bermuda</option>
                                    <option value="BT" {{ ($user->country == 'BT') ? "selected='selected'" : '' }}>Bhutan</option>
                                    <option value="BO" {{ ($user->country == 'BO') ? "selected='selected'" : '' }}>Bolivia</option>
                                    <option value="BA" {{ ($user->country == 'BA') ? "selected='selected'" : '' }}>Bosnia And Herzegovina</option>
                                    <option value="BW" {{ ($user->country == 'BW') ? "selected='selected'" : '' }}>Botswana</option>
                                    <option value="BV" {{ ($user->country == 'BV') ? "selected='selected'" : '' }}>Bouvet Island</option>
                                    <option value="BR" {{ ($user->country == 'BR') ? "selected='selected'" : '' }}>Brazil</option>
                                    <option value="IO" {{ ($user->country == 'IO') ? "selected='selected'" : '' }}>British Indian Ocean Territory</option>
                                    <option value="BN" {{ ($user->country == 'BN') ? "selected='selected'" : '' }}>Brunei Darussalam</option>
                                    <option value="BG" {{ ($user->country == 'BG') ? "selected='selected'" : '' }}>Bulgaria</option>
                                    <option value="BF" {{ ($user->country == 'BF') ? "selected='selected'" : '' }}>Burkina Faso</option>
                                    <option value="BI" {{ ($user->country == 'BI') ? "selected='selected'" : '' }}>Burundi</option>
                                    <option value="KH" {{ ($user->country == 'KH') ? "selected='selected'" : '' }}>Cambodia</option>
                                    <option value="CM" {{ ($user->country == 'CM') ? "selected='selected'" : '' }}>Cameroon</option>
                                    <option value="CA" {{ ($user->country == 'CA') ? "selected='selected'" : '' }}>Canada</option>
                                    <option value="CV" {{ ($user->country == 'CV') ? "selected='selected'" : '' }}>Cape Verde</option>
                                    <option value="KY" {{ ($user->country == 'KY') ? "selected='selected'" : '' }}>Cayman Islands</option>
                                    <option value="CF" {{ ($user->country == 'CF') ? "selected='selected'" : '' }}>Central African Republic</option>
                                    <option value="TD" {{ ($user->country == 'TD') ? "selected='selected'" : '' }}>Chad</option>
                                    <option value="CL" {{ ($user->country == 'CL') ? "selected='selected'" : '' }}>Chile</option>
                                    <option value="CN" {{ ($user->country == 'CN') ? "selected='selected'" : '' }}>China</option>
                                    <option value="CX" {{ ($user->country == 'CX') ? "selected='selected'" : '' }}>Christmas Island</option>
                                    <option value="CC" {{ ($user->country == 'CC') ? "selected='selected'" : '' }}>Cocos (keeling) Islands</option>
                                    <option value="CO" {{ ($user->country == 'CO') ? "selected='selected'" : '' }}>Colombia</option>
                                    <option value="KM" {{ ($user->country == 'KM') ? "selected='selected'" : '' }}>Comoros</option>
                                    <option value="CG" {{ ($user->country == 'CG') ? "selected='selected'" : '' }}>Congo</option>
                                    <option value="CD" {{ ($user->country == 'CD') ? "selected='selected'" : '' }}>Congo</option>
                                    <option value="CK" {{ ($user->country == 'CK') ? "selected='selected'" : '' }}>Cook Islands</option>
                                    <option value="CR" {{ ($user->country == 'CR') ? "selected='selected'" : '' }}>Costa Rica</option>
                                    <option value="CI" {{ ($user->country == 'CI') ? "selected='selected'" : '' }}>Cote D'ivoire</option>
                                    <option value="HR" {{ ($user->country == 'HR') ? "selected='selected'" : '' }}>Croatia</option>
                                    <option value="CU" {{ ($user->country == 'CU') ? "selected='selected'" : '' }}>Cuba</option>
                                    <option value="CY" {{ ($user->country == 'CY') ? "selected='selected'" : '' }}>Cyprus</option>
                                    <option value="CZ" {{ ($user->country == 'CZ') ? "selected='selected'" : '' }}>Czech Republic</option>
                                    <option value="DK" {{ ($user->country == 'DK') ? "selected='selected'" : '' }}>Denmark</option>
                                    <option value="DJ" {{ ($user->country == 'DJ') ? "selected='selected'" : '' }}>Djibouti</option>
                                    <option value="DM" {{ ($user->country == 'DM') ? "selected='selected'" : '' }}>Dominica</option>
                                    <option value="DO" {{ ($user->country == 'DO') ? "selected='selected'" : '' }}>Dominican Republic</option>
                                    <option value="EC" {{ ($user->country == 'EC') ? "selected='selected'" : '' }}>Ecuador</option>
                                    <option value="EG" {{ ($user->country == 'EG') ? "selected='selected'" : '' }}>Egypt</option>
                                    <option value="SV" {{ ($user->country == 'SV') ? "selected='selected'" : '' }}>El Salvador</option>
                                    <option value="GQ" {{ ($user->country == 'GQ') ? "selected='selected'" : '' }}>Equatorial Guinea</option>
                                    <option value="ER" {{ ($user->country == 'ER') ? "selected='selected'" : '' }}>Eritrea</option>
                                    <option value="EE" {{ ($user->country == 'EE') ? "selected='selected'" : '' }}>Estonia</option>
                                    <option value="ET" {{ ($user->country == 'ET') ? "selected='selected'" : '' }}>Ethiopia</option>
                                    <option value="FK" {{ ($user->country == 'FK') ? "selected='selected'" : '' }}>Falkland Islands (malvinas)</option>
                                    <option value="FO" {{ ($user->country == 'FO') ? "selected='selected'" : '' }}>Faroe Islands</option>
                                    <option value="FJ" {{ ($user->country == 'FJ') ? "selected='selected'" : '' }}>Fiji</option>
                                    <option value="FI" {{ ($user->country == 'FI') ? "selected='selected'" : '' }}>Finland</option>
                                    <option value="FR" {{ ($user->country == 'FR') ? "selected='selected'" : '' }}>France</option>
                                    <option value="GF" {{ ($user->country == 'GF') ? "selected='selected'" : '' }}>French Guiana</option>
                                    <option value="PF" {{ ($user->country == 'PF') ? "selected='selected'" : '' }}>French Polynesia</option>
                                    <option value="TF" {{ ($user->country == 'TF') ? "selected='selected'" : '' }}>French Southern Territories</option>
                                    <option value="GA" {{ ($user->country == 'GA') ? "selected='selected'" : '' }}>Gabon</option>
                                    <option value="GM" {{ ($user->country == 'GM') ? "selected='selected'" : '' }}>Gambia</option>
                                    <option value="GE" {{ ($user->country == 'GE') ? "selected='selected'" : '' }}>Georgia</option>
                                    <option value="DE" {{ ($user->country == 'DE') ? "selected='selected'" : '' }}>Germany</option>
                                    <option value="GH" {{ ($user->country == 'GH') ? "selected='selected'" : '' }}>Ghana</option>
                                    <option value="GI" {{ ($user->country == 'GI') ? "selected='selected'" : '' }}>Gibraltar</option>
                                    <option value="GR" {{ ($user->country == 'GR') ? "selected='selected'" : '' }}>Greece</option>
                                    <option value="GL" {{ ($user->country == 'GL') ? "selected='selected'" : '' }}>Greenland</option>
                                    <option value="GD" {{ ($user->country == 'GD') ? "selected='selected'" : '' }}>Grenada</option>
                                    <option value="GP" {{ ($user->country == 'GP') ? "selected='selected'" : '' }}>Guadeloupe</option>
                                    <option value="GU" {{ ($user->country == 'GU') ? "selected='selected'" : '' }}>Guam</option>
                                    <option value="GT" {{ ($user->country == 'GT') ? "selected='selected'" : '' }}>Guatemala</option>
                                    <option value="GN" {{ ($user->country == 'GN') ? "selected='selected'" : '' }}>Guinea</option>
                                    <option value="GW" {{ ($user->country == 'GW') ? "selected='selected'" : '' }}>Guinea-bissau</option>
                                    <option value="GY" {{ ($user->country == 'GY') ? "selected='selected'" : '' }}>Guyana</option>
                                    <option value="HT" {{ ($user->country == 'HT') ? "selected='selected'" : '' }}>Haiti</option>
                                    <option value="HM" {{ ($user->country == 'HM') ? "selected='selected'" : '' }}>Heard Island And Mcdonald Islands</option>
                                    <option value="VA" {{ ($user->country == 'VA') ? "selected='selected'" : '' }}>Holy See (vatican City State)</option>
                                    <option value="HN" {{ ($user->country == 'HN') ? "selected='selected'" : '' }}>Honduras</option>
                                    <option value="HK" {{ ($user->country == 'HK') ? "selected='selected'" : '' }}>Hong Kong</option>
                                    <option value="HU" {{ ($user->country == 'HU') ? "selected='selected'" : '' }}>Hungary</option>
                                    <option value="IS" {{ ($user->country == 'IS') ? "selected='selected'" : '' }}>Iceland</option>
                                    <option value="IN" {{ ($user->country == 'IN') ? "selected='selected'" : '' }}>India</option>
                                    <option value="ID" {{ ($user->country == 'ID') ? "selected='selected'" : '' }}>Indonesia</option>
                                    <option value="IR" {{ ($user->country == 'IR') ? "selected='selected'" : '' }}>Iran, Islamic Republic Of</option>
                                    <option value="IQ" {{ ($user->country == 'IQ') ? "selected='selected'" : '' }}>Iraq</option>
                                    <option value="IE" {{ ($user->country == 'IE') ? "selected='selected'" : '' }}>Ireland</option>
                                    <option value="IL" {{ ($user->country == 'IL') ? "selected='selected'" : '' }}>Israel</option>
                                    <option value="IT" {{ ($user->country == 'IT') ? "selected='selected'" : '' }}>Italy</option>
                                    <option value="JM" {{ ($user->country == 'JM') ? "selected='selected'" : '' }}>Jamaica</option>
                                    <option value="JP" {{ ($user->country == 'JP') ? "selected='selected'" : '' }}>Japan</option>
                                    <option value="JO" {{ ($user->country == 'JO') ? "selected='selected'" : '' }}>Jordan</option>
                                    <option value="KZ" {{ ($user->country == 'KZ') ? "selected='selected'" : '' }}>Kazakhstan</option>
                                    <option value="KE" {{ ($user->country == 'KE') ? "selected='selected'" : '' }}>Kenya</option>
                                    <option value="KI" {{ ($user->country == 'KI') ? "selected='selected'" : '' }}>Kiribati</option>
                                    <option value="KP" {{ ($user->country == 'KP') ? "selected='selected'" : '' }}>Korea, Democratic People's Republic Of</option>
                                    <option value="KR" {{ ($user->country == 'KR') ? "selected='selected'" : '' }}>Korea, Republic Of</option>
                                    <option value="KW" {{ ($user->country == 'KW') ? "selected='selected'" : '' }}>Kuwait</option>
                                    <option value="KG" {{ ($user->country == 'KG') ? "selected='selected'" : '' }}>Kyrgyzstan</option>
                                    <option value="LA" {{ ($user->country == 'LA') ? "selected='selected'" : '' }}>Lao People's Democratic Republic</option>
                                    <option value="LV" {{ ($user->country == 'LV') ? "selected='selected'" : '' }}>Latvia</option>
                                    <option value="LB" {{ ($user->country == 'LB') ? "selected='selected'" : '' }}>Lebanon</option>
                                    <option value="LS" {{ ($user->country == 'LS') ? "selected='selected'" : '' }}>Lesotho</option>
                                    <option value="LR" {{ ($user->country == 'LR') ? "selected='selected'" : '' }}>Liberia</option>
                                    <option value="LY" {{ ($user->country == 'LY') ? "selected='selected'" : '' }}>Libyan Arab Jamahiriya</option>
                                    <option value="LI" {{ ($user->country == 'LI') ? "selected='selected'" : '' }}>Liechtenstein</option>
                                    <option value="LT" {{ ($user->country == 'LT') ? "selected='selected'" : '' }}>Lithuania</option>
                                    <option value="LU" {{ ($user->country == 'LU') ? "selected='selected'" : '' }}>Luxembourg</option>
                                    <option value="MO" {{ ($user->country == 'MO') ? "selected='selected'" : '' }}>Macao</option>
                                    <option value="MK" {{ ($user->country == 'MK') ? "selected='selected'" : '' }}>Macedonia, The Former Yugoslav Republic Of</option>
                                    <option value="MG" {{ ($user->country == 'MG') ? "selected='selected'" : '' }}>Madagascar</option>
                                    <option value="MW" {{ ($user->country == 'MW') ? "selected='selected'" : '' }}>Malawi</option>
                                    <option value="MY" {{ ($user->country == 'MY') ? "selected='selected'" : '' }}>Malaysia</option>
                                    <option value="MV" {{ ($user->country == 'MV') ? "selected='selected'" : '' }}>Maldives</option>
                                    <option value="ML" {{ ($user->country == 'ML') ? "selected='selected'" : '' }}>Mali</option>
                                    <option value="MT" {{ ($user->country == 'MT') ? "selected='selected'" : '' }}>Malta</option>
                                    <option value="MH" {{ ($user->country == 'MH') ? "selected='selected'" : '' }}>Marshall Islands</option>
                                    <option value="MQ" {{ ($user->country == 'MQ') ? "selected='selected'" : '' }}>Martinique</option>
                                    <option value="MR" {{ ($user->country == 'MR') ? "selected='selected'" : '' }}>Mauritania</option>
                                    <option value="MU" {{ ($user->country == 'MU') ? "selected='selected'" : '' }}>Mauritius</option>
                                    <option value="YT" {{ ($user->country == 'YT') ? "selected='selected'" : '' }}>Mayotte</option>
                                    <option value="MX" {{ ($user->country == 'MX') ? "selected='selected'" : '' }}>Mexico</option>
                                    <option value="FM" {{ ($user->country == 'FM') ? "selected='selected'" : '' }}>Micronesia, Federated States Of</option>
                                    <option value="MD" {{ ($user->country == 'MD') ? "selected='selected'" : '' }}>Moldova, Republic Of</option>
                                    <option value="MC" {{ ($user->country == 'MC') ? "selected='selected'" : '' }}>Monaco</option>
                                    <option value="MN" {{ ($user->country == 'MN') ? "selected='selected'" : '' }}>Mongolia</option>
                                    <option value="MS" {{ ($user->country == 'MS') ? "selected='selected'" : '' }}>Montserrat</option>
                                    <option value="MA" {{ ($user->country == 'MA') ? "selected='selected'" : '' }}>Morocco</option>
                                    <option value="MZ" {{ ($user->country == 'MZ') ? "selected='selected'" : '' }}>Mozambique</option>
                                    <option value="MM" {{ ($user->country == 'MM') ? "selected='selected'" : '' }}>Myanmar</option>
                                    <option value="NA" {{ ($user->country == 'NA') ? "selected='selected'" : '' }}>Namibia</option>
                                    <option value="NR" {{ ($user->country == 'NR') ? "selected='selected'" : '' }}>Nauru</option>
                                    <option value="NP" {{ ($user->country == 'NP') ? "selected='selected'" : '' }}>Nepal</option>
                                    <option value="NL" {{ ($user->country == 'NL') ? "selected='selected'" : '' }}>Netherlands</option>
                                    <option value="AN" {{ ($user->country == 'AN') ? "selected='selected'" : '' }}>Netherlands Antilles</option>
                                    <option value="NC" {{ ($user->country == 'NC') ? "selected='selected'" : '' }}>New Caledonia</option>
                                    <option value="NZ" {{ ($user->country == 'NZ') ? "selected='selected'" : '' }}>New Zealand</option>
                                    <option value="NI" {{ ($user->country == 'NI') ? "selected='selected'" : '' }}>Nicaragua</option>
                                    <option value="NE" {{ ($user->country == 'NE') ? "selected='selected'" : '' }}>Niger</option>
                                    <option value="NG" {{ ($user->country == 'NG') ? "selected='selected'" : '' }}>Nigeria</option>
                                    <option value="NU" {{ ($user->country == 'NU') ? "selected='selected'" : '' }}>Niue</option>
                                    <option value="NF" {{ ($user->country == 'NF') ? "selected='selected'" : '' }}>Norfolk Island</option>
                                    <option value="MP" {{ ($user->country == 'MP') ? "selected='selected'" : '' }}>Northern Mariana Islands</option>
                                    <option value="NO" {{ ($user->country == 'NO') ? "selected='selected'" : '' }}>Norway</option>
                                    <option value="OM" {{ ($user->country == 'OM') ? "selected='selected'" : '' }}>Oman</option>
                                    <option value="PK" {{ ($user->country == 'PK') ? "selected='selected'" : '' }}>Pakistan</option>
                                    <option value="PW" {{ ($user->country == 'PW') ? "selected='selected'" : '' }}>Palau</option>
                                    <option value="PS" {{ ($user->country == 'PS') ? "selected='selected'" : '' }}>Palestinian Territory, Occupied</option>
                                    <option value="PA" {{ ($user->country == 'PA') ? "selected='selected'" : '' }}>Panama</option>
                                    <option value="PG" {{ ($user->country == 'PG') ? "selected='selected'" : '' }}>Papua New Guinea</option>
                                    <option value="PY" {{ ($user->country == 'PY') ? "selected='selected'" : '' }}>Paraguay</option>
                                    <option value="PE" {{ ($user->country == 'PE') ? "selected='selected'" : '' }}>Peru</option>
                                    <option value="PH" {{ ($user->country == 'PH') ? "selected='selected'" : '' }}>Philippines</option>
                                    <option value="PN" {{ ($user->country == 'PN') ? "selected='selected'" : '' }}>Pitcairn</option>
                                    <option value="PL" {{ ($user->country == 'PL') ? "selected='selected'" : '' }}>Poland</option>
                                    <option value="PT" {{ ($user->country == 'PT') ? "selected='selected'" : '' }}>Portugal</option>
                                    <option value="PR" {{ ($user->country == 'PR') ? "selected='selected'" : '' }}>Puerto Rico</option>
                                    <option value="QA" {{ ($user->country == 'QA') ? "selected='selected'" : '' }}>Qatar</option>
                                    <option value="RE" {{ ($user->country == 'RE') ? "selected='selected'" : '' }}>Reunion</option>
                                    <option value="RO" {{ ($user->country == 'RO') ? "selected='selected'" : '' }}>Romania</option>
                                    <option value="RU" {{ ($user->country == 'RU') ? "selected='selected'" : '' }}>Russian Federation</option>
                                    <option value="RW" {{ ($user->country == 'RW') ? "selected='selected'" : '' }}>Rwanda</option>
                                    <option value="SH" {{ ($user->country == 'SH') ? "selected='selected'" : '' }}>Saint Helena</option>
                                    <option value="KN" {{ ($user->country == 'KN') ? "selected='selected'" : '' }}>Saint Kitts And Nevis</option>
                                    <option value="LC" {{ ($user->country == 'LC') ? "selected='selected'" : '' }}>Saint Lucia</option>
                                    <option value="PM" {{ ($user->country == 'PM') ? "selected='selected'" : '' }}>Saint Pierre And Miquelon</option>
                                    <option value="VC" {{ ($user->country == 'VC') ? "selected='selected'" : '' }}>Saint Vincent And The Grenadines</option>
                                    <option value="WS" {{ ($user->country == 'WS') ? "selected='selected'" : '' }}>Samoa</option>
                                    <option value="SM" {{ ($user->country == 'SM') ? "selected='selected'" : '' }}>San Marino</option>
                                    <option value="ST" {{ ($user->country == 'ST') ? "selected='selected'" : '' }}>Sao Tome And Principe</option>
                                    <option value="SA" {{ ($user->country == 'SA') ? "selected='selected'" : '' }}>Saudi Arabia</option>
                                    <option value="SN" {{ ($user->country == 'SN') ? "selected='selected'" : '' }}>Senegal</option>
                                    <option value="CS" {{ ($user->country == 'CS') ? "selected='selected'" : '' }}>Serbia And Montenegro</option>
                                    <option value="SC" {{ ($user->country == 'CO') ? "selected='selected'" : '' }}>Seychelles</option>
                                    <option value="SL" {{ ($user->country == 'SL') ? "selected='selected'" : '' }}>Sierra Leone</option>
                                    <option value="SG" {{ ($user->country == 'SG') ? "selected='selected'" : '' }}>Singapore</option>
                                    <option value="SK" {{ ($user->country == 'SK') ? "selected='selected'" : '' }}>Slovakia</option>
                                    <option value="SI" {{ ($user->country == 'SI') ? "selected='selected'" : '' }}>Slovenia</option>
                                    <option value="SB" {{ ($user->country == 'SB') ? "selected='selected'" : '' }}>Solomon Islands</option>
                                    <option value="SO" {{ ($user->country == 'SO') ? "selected='selected'" : '' }}>Somalia</option>
                                    <option value="ZA" {{ ($user->country == 'ZA') ? "selected='selected'" : '' }}>South Africa</option>
                                    <option value="GS" {{ ($user->country == 'GS') ? "selected='selected'" : '' }}>South Georgia And The South Sandwich Islands</option>
                                    <option value="ES" {{ ($user->country == 'ES') ? "selected='selected'" : '' }}>Spain</option>
                                    <option value="LK" {{ ($user->country == 'LK') ? "selected='selected'" : '' }}>Sri Lanka</option>
                                    <option value="SD" {{ ($user->country == 'SD') ? "selected='selected'" : '' }}>Sudan</option>
                                    <option value="SR" {{ ($user->country == 'SR') ? "selected='selected'" : '' }}>Suriname</option>
                                    <option value="SJ" {{ ($user->country == 'SJ') ? "selected='selected'" : '' }}>Svalbard And Jan Mayen</option>
                                    <option value="SZ" {{ ($user->country == 'SZ') ? "selected='selected'" : '' }}>Swaziland</option>
                                    <option value="SE" {{ ($user->country == 'SE') ? "selected='selected'" : '' }}>Sweden</option>
                                    <option value="CH" {{ ($user->country == 'CH') ? "selected='selected'" : '' }}>Switzerland</option>
                                    <option value="SY" {{ ($user->country == 'SY') ? "selected='selected'" : '' }}>Syrian Arab Republic</option>
                                    <option value="TW" {{ ($user->country == 'TW') ? "selected='selected'" : '' }}>Taiwan, Province Of China</option>
                                    <option value="TJ" {{ ($user->country == 'TJ') ? "selected='selected'" : '' }}>Tajikistan</option>
                                    <option value="TZ" {{ ($user->country == 'TZ') ? "selected='selected'" : '' }}>Tanzania, United Republic Of</option>
                                    <option value="TH" {{ ($user->country == 'TH') ? "selected='selected'" : '' }}>Thailand</option>
                                    <option value="TL" {{ ($user->country == 'TL') ? "selected='selected'" : '' }}>Timor-leste</option>
                                    <option value="TG" {{ ($user->country == 'TG') ? "selected='selected'" : '' }}>Togo</option>
                                    <option value="TK" {{ ($user->country == 'TK') ? "selected='selected'" : '' }}>Tokelau</option>
                                    <option value="TO" {{ ($user->country == 'TO') ? "selected='selected'" : '' }}>Tonga</option>
                                    <option value="TT" {{ ($user->country == 'TT') ? "selected='selected'" : '' }}>Trinidad And Tobago</option>
                                    <option value="TN" {{ ($user->country == 'TN') ? "selected='selected'" : '' }}>Tunisia</option>
                                    <option value="TR" {{ ($user->country == 'TR') ? "selected='selected'" : '' }}>Turkey</option>
                                    <option value="TM" {{ ($user->country == 'TM') ? "selected='selected'" : '' }}>Turkmenistan</option>
                                    <option value="TC" {{ ($user->country == 'TC') ? "selected='selected'" : '' }}>Turks And Caicos Islands</option>
                                    <option value="TV" {{ ($user->country == 'TV') ? "selected='selected'" : '' }}>Tuvalu</option>
                                    <option value="UG" {{ ($user->country == 'UG') ? "selected='selected'" : '' }}>Uganda</option>
                                    <option value="UA" {{ ($user->country == 'UA') ? "selected='selected'" : '' }}>Ukraine</option>
                                    <option value="AE" {{ ($user->country == 'AE') ? "selected='selected'" : '' }}>United Arab Emirates</option>
                                    <option value="GB" {{ ($user->country == 'GB') ? "selected='selected'" : '' }}>United Kingdom</option>
                                    <option value="US" {{ ($user->country == 'US') ? "selected='selected'" : '' }}>United States</option>
                                    <option value="UM" {{ ($user->country == 'UM') ? "selected='selected'" : '' }}>United States Minor Outlying Islands</option>
                                    <option value="UY" {{ ($user->country == 'UY') ? "selected='selected'" : '' }}>Uruguay</option>
                                    <option value="UZ" {{ ($user->country == 'UZ') ? "selected='selected'" : '' }}>Uzbekistan</option>
                                    <option value="VU" {{ ($user->country == 'VU') ? "selected='selected'" : '' }}>Vanuatu</option>
                                    <option value="VE" {{ ($user->country == 'VE') ? "selected='selected'" : '' }}>Venezuela</option>
                                    <option value="VN" {{ ($user->country == 'VN') ? "selected='selected'" : '' }}>Viet Nam</option>
                                    <option value="VG" {{ ($user->country == 'VG') ? "selected='selected'" : '' }}>Virgin Islands, British</option>
                                    <option value="VI" {{ ($user->country == 'VI') ? "selected='selected'" : '' }}>Virgin Islands, U.s.</option>
                                    <option value="WF" {{ ($user->country == 'WF') ? "selected='selected'" : '' }}>Wallis And Futuna</option>
                                    <option value="EH" {{ ($user->country == 'EH') ? "selected='selected'" : '' }}>Western Sahara</option>
                                    <option value="YE" {{ ($user->country == 'YE') ? "selected='selected'" : '' }}>Yemen</option>
                                    <option value="ZM" {{ ($user->country == 'ZM') ? "selected='selected'" : '' }}>Zambia</option>
                                    <option value="ZW" {{ ($user->country == 'ZW') ? "selected='selected'" : '' }}>Zimbabwe</option>
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
                                <label for="postal_code">Zip Code (Limit 255 Chars)</label>
								</br>
								<div style="width:300px;float:left">
                                <input type="text" readonly name="postal_code"  onkeydown="restrictTextField(event,255)" class="form-control" id="postal_code" value="{{ old('postal_code', $user->postal_code)}}">
                                 @if ($errors->has('postal_code')) <p class="help-block">{{ $errors->first('postal_code') }}</p> @endif
                               </div>
								<div style="width:95px;float:right">
								<select class="form-control" id="postal_code_bit"  name="postal_code_bit">
								 
								 <option value="0">Public</option>
								 <option {{ (in_array('postal_code',$private_flags)) ? "selected='selected'" : '' }} value="postal_code">Private</option>
								</select> 
								</div> 
							</div>

							<div class="col-sm-6 margin-btm-1">
                                <div class="form-group">
			                        <label>Choose default algorithm preferences</label>
			                        <select name="default_algo" id="default_algo" class="form-control">
			                        @foreach(\App\Model\Algorithm::getList() as $key=>$algo)
			                            <option value="{{$key}}" {{ $user->default_algo == $key ? 'selected' : ''}}>{{$algo}}</option>
			                        @endforeach
			                        </select>
			                    </div>							
			                </div>
                        </div>
                        <button type="submit" id="update_profile" class="btn btn-login">Update</button>

                    </form>  
        </div>
    </div>   
    <div class="post"> </div>
 </div></div>
</div>  <!-- /.right-whitePnl-->
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB9KjPwjqnwoqvXVV3POGI0_hMXKcPxvDM&libraries=places"></script>
    <script>
    	var ifAddressSelected = false;
    	var oldAddressVal = document.getElementById("address_1").value;
    	let placeSearch;
		let autocomplete;
		const componentForm = {
		  address_1: "short_name",
		  address_2: "long_name",
		  city: "long_name",
		  state: "long_name",
		  country: "short_name",
		  postal_code: "short_name",
		};
		const components = {
			neighborhood:'address_1',
			street_number:'address_1',
			sublocality_level_1:'address_1',
			route:'address_1',
			locality:'address_2',
			administrative_area_level_2:'city',
			administrative_area_level_1:'state',
			country:'country',
			postal_code:'postal_code'
		}
		function initAutocomplete() {
		ifAddressSelected = false;
	  	autocomplete = new google.maps.places.Autocomplete(
	    document.getElementById("address_1"),
	    { types: ["geocode"] }
	  );
	  autocomplete.setFields(["address_component"]);
	  autocomplete.addListener("place_changed", fillInAddress);
	}
    function blankAddress(){
		var val = document.getElementById('address_1').value;
		if(val =='' || val == null){
			Object.keys(componentForm).forEach(function(k,val){
				if(k == 'country'){
					document.getElementById('country_select').value = '';
				}
				document.getElementById(k).value = "";
			})
		}
	}

	function emptyAddress(){
		var val = document.getElementById('address_1').value;
		if((oldAddressVal != val) && (val =='' || val ==null || !ifAddressSelected)){
			Object.keys(componentForm).forEach(function(k,val){
				if(k == 'country'){
					document.getElementById('country_select').value = '';
				}
				document.getElementById(k).value = "";
			})
		}
		ifAddressSelected = false;
	}

	function fillInAddress() {
	  document.getElementById('address_1').value = "";
	  const place = autocomplete.getPlace();	  
	  ifAddressSelected = true;
	  if(place && place.address_components)
	  for (const component of place.address_components) {
	       const addressType = component.types[0];
	    if (components[addressType]) {

	      var val = component[componentForm[components[addressType]]];
	      var oldVal = document.getElementById(components[addressType]).value;
	      if(oldVal !='' && components[addressType] == 'address_1'){
	      	val = oldVal+","+val	
	      }	      
	      document.getElementById(components[addressType]).value = val;
	      if(components[addressType] == 'country'){
	      		document.getElementById('country_select').value = val;
			}
	    }
	  }
	}
	function geolocate() {
	  if (navigator.geolocation) {
	    navigator.geolocation.getCurrentPosition((position) => {
	      const geolocation = {
	        lat: position.coords.latitude,
	        lng: position.coords.longitude,
	      };
	      const circle = new google.maps.Circle({
	        center: geolocation,
	        radius: position.coords.accuracy,
	      });
	      autocomplete.setBounds(circle.getBounds());
	    });
	  }
	}
        $(document).ready(function () {
            $("#birthday").datepicker({
                changeMonth: true,
                changeYear: true,
				maxDate: 0
            });
            $('#mobile_carrier').change(function(e){
            	if(e.target.value == 'non_usa'){
            		$('#verifyButton').hide();
            	}else{
            		$('#verifyButton').show();
            	}
            })

           
            
        })
        function checkCarrier(){
        	var val = $('#mobile_carrier').val();
        	if(val == 'non_usa'){
            		$('#verifyButton').hide();
            	}else{
            		$('#verifyButton').show();
            	}
        }

        function onlyNumbers(e){
        	var regex = /^[0-9]+$/;
        	var value_length = ($(e.target).val()).length;
        	if( !regex.test($(e.target).val()) ) {
  				e.preventDefault();
  				$(e.target).val($(e.target).val().substring(0,value_length-1));
  			}

        }

checkCarrier();
//outlinecall(88,36,true);
    </script>


    @endsection

