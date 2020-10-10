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
							<button type="submit" id="verify_phone_email" class="btn btn-login">Verify</button>
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
                                <label for="address_1">Address Line 1 (Limit 255 Chars) </label>
								</br>
								<div style="width:300px;float:left">
                                <input type="text" onkeydown="restrictTextField(event,255)" onfocus="initAutocomplete()" name="address_1" class="form-control" id="address_1" value="{{ old('address_1', $user->address_1)}}">
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
                                <input type="text" name="address_2" onkeydown="restrictTextField(event,255)" class="form-control" id="address_2" value="{{ old('address_2', $user->address_2)}}">
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
                                <input type="text" onkeydown="restrictTextField(event,255)" name="city" class="form-control" id="city" value="{{ old('city', $user->city)}}">
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
                                <input type="text" onkeydown="restrictTextField(event,255)" name="state" class="form-control" id="state" value="{{ old('state', $user->state)}}">
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
                                	 <option value=""> -- Select -- </option>
                                    <option value="AF">AFGHANISTAN</option>
                                    <option value="AL">ALBANIA</option>
                                    <option value="DZ">ALGERIA</option>
                                    <option value="AS">AMERICAN SAMOA</option>
                                    <option value="AD">ANDORRA</option>
                                    <option value="AO">ANGOLA</option>
                                    <option value="AI">ANGUILLA</option>
                                    <option value="AQ">ANTARCTICA</option>
                                    <option value="AG">ANTIGUA AND BARBUDA</option>
                                    <option value="AR">ARGENTINA</option>
                                    <option value="AM">ARMENIA</option>
                                    <option value="AW">ARUBA</option>
                                    <option value="AU">AUSTRALIA</option>
                                    <option value="AT">AUSTRIA</option>
                                    <option value="AZ">AZERBAIJAN</option>
                                    <option value="BS">BAHAMAS</option>
                                    <option value="BH">BAHRAIN</option>
                                    <option value="BD">BANGLADESH</option>
                                    <option value="BB">BARBADOS</option>
                                    <option value="BY">BELARUS</option>
                                    <option value="BE">BELGIUM</option>
                                    <option value="BZ">BELIZE</option>
                                    <option value="BJ">BENIN</option>
                                    <option value="BM">BERMUDA</option>
                                    <option value="BT">BHUTAN</option>
                                    <option value="BO">BOLIVIA</option>
                                    <option value="BA">BOSNIA AND HERZEGOVINA</option>
                                    <option value="BW">BOTSWANA</option>
                                    <option value="BV">BOUVET ISLAND</option>
                                    <option value="BR">BRAZIL</option>
                                    <option value="IO">BRITISH INDIAN OCEAN TERRITORY</option>
                                    <option value="BN">BRUNEI DARUSSALAM</option>
                                    <option value="BG">BULGARIA</option>
                                    <option value="BF">BURKINA FASO</option>
                                    <option value="BI">BURUNDI</option>
                                    <option value="KH">CAMBODIA</option>
                                    <option value="CM">CAMEROON</option>
                                    <option value="CA">CANADA</option>
                                    <option value="CV">CAPE VERDE</option>
                                    <option value="KY">CAYMAN ISLANDS</option>
                                    <option value="CF">CENTRAL AFRICAN REPUBLIC</option>
                                    <option value="TD">CHAD</option>
                                    <option value="CL">CHILE</option>
                                    <option value="CN">CHINA</option>
                                    <option value="CX">CHRISTMAS ISLAND</option>
                                    <option value="CC">COCOS (KEELING) ISLANDS</option>
                                    <option value="CO">COLOMBIA</option>
                                    <option value="KM">COMOROS</option>
                                    <option value="CG">CONGO</option>
                                    <option value="CD">CONGO</option>
                                    <option value="CK">COOK ISLANDS</option>
                                    <option value="CR">COSTA RICA</option>
                                    <option value="CI">COTE D'IVOIRE</option>
                                    <option value="HR">CROATIA</option>
                                    <option value="CU">CUBA</option>
                                    <option value="CY">CYPRUS</option>
                                    <option value="CZ">CZECH REPUBLIC</option>
                                    <option value="DK">DENMARK</option>
                                    <option value="DJ">DJIBOUTI</option>
                                    <option value="DM">DOMINICA</option>
                                    <option value="DO">DOMINICAN REPUBLIC</option>
                                    <option value="EC">ECUADOR</option>
                                    <option value="EG">EGYPT</option>
                                    <option value="SV">EL SALVADOR</option>
                                    <option value="GQ">EQUATORIAL GUINEA</option>
                                    <option value="ER">ERITREA</option>
                                    <option value="EE">ESTONIA</option>
                                    <option value="ET">ETHIOPIA</option>
                                    <option value="FK">FALKLAND ISLANDS (MALVINAS)</option>
                                    <option value="FO">FAROE ISLANDS</option>
                                    <option value="FJ">FIJI</option>
                                    <option value="FI">FINLAND</option>
                                    <option value="FR">FRANCE</option>
                                    <option value="GF">FRENCH GUIANA</option>
                                    <option value="PF">FRENCH POLYNESIA</option>
                                    <option value="TF">FRENCH SOUTHERN TERRITORIES</option>
                                    <option value="GA">GABON</option>
                                    <option value="GM">GAMBIA</option>
                                    <option value="GE">GEORGIA</option>
                                    <option value="DE">GERMANY</option>
                                    <option value="GH">GHANA</option>
                                    <option value="GI">GIBRALTAR</option>
                                    <option value="GR">GREECE</option>
                                    <option value="GL">GREENLAND</option>
                                    <option value="GD">GRENADA</option>
                                    <option value="GP">GUADELOUPE</option>
                                    <option value="GU">GUAM</option>
                                    <option value="GT">GUATEMALA</option>
                                    <option value="GN">GUINEA</option>
                                    <option value="GW">GUINEA-BISSAU</option>
                                    <option value="GY">GUYANA</option>
                                    <option value="HT">HAITI</option>
                                    <option value="HM">HEARD ISLAND AND MCDONALD ISLANDS</option>
                                    <option value="VA">HOLY SEE (VATICAN CITY STATE)</option>
                                    <option value="HN">HONDURAS</option>
                                    <option value="HK">HONG KONG</option>
                                    <option value="HU">HUNGARY</option>
                                    <option value="IS">ICELAND</option>
                                    <option value="IN">INDIA</option>
                                    <option value="ID">INDONESIA</option>
                                    <option value="IR">IRAN, ISLAMIC REPUBLIC OF</option>
                                    <option value="IQ">IRAQ</option>
                                    <option value="IE">IRELAND</option>
                                    <option value="IL">ISRAEL</option>
                                    <option value="IT">ITALY</option>
                                    <option value="JM">JAMAICA</option>
                                    <option value="JP">JAPAN</option>
                                    <option value="JO">JORDAN</option>
                                    <option value="KZ">KAZAKHSTAN</option>
                                    <option value="KE">KENYA</option>
                                    <option value="KI">KIRIBATI</option>
                                    <option value="KP">KOREA, DEMOCRATIC PEOPLE'S REPUBLIC OF</option>
                                    <option value="KR">KOREA, REPUBLIC OF</option>
                                    <option value="KW">KUWAIT</option>
                                    <option value="KG">KYRGYZSTAN</option>
                                    <option value="LA">LAO PEOPLE'S DEMOCRATIC REPUBLIC</option>
                                    <option value="LV">LATVIA</option>
                                    <option value="LB">LEBANON</option>
                                    <option value="LS">LESOTHO</option>
                                    <option value="LR">LIBERIA</option>
                                    <option value="LY">LIBYAN ARAB JAMAHIRIYA</option>
                                    <option value="LI">LIECHTENSTEIN</option>
                                    <option value="LT">LITHUANIA</option>
                                    <option value="LU">LUXEMBOURG</option>
                                    <option value="MO">MACAO</option>
                                    <option value="MK">MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF</option>
                                    <option value="MG">MADAGASCAR</option>
                                    <option value="MW">MALAWI</option>
                                    <option value="MY">MALAYSIA</option>
                                    <option value="MV">MALDIVES</option>
                                    <option value="ML">MALI</option>
                                    <option value="MT">MALTA</option>
                                    <option value="MH">MARSHALL ISLANDS</option>
                                    <option value="MQ">MARTINIQUE</option>
                                    <option value="MR">MAURITANIA</option>
                                    <option value="MU">MAURITIUS</option>
                                    <option value="YT">MAYOTTE</option>
                                    <option value="MX">MEXICO</option>
                                    <option value="FM">MICRONESIA, FEDERATED STATES OF</option>
                                    <option value="MD">MOLDOVA, REPUBLIC OF</option>
                                    <option value="MC">MONACO</option>
                                    <option value="MN">MONGOLIA</option>
                                    <option value="MS">MONTSERRAT</option>
                                    <option value="MA">MOROCCO</option>
                                    <option value="MZ">MOZAMBIQUE</option>
                                    <option value="MM">MYANMAR</option>
                                    <option value="NA">NAMIBIA</option>
                                    <option value="NR">NAURU</option>
                                    <option value="NP">NEPAL</option>
                                    <option value="NL">NETHERLANDS</option>
                                    <option value="AN">NETHERLANDS ANTILLES</option>
                                    <option value="NC">NEW CALEDONIA</option>
                                    <option value="NZ">NEW ZEALAND</option>
                                    <option value="NI">NICARAGUA</option>
                                    <option value="NE">NIGER</option>
                                    <option value="NG">NIGERIA</option>
                                    <option value="NU">NIUE</option>
                                    <option value="NF">NORFOLK ISLAND</option>
                                    <option value="MP">NORTHERN MARIANA ISLANDS</option>
                                    <option value="NO">NORWAY</option>
                                    <option value="OM">OMAN</option>
                                    <option value="PK">PAKISTAN</option>
                                    <option value="PW">PALAU</option>
                                    <option value="PS">PALESTINIAN TERRITORY, OCCUPIED</option>
                                    <option value="PA">PANAMA</option>
                                    <option value="PG">PAPUA NEW GUINEA</option>
                                    <option value="PY">PARAGUAY</option>
                                    <option value="PE">PERU</option>
                                    <option value="PH">PHILIPPINES</option>
                                    <option value="PN">PITCAIRN</option>
                                    <option value="PL">POLAND</option>
                                    <option value="PT">PORTUGAL</option>
                                    <option value="PR">PUERTO RICO</option>
                                    <option value="QA">QATAR</option>
                                    <option value="RE">REUNION</option>
                                    <option value="RO">ROMANIA</option>
                                    <option value="RU">RUSSIAN FEDERATION</option>
                                    <option value="RW">RWANDA</option>
                                    <option value="SH">SAINT HELENA</option>
                                    <option value="KN">SAINT KITTS AND NEVIS</option>
                                    <option value="LC">SAINT LUCIA</option>
                                    <option value="PM">SAINT PIERRE AND MIQUELON</option>
                                    <option value="VC">SAINT VINCENT AND THE GRENADINES</option>
                                    <option value="WS">SAMOA</option>
                                    <option value="SM">SAN MARINO</option>
                                    <option value="ST">SAO TOME AND PRINCIPE</option>
                                    <option value="SA">SAUDI ARABIA</option>
                                    <option value="SN">SENEGAL</option>
                                    <option value="CS">SERBIA AND MONTENEGRO</option>
                                    <option value="SC">SEYCHELLES</option>
                                    <option value="SL">SIERRA LEONE</option>
                                    <option value="SG">SINGAPORE</option>
                                    <option value="SK">SLOVAKIA</option>
                                    <option value="SI">SLOVENIA</option>
                                    <option value="SB">SOLOMON ISLANDS</option>
                                    <option value="SO">SOMALIA</option>
                                    <option value="ZA">SOUTH AFRICA</option>
                                    <option value="GS">SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS</option>
                                    <option value="ES">SPAIN</option>
                                    <option value="LK">SRI LANKA</option>
                                    <option value="SD">SUDAN</option>
                                    <option value="SR">SURINAME</option>
                                    <option value="SJ">SVALBARD AND JAN MAYEN</option>
                                    <option value="SZ">SWAZILAND</option>
                                    <option value="SE">SWEDEN</option>
                                    <option value="CH">SWITZERLAND</option>
                                    <option value="SY">SYRIAN ARAB REPUBLIC</option>
                                    <option value="TW">TAIWAN, PROVINCE OF CHINA</option>
                                    <option value="TJ">TAJIKISTAN</option>
                                    <option value="TZ">TANZANIA, UNITED REPUBLIC OF</option>
                                    <option value="TH">THAILAND</option>
                                    <option value="TL">TIMOR-LESTE</option>
                                    <option value="TG">TOGO</option>
                                    <option value="TK">TOKELAU</option>
                                    <option value="TO">TONGA</option>
                                    <option value="TT">TRINIDAD AND TOBAGO</option>
                                    <option value="TN">TUNISIA</option>
                                    <option value="TR">TURKEY</option>
                                    <option value="TM">TURKMENISTAN</option>
                                    <option value="TC">TURKS AND CAICOS ISLANDS</option>
                                    <option value="TV">TUVALU</option>
                                    <option value="UG">UGANDA</option>
                                    <option value="UA">UKRAINE</option>
                                    <option value="AE">UNITED ARAB EMIRATES</option>
                                    <option value="GB">UNITED KINGDOM</option>
                                    <option value="US">UNITED STATES</option>
                                    <option value="UM">UNITED STATES MINOR OUTLYING ISLANDS</option>
                                    <option value="UY">URUGUAY</option>
                                    <option value="UZ">UZBEKISTAN</option>
                                    <option value="VU">VANUATU</option>
                                    <option value="VE">VENEZUELA</option>
                                    <option value="VN">VIET NAM</option>
                                    <option value="VG">VIRGIN ISLANDS, BRITISH</option>
                                    <option value="VI">VIRGIN ISLANDS, U.S.</option>
                                    <option value="WF">WALLIS AND FUTUNA</option>
                                    <option value="EH">WESTERN SAHARA</option>
                                    <option value="YE">YEMEN</option>
                                    <option value="ZM">ZAMBIA</option>
                                    <option value="ZW">ZIMBABWE</option>
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
                                <input type="text" name="postal_code"  onkeydown="restrictTextField(event,255)" class="form-control" id="postal_code" value="{{ old('postal_code', $user->postal_code)}}">
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
 </div></div>
</div>  <!-- /.right-whitePnl-->
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB9KjPwjqnwoqvXVV3POGI0_hMXKcPxvDM&libraries=places"></script>
    <script>
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
			route:'address_1',
			locality:'address_2',
			administrative_area_level_2:'city',
			administrative_area_level_1:'state',
			country:'country',
			postal_code:'postal_code'
		}
		function initAutocomplete() {
	  autocomplete = new google.maps.places.Autocomplete(
	    document.getElementById("address_1"),
	    { types: ["geocode"] }
	  );
	  autocomplete.setFields(["address_component"]);
	  autocomplete.addListener("place_changed", fillInAddress);
	}

	function fillInAddress() {
	  const place = autocomplete.getPlace();
	  for (const component in componentForm) {
	    document.getElementById(component).value = "";
	    if(component == 'address_1'){
	    	document.getElementById(component).disabled = false;
	    }else{
	    	document.getElementById(component).disabled = true;
	    }	
	    
	  }

	  console.log(' place.address_components', place.address_components);
	  for (const component of place.address_components) {
	       const addressType = component.types[0];
	       console.log('addressType',addressType,components[addressType]);
	    if (components[addressType]) {
	      var val = component[componentForm[components[addressType]]];
	      var oldVal = document.getElementById(components[addressType]).value;
	      if(oldVal !=''){
	      	val = oldVal+","+val	
	      }
	      
	      document.getElementById(components[addressType]).value = val;
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
        })
        function onlyNumbers(e){
        	var regex = /^[0-9]+$/;
        	var value_length = ($(e.target).val()).length;
        	if( !regex.test($(e.target).val()) ) {
  				e.preventDefault();
  				$(e.target).val($(e.target).val().substring(0,value_length-1));
  			}

        }
    </script>


    @endsection

