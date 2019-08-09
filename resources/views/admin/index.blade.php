@extends('admin.app')
@section('content')
 <div class="row">
        <div class="col-md-4 panel-warning">
            <div class="content-box-header panel-heading">
                <div class="panel-title ">Total Live Topics</div>
                
                <div class="panel-options">
                    <i class="fa fa-hand-o-right"></i> 12
                </div>
            </div>
			<br/><br/>
			
			
          
        </div>
		<div class="col-md-4 panel-warning">
		    <div class="content-box-header panel-heading">
                <div class="panel-title ">Total Live Camps</div>
                
                <div class="panel-options">
                    <i class="fa fa-hand-o-right"></i> 12
                </div>
            </div>
		</div>
		
		<div class="col-md-4 panel-warning">
		    <div class="content-box-header panel-heading">
                <div class="panel-title ">Total Active Persons</div>
                
                <div class="panel-options">
                    <i class="fa fa-hand-o-right"></i> 12
                </div>
            </div>
		</div>
        @if (env('APP_ENV')!='Production')
       <div class="col-md-4 panel-warning">
             <button id="copydatabase" class="btn btn-primary">Copy Production Database To Staging</button>
        </div>
        <div class="col-md-4 panel-warning">
            <button id="copyfiles" class="btn btn-primary">Copy Production Files To Staging</button>
         </div>
        @endif
        
    </div>

    <div id="loader_div" class="modal" style="display: none">
    <div class="center">
        <img alt="" src="/img/ajax-loader.gif" />
        </div>
    </div>
    <script>
         $(document).ready(function () {
            

            $('#copyfiles').click(function(){
                   $.ajax({
                    type:'GET',
                    url:"https://canonizer.com/archievefiles",
                    beforeSend:function(){
                        $('#loader_div').show();
                    },
                    success:function(response){
                        if(response == 'SUCCESS'){
                                 $.ajax({
                                    type:'POST',
                                     headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    url:"{{ url('/admin/copyfiles') }}",
                                    beforeSend:function(){

                                    },

                                    success:function(response){
                                         $.ajax({
                                            type:'GET',
                                            url:"https://canonizer.com/removearchievefiles",
                                            beforeSend:function(){

                                            },
                                            success:function(){
                                                 $('#loader_div').hide();
                                            }
                                         });
                                    },
                                    complete:function(){
                                        
                                    }
                                });
                        }else{
                             $('#loader_div').hide();

                        }
                    },
                    complete:function(){

                    }
                   });
                   
            });

          
            $('#copydatabase').click(function(){
                $.ajax({
                    type:'POST',
                     headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    url:" {{url('/admin/copydatabase') }} ",
                    beforeSend:function(){
                        $('#loader_div').show();
                    },

                    success:function(response){
                        $('#loader_div').hide();
                    },
                    complete:function(){

                    }
                })
            });

         });

    </script>
@endsection