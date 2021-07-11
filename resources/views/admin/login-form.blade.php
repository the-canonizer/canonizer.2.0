<div class="row">
        <div class="col-md-4 panel-warning" style="margin:0px auto;">
            <div class="content-box-header panel-heading">
                <div class="panel-title ">Login</div>
                
                
            </div>
            <div class="content-box-large box-with-header">
                <form method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
								 
                    <fieldset>
                        <div class="form-group">
                            <label>Email <span style="color:red">*</span></label>
                            <input name="email" class="form-control" placeholder="Email" value="" type="text">
                             @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
                        </div>
                        <div class="form-group">
                            <label>Password <span style="color:red">*</span></label>
                           <input name="password" class="form-control" placeholder="Password" value="" type="password">
                            @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
                        </div>
                    </fieldset>
                    <div>
                        <button id="submit" onClick="submitLoginForm(this)" class="btn btn-primary">
                            Login
                        </button>
                        <div id="loggingin" class="btn btn-primary" style="display:none;">Logging in ..</div>
                    </div>
                    
                </form>
			  				
			  			
            </div>
        </div>
    </div>

    <script>
        
        function submitLoginForm(btn){
        $('#submit').hide();
        $('#loggingin').show();
        return true;
    }
    </script>