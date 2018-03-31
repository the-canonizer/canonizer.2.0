<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Canonizer Admin</title>

        <link rel="shortcut icon" href="img/favicon.ico" >
        <!-- Bootstrap core CSS-->
        <link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet">
        <!-- Custom fonts for this template-->
         <link href="{{ URL::asset('/css/admin.css') }}" rel="stylesheet">

        <link href="{{ URL::asset('/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
         <script src="{{ URL::asset('/js/jquery.min.js') }}"></script>
       
    </head>
<body>
<div class="header">
	     <div class="container">
	        <div class="row">
	           <div class="col-md-5">
	              <!-- Logo -->
	              <div class="logo">
	                 <h1><a href="index.html">Canonizer Admin</a></h1>
	              </div>
	           </div>
	        </div>
	     </div>
	</div>

    <div class="page-content">
    	<div class="row">
		  
		  <div class="col-md-12">
		      @include('admin.login-form');
		  </div>
		</div>
    </div>

    <footer>
         <div class="container">

            <div class="copy text-center">
               Copyright &copy;<?=date('Y')?>
            </div>
            
         </div>
      </footer>
</body>
</html>
