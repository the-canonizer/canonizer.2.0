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
        <div class="col-md-4 panel-warning">
             <button id="copydatabase" class="btn btn-primary">Copy Production Database To Staging</button>
        </div>
        <div class="col-md-4 panel-warning">
            <button id="copyfiles" class="btn btn-primary">Copy Production Files To Staging</button>
         </div>
    </div>
    <script>
         $(document).ready(function () {
            

            $('#copyfiles').click(function(){
                   $.ajax({
                    type:'GET',
                    url:"https://canonizer.com/archievefiles",
                    beforeSend:function(){

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

                                    },
                                    complete:function(){
                                        
                                    }
                                });
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

                    },

                    success:function(response){

                    },
                    complete:function(){

                    }
                })
            });

         });

    </script>
@endsection