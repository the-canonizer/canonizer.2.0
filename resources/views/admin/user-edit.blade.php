@extends('admin.app')
@section('content')
    <div class="row">
        <div class="col-md-12 panel-warning">
            <div class="content-box-header panel-heading">
                <div class="panel-title ">Edit User</div>
                
                
            </div>
            <div class="content-box-large box-with-header">
                <div class="panel-body">
			  					<form method="POST" onsubmit="return submitForm(this)">
                                  <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
								 
									<fieldset >
                                    <div class="row">
                                        <div class="col-md-4">
										<div class="form-group">
											<label>First Name (Limit 100 Chars)<span style="color:red">*</span></label>
											<input name="first_name" onkeydown="restrictTextField(event,100)"  class="form-control" value="{{ $user->first_name }}" placeholder="First name" type="text">
											@if ($errors->has('first_name')) <p class="help-block">{{ $errors->first('first_name') }}</p> @endif
										</div>
                                        </div>
                                        <div class="col-md-4">
										<div class="form-group">
											<label>Middle Name (Limit 100 Chars)</label>
											<input name="middle_name" onkeydown="restrictTextField(event,100)" class="form-control" value="{{ $user->middle_name }}" placeholder="Middle name" type="text">
											@if ($errors->has('middle_name')) <p class="help-block">{{ $errors->first('middle_name') }}</p> @endif
										</div>
                                        </div>
                                        <div class="col-md-4">
										<div class="form-group">
											<label>Last Name  (Limit 100 Chars)<span style="color:red">*</span></label>
											<input name="last_name" onkeydown="restrictTextField(event,100)"  class="form-control" value="{{ $user->last_name }}" placeholder="Last name" type="text">
											@if ($errors->has('last_name')) <p class="help-block">{{ $errors->first('last_name') }}</p> @endif
										</div>
                                        </div>
										</div>
                                         <div class="row">
                                         <h4 class="col-md-12">Address Information</h4>
                                         </div>
                                        <div class="row">
                                        
                                        <div class="col-md-6">
										<div class="form-group">
											<label>Address Line 1 (Limit 500 Chars)</label>
											<input name="address_1"  onkeydown="restrictTextField(event,500)" class="form-control" value="{{ $user->address_1 }}" placeholder="Address line 1" type="text">
											@if ($errors->has('address_1')) <p class="help-block">{{ $errors->first('address_1') }}</p> @endif
										</div>
                                        </div>
                                        <div class="col-md-6">
										<div class="form-group">
											<label>Address Line 2 (Limit 500 Chars)</label>
											<input name="address_2" onkeydown="restrictTextField(event,500)" class="form-control" value="{{ $user->address_2 }}" placeholder="Address Line 2" type="text">
											@if ($errors->has('address_2')) <p class="help-block">{{ $errors->first('address_2') }}</p> @endif
										</div>
                                        </div>
                                        <div class="col-md-3">
										<div class="form-group">
											<label>City (Limit 50 Chars)</label>
											<input name="city"  onkeydown="restrictTextField(event,50)" class="form-control" value="{{ $user->city }}" placeholder="City" type="text">
											@if ($errors->has('city')) <p class="help-block">{{ $errors->first('city') }}</p> @endif
										</div>
                                        </div>
                                        <div class="col-md-3">
										<div class="form-group">
											<label>State (Limit 50 Chars)</label>
											<input name="state" onkeydown="restrictTextField(event,50)" class="form-control" value="{{ $user->state }}" placeholder="State" type="text">
											@if ($errors->has('state')) <p class="help-block">{{ $errors->first('state') }}</p> @endif
										</div>
                                        </div>
                                        <div class="col-md-3">
										<div class="form-group">
											<label>Pincode (Limit 10 Chars)</label>
											<input name="postal_code"  onkeydown="restrictTextField(event,10)" class="form-control" value="{{ $user->postal_code }}" placeholder="Postal Code" type="text">
											@if ($errors->has('postal_code')) <p class="help-block">{{ $errors->first('postal_code') }}</p> @endif
										</div>
                                        </div>
                                        <div class="col-md-3">
										<div class="form-group">
											<label>Country (Limit 20 Chars)</label>
											<input name="country" onkeydown="restrictTextField(event,20)" class="form-control" value="{{ $user->country }}" placeholder="Country" type="text">
											@if ($errors->has('country')) <p class="help-block">{{ $errors->first('country') }}</p> @endif
										</div>
                                        </div>
										</div>
									</fieldset>
									<div>
										<button type="submit" class="btn btn-primary">
											<i class="fa fa-save"></i>
											Submit
										</button>
									</div>
								</form>
			  				</div>
            </div>
        </div>
    </div>
@endsection