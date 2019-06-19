@extends('admin.app')
@section('content')
    <div class="row">
        <div class="col-md-12 panel-warning">
            <div class="content-box-header panel-heading">
                <div class="panel-title ">Video Podcast</div>
                
                
            </div>
            <div class="content-box-large box-with-header">
                <div class="panel-body">
			  					<form method="POST">
                                  <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
								  <input type="hidden" name="id" value="{{$video->id}}" />
									<fieldset >
                                    <div class="row">
                                        <div class="col-md-12">
										<div class="form-group">
											<label>Edit Or Create Html for video podcast</label>
										<textarea id="podcastEditor" name="html_content">{{$video->html_content}}
											</textarea>
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
									<script type="text/javascript">
										
										CKEDITOR.replace('podcastEditor',{
											startupMode: 'source'
										});
									</script>
								</form>
			  				</div>
            </div>
        </div>
    </div>
@endsection