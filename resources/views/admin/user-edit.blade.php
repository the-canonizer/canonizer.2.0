@extends('admin.app')
@section('content')
    <div class="row">
        <div class="col-md-12 panel-warning">
            <div class="content-box-header panel-heading">
                <div class="panel-title ">Edit User</div>
                
                
            </div>
            <div class="content-box-large box-with-header">
                <div class="panel-body">
			  					<form method="POST">
                                  <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
								 
									<fieldset >
                                    <div class="row">
                                        <div class="col-md-4">
										<div class="form-group">
											<label>First Name (Limit 100 Chars)<span style="color:red">*</span></label>
											<input name="first_name"  class="form-control" value="{{ $user->first_name }}" placeholder="First name" type="text">
											@if ($errors->has('first_name')) <p class="help-block">{{ $errors->first('first_name') }}</p> @endif
										</div>
                                        </div>
                                        <div class="col-md-4">
										<div class="form-group">
											<label>Middle Name</label>
											<input name="middle_name"  class="form-control" value="{{ $user->middle_name }}" placeholder="Middle name" type="text">
											@if ($errors->has('middle_name')) <p class="help-block">{{ $errors->first('middle_name') }}</p> @endif
										</div>
                                        </div>
                                        <div class="col-md-4">
										<div class="form-group">
											<label>Last Name  (Limit 100 Chars)<span style="color:red">*</span></label>
											<input name="last_name"  class="form-control" value="{{ $user->last_name }}" placeholder="Last name" type="text">
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
											<label>Address Line 1</label>
											<input name="address_1"  class="form-control" value="{{ $user->address_1 }}" placeholder="Address line 1" type="text">
										</div>
                                        </div>
                                        <div class="col-md-6">
										<div class="form-group">
											<label>Address Line 2</label>
											<input name="address_2"  class="form-control" value="{{ $user->address_2 }}" placeholder="Address Line 2" type="text">
										</div>
                                        </div>
                                        <div class="col-md-3">
										<div class="form-group">
											<label>City</label>
											<input name="city"  class="form-control" value="{{ $user->city }}" placeholder="City" type="text">
										</div>
                                        </div>
                                        <div class="col-md-3">
										<div class="form-group">
											<label>State</label>
											<input name="state"  class="form-control" value="{{ $user->state }}" placeholder="State" type="text">
										</div>
                                        </div>
                                        <div class="col-md-3">
										<div class="form-group">
											<label>Pincode</label>
											<input name="postal_code"  class="form-control" value="{{ $user->postal_code }}" placeholder="Postal Code" type="text">
										</div>
                                        </div>
                                        <div class="col-md-3">
										<div class="form-group">
											<label>Country</label>
											<input name="country" class="form-control" value="{{ $user->country }}" placeholder="Country" type="text">
										</div>
                                        </div>
										</div>
									</fieldset>
									<div>
										<button class="btn btn-primary">
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