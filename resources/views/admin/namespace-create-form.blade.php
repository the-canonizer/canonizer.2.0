@extends('admin.app')
@section('content')
    <div class="row">
        <div class="col-md-12 panel-warning">
            <div class="content-box-header panel-heading">
                <div class="panel-title ">Create Namespace</div>
                
                
            </div>
            <div class="content-box-large box-with-header">
                
			  				<div class="panel-body">
			  					<form method="POST">
                                  <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                  <input type="hidden" name="page" value="<?php echo $_GET['page']; ?>">
								  @if($namespaceRequest)
								  <input type="hidden" name="requestId" value="{{$namespaceRequest->id}}"/>
								  @endif
								  
									<fieldset>
										<div class="form-group">
											<label>Namespace Name ( Limit 100 Chars ) <span style="color:red">*</span></label>
											
											<input name="name" onkeydown="restrictTextField(event,100)" value="{{ $namespaceRequest ? $namespaceRequest->name : '' }}" class="form-control" placeholder="Namespace name" type="text">
											 @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
										</div>
										<div class="form-group">
											<label>Parent Namespace</label>
											<select name="parent_id" class="form-control">
                                                <option value="0">-- No Parent -- </option>
                                                @foreach($namespaces as $namespace)
                                                    <option value="{{$namespace->id}}">{{$namespace->name}}</option>
                                                @endforeach
                                            </select>
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